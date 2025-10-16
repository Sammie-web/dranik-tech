<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaystackService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->middleware('auth');
        $this->paystackService = $paystackService;
    }

    /**
     * Initialize payment for a booking
     */
    public function initialize(Request $request, Booking $booking)
    {
        // Log incoming initialize attempts to help debug UI/redirect issues.
        \Log::info('PaymentController::initialize called', [
            'user_id' => auth()->id(),
            'booking_id' => $booking->id,
            'gateway_input' => $request->input('gateway'),
            'uri' => $request->getRequestUri(),
        ]);

        // Ensure user can only pay for their own booking
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        // Check if payment is already completed
        if ($booking->payment && $booking->payment->status === 'completed') {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'Payment has already been completed for this booking.');
        }

        $rules = [
            'gateway' => 'required|in:paystack',
        ];

        // If the service is negotiable allow a negotiated_amount input
        if ($booking->service && $booking->service->price_type === 'negotiable') {
            $rules['negotiated_amount'] = 'required|numeric|min:0.01|max:' . $booking->service->price;
        } else {
            $rules['negotiated_amount'] = 'nullable|numeric|min:0.01';
        }

        $request->validate($rules);

        $gateway = $request->gateway;
        $user = auth()->user();

        // Prepare payment data
        // If a negotiated amount was provided, update the booking & payment amounts
        $negotiated = $request->input('negotiated_amount');
        if ($negotiated) {
            $negotiated = (float) $negotiated;
            // Update booking amount and commission accordingly (commission still based on amount)
            $commission = round($negotiated * 0.05, 2);
            $booking->update(['amount' => $negotiated, 'commission' => $commission]);

            // Update payment record if it exists
            if ($booking->payment) {
                $booking->payment->update(['amount' => $negotiated, 'commission' => $commission, 'provider_amount' => $negotiated - $commission]);
            }
        }

        $paymentData = [
            'email' => $user->email,
            'amount' => $booking->amount,
            'reference' => $this->generatePaymentReference($gateway),
            'callback_url' => route('payments.callback', ['gateway' => $gateway]),
            'booking_id' => $booking->id,
            'customer_id' => $user->id,
            'provider_id' => $booking->provider_id,
            'service_title' => $booking->service->title,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone,
        ];

        try {
            // Initialize payment with selected gateway
            // Only Paystack is supported
            $result = $this->paystackService->initializePayment($paymentData);

            if ($result['status'] === 'success') {
                // Ensure a payment record exists for this booking
                $payment = $booking->payment;
                if (!$payment) {
                    $payment = Payment::create([
                        'booking_id' => $booking->id,
                        'customer_id' => $user->id,
                        'provider_id' => $booking->provider_id,
                        'amount' => $booking->amount,
                        'commission' => $booking->commission,
                        'provider_amount' => $booking->amount - $booking->commission,
                        'gateway' => $gateway,
                        'gateway_reference' => $paymentData['reference'],
                        'status' => 'processing',
                    ]);
                } else {
                    // Update payment record with gateway reference
                    $payment->update([
                        'gateway' => $gateway,
                        'gateway_reference' => $paymentData['reference'],
                        'status' => 'processing',
                    ]);
                }
                // Redirect to payment gateway
                return redirect($result['authorization_url']);
            }

            return back()->withErrors(['error' => $result['message']]);

        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Payment initialization failed. Please try again.']);
        }
    }

    /**
     * Handle payment callback from gateways
     */
    public function callback(Request $request, $gateway)
    {
        try {
            if ($gateway === 'paystack') {
                return $this->handlePaystackCallback($request);
            }

            return redirect()->route('dashboard')->withErrors(['error' => 'Invalid payment gateway.']);

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('dashboard')->withErrors(['error' => 'Payment verification failed.']);
        }
    }

    /**
     * Handle Paystack payment callback
     */
    private function handlePaystackCallback(Request $request)
    {
        $reference = $request->query('reference');
        
        if (!$reference) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Payment reference not found.']);
        }

        // Find payment by reference
        $payment = Payment::where('gateway_reference', $reference)->first();
        
        if (!$payment) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Payment record not found.']);
        }

        // Verify payment with Paystack
        $result = $this->paystackService->verifyPayment($reference);

        return $this->processPaymentResult($payment, $result);
    }

    // (Payment gateway support limited to Paystack)

    /**
     * Process payment verification result
     */
    private function processPaymentResult(Payment $payment, $result)
    {
        DB::beginTransaction();
        
        try {
            if ($result['status'] === 'success') {
                // Payment successful
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'gateway_response' => $result['gateway_response'],
                ]);

                // Update booking status
                $payment->booking->update(['status' => 'confirmed']);

                DB::commit();

                return redirect()->route('bookings.show', $payment->booking)
                    ->with('success', 'Payment completed successfully! Your booking has been confirmed.');

            } else {
                // Payment failed
                $payment->update([
                    'status' => 'failed',
                    'gateway_response' => $result['data'] ?? null,
                ]);

                DB::commit();

                return redirect()->route('bookings.payment', $payment->booking)
                    ->withErrors(['error' => $result['message'] ?? 'Payment verification failed.']);
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment processing error: ' . $e->getMessage());
            
            return redirect()->route('bookings.payment', $payment->booking)
                ->withErrors(['error' => 'Payment processing failed. Please contact support.']);
        }
    }

    /**
     * Handle webhook notifications from payment gateways
     */
    public function webhook(Request $request, $gateway)
    {
        try {
            if ($gateway === 'paystack') {
                return $this->handlePaystackWebhook($request);
            }

            return response()->json(['status' => 'error', 'message' => 'Invalid gateway'], 400);

        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle Paystack webhook
     */
    private function handlePaystackWebhook(Request $request)
    {
        // Verify webhook signature
        $signature = $request->header('x-paystack-signature');
        $body = $request->getContent();
        
        if (!hash_equals($signature, hash_hmac('sha512', $body, config('services.paystack.secret_key')))) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $event = $request->json()->all();
        
        if ($event['event'] === 'charge.success') {
            $reference = $event['data']['reference'];
            $payment = Payment::where('gateway_reference', $reference)->first();
            
            if ($payment && $payment->status !== 'completed') {
                $result = $this->paystackService->verifyPayment($reference);
                $this->processPaymentResult($payment, $result);
            }
        }

        return response()->json(['status' => 'success']);
    }

    // (Webhook handling is Paystack-only)

    /**
     * Initiate refund for a payment
     */
    public function refund(Request $request, Payment $payment)
    {
        // Only admin or the customer can initiate refund
        if (!auth()->user()->isAdmin() && $payment->customer_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status !== 'completed') {
            return back()->withErrors(['error' => 'Only completed payments can be refunded.']);
        }

        try {
            // Only Paystack supported
            $result = $this->paystackService->refundPayment($payment->gateway_reference);

            if ($result['status'] === 'success') {
                $payment->update(['status' => 'refunded']);
                $payment->booking->update(['status' => 'refunded']);
                
                return back()->with('success', 'Refund initiated successfully.');
            }

            return back()->withErrors(['error' => $result['message']]);

        } catch (\Exception $e) {
            Log::error('Refund error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Refund processing failed.']);
        }
    }

    /**
     * Generate payment reference
     */
    private function generatePaymentReference($gateway)
    {
        // Only Paystack in use
        return 'PAY_' . time() . '_' . strtoupper(substr(md5(uniqid()), 0, 8));
    }
}
