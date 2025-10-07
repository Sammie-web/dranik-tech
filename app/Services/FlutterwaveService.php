<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    private $client;
    private $secretKey;
    private $publicKey;
    private $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->publicKey = config('services.flutterwave.public_key');
        $this->baseUrl = 'https://api.flutterwave.com/v3';
    }

    /**
     * Initialize a payment transaction
     */
    public function initializePayment($data)
    {
        try {
            $response = $this->client->post($this->baseUrl . '/payments', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'tx_ref' => $data['reference'],
                    'amount' => $data['amount'],
                    'currency' => 'NGN',
                    'redirect_url' => $data['callback_url'],
                    'payment_options' => 'card,banktransfer,ussd,mobilemoney',
                    'customer' => [
                        'email' => $data['email'],
                        'name' => $data['customer_name'] ?? '',
                        'phonenumber' => $data['customer_phone'] ?? '',
                    ],
                    'customizations' => [
                        'title' => 'D\'RANiK Techs Service Payment',
                        'description' => $data['service_title'] ?? 'Service booking payment',
                        'logo' => config('app.url') . '/images/logo.png',
                    ],
                    'meta' => [
                        'booking_id' => $data['booking_id'],
                        'customer_id' => $data['customer_id'],
                        'provider_id' => $data['provider_id'],
                    ],
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] === 'success') {
                return [
                    'status' => 'success',
                    'data' => $body['data'],
                    'authorization_url' => $body['data']['link'],
                    'reference' => $data['reference'],
                ];
            }

            return [
                'status' => 'error',
                'message' => $body['message'] ?? 'Payment initialization failed',
            ];

        } catch (RequestException $e) {
            Log::error('Flutterwave initialization error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Payment service unavailable. Please try again.',
            ];
        }
    }

    /**
     * Verify a payment transaction
     */
    public function verifyPayment($transactionId)
    {
        try {
            $response = $this->client->get($this->baseUrl . '/transactions/' . $transactionId . '/verify', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] === 'success' && $body['data']['status'] === 'successful') {
                return [
                    'status' => 'success',
                    'data' => $body['data'],
                    'amount' => $body['data']['amount'],
                    'reference' => $body['data']['tx_ref'],
                    'transaction_id' => $body['data']['id'],
                    'paid_at' => $body['data']['created_at'],
                    'gateway_response' => $body['data'],
                ];
            }

            return [
                'status' => 'failed',
                'message' => $body['data']['processor_response'] ?? 'Payment verification failed',
                'data' => $body['data'] ?? null,
            ];

        } catch (RequestException $e) {
            Log::error('Flutterwave verification error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Payment verification failed. Please contact support.',
            ];
        }
    }

    /**
     * Get transaction details by reference
     */
    public function getTransactionByReference($reference)
    {
        try {
            $response = $this->client->get($this->baseUrl . '/transactions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                ],
                'query' => [
                    'tx_ref' => $reference,
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] === 'success' && !empty($body['data'])) {
                return [
                    'status' => 'success',
                    'data' => $body['data'][0], // Get first transaction
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Transaction not found',
            ];

        } catch (RequestException $e) {
            Log::error('Flutterwave transaction fetch error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Unable to fetch transaction details.',
            ];
        }
    }

    /**
     * Initiate a refund
     */
    public function refundPayment($transactionId, $amount = null)
    {
        try {
            $data = ['id' => $transactionId];
            if ($amount) {
                $data['amount'] = $amount;
            }

            $response = $this->client->post($this->baseUrl . '/transactions/' . $transactionId . '/refund', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] === 'success') {
                return [
                    'status' => 'success',
                    'data' => $body['data'],
                    'message' => 'Refund initiated successfully',
                ];
            }

            return [
                'status' => 'error',
                'message' => $body['message'] ?? 'Refund initiation failed',
            ];

        } catch (RequestException $e) {
            Log::error('Flutterwave refund error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Refund processing failed. Please contact support.',
            ];
        }
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Generate payment reference
     */
    public static function generateReference($prefix = 'FLW')
    {
        return $prefix . '_' . time() . '_' . strtoupper(substr(md5(uniqid()), 0, 8));
    }
}

