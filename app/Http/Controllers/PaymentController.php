<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaystackService;
use App\Services\FlutterwaveService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paystackService;
    protected $flutterwaveService;

    public function __construct(PaystackService $paystackService, FlutterwaveService $flutterwaveService)
    {
        $this->middleware('auth');
        $this->paystackService = $paystackService;
        $this->flutterwaveService = $flutterwaveService;
    }

    /**
     * Initialize payment for a booking
     */
    public function initialize(Request $request, Booking $booking)
    {
        // Ensure user can only pay for their own booking
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        // Check if payment is already completed
        if ($booking->payment && $booking->payment->status === 'completed') {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'Payment has already been completed for this booking.');
        }

        $request->validate([
            'gateway' => 'required|in:paystack,flutterwave',
        ]);

        $gateway = $request->gateway;
        $user = auth()->user();

        // Prepare payment data
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
            if ($gateway === 'paystack') {
                $result = $this->paystackService->initializePayment($paymentData);
            } else {
                $result = $this->flutterwaveService->initializePayment($paymentData);
            }

            if ($result['status'] === 'success') {
                // Update payment record with gateway reference
                $booking->payment->update([
                    'gateway' => $gateway,
                    'gateway_reference' => $paymentData['reference'],
                    'status' => 'processing',
                ]);

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
            } elseif ($gateway === 'flutterwave') {
                return $this->handleFlutterwaveCallback($request);
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

    /**
     * Handle Flutterwave payment callback
     */
    private function handleFlutterwaveCallback(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        $reference = $request->query('tx_ref');
        
        if (!$transactionId || !$reference) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Payment details not found.']);
        }

        // Find payment by reference
        $payment = Payment::where('gateway_reference', $reference)->first();
        
        if (!$payment) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Payment record not found.']);
        }

        // Verify payment with Flutterwave
        $result = $this->flutterwaveService->verifyPayment($transactionId);

        return $this->processPaymentResult($payment, $result);
    }

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
            } elseif ($gateway === 'flutterwave') {
                return $this->handleFlutterwaveWebhook($request);
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

    /**
     * Handle Flutterwave webhook
     */
    private function handleFlutterwaveWebhook(Request $request)
    {
        // Verify webhook signature
        $signature = $request->header('verif-hash');
        
        if (!hash_equals($signature, config('services.flutterwave.secret_key'))) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $event = $request->json()->all();
        
        if ($event['event'] === 'charge.completed') {
            $reference = $event['data']['tx_ref'];
            $payment = Payment::where('gateway_reference', $reference)->first();
            
            if ($payment && $payment->status !== 'completed') {
                $result = $this->flutterwaveService->verifyPayment($event['data']['id']);
                $this->processPaymentResult($payment, $result);
            }
        }

        return response()->json(['status' => 'success']);
    }

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
            if ($payment->gateway === 'paystack') {
                $result = $this->paystackService->refundPayment($payment->gateway_reference);
            } else {
                $result = $this->flutterwaveService->refundPayment($payment->gateway_reference);
            }

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
        $prefix = $gateway === 'paystack' ? 'PAY' : 'FLW';
        return $prefix . '_' . time() . '_' . strtoupper(substr(md5(uniqid()), 0, 8));
    }
}
