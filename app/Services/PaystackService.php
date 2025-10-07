<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    private $client;
    private $secretKey;
    private $publicKey;
    private $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->secretKey = config('services.paystack.secret_key');
        $this->publicKey = config('services.paystack.public_key');
        $this->baseUrl = 'https://api.paystack.co';
    }

    /**
     * Initialize a payment transaction
     */
    public function initializePayment($data)
    {
        try {
            $response = $this->client->post($this->baseUrl . '/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'email' => $data['email'],
                    'amount' => $data['amount'] * 100, // Convert to kobo
                    'reference' => $data['reference'],
                    'callback_url' => $data['callback_url'],
                    'metadata' => [
                        'booking_id' => $data['booking_id'],
                        'customer_id' => $data['customer_id'],
                        'provider_id' => $data['provider_id'],
                        'service_title' => $data['service_title'] ?? '',
                    ],
                    'channels' => ['card', 'bank', 'ussd', 'qr', 'mobile_money', 'bank_transfer'],
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] === true) {
                return [
                    'status' => 'success',
                    'data' => $body['data'],
                    'authorization_url' => $body['data']['authorization_url'],
                    'access_code' => $body['data']['access_code'],
                    'reference' => $body['data']['reference'],
                ];
            }

            return [
                'status' => 'error',
                'message' => $body['message'] ?? 'Payment initialization failed',
            ];

        } catch (RequestException $e) {
            Log::error('Paystack initialization error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Payment service unavailable. Please try again.',
            ];
        }
    }

    /**
     * Verify a payment transaction
     */
    public function verifyPayment($reference)
    {
        try {
            $response = $this->client->get($this->baseUrl . '/transaction/verify/' . $reference, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] === true && $body['data']['status'] === 'success') {
                return [
                    'status' => 'success',
                    'data' => $body['data'],
                    'amount' => $body['data']['amount'] / 100, // Convert from kobo
                    'reference' => $body['data']['reference'],
                    'paid_at' => $body['data']['paid_at'],
                    'gateway_response' => $body['data'],
                ];
            }

            return [
                'status' => 'failed',
                'message' => $body['data']['gateway_response'] ?? 'Payment verification failed',
                'data' => $body['data'] ?? null,
            ];

        } catch (RequestException $e) {
            Log::error('Paystack verification error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Payment verification failed. Please contact support.',
            ];
        }
    }

    /**
     * Get transaction details
     */
    public function getTransaction($reference)
    {
        try {
            $response = $this->client->get($this->baseUrl . '/transaction/' . $reference, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] === true) {
                return [
                    'status' => 'success',
                    'data' => $body['data'],
                ];
            }

            return [
                'status' => 'error',
                'message' => $body['message'] ?? 'Transaction not found',
            ];

        } catch (RequestException $e) {
            Log::error('Paystack transaction fetch error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Unable to fetch transaction details.',
            ];
        }
    }

    /**
     * Initiate a refund
     */
    public function refundPayment($reference, $amount = null)
    {
        try {
            $data = ['transaction' => $reference];
            if ($amount) {
                $data['amount'] = $amount * 100; // Convert to kobo
            }

            $response = $this->client->post($this->baseUrl . '/refund', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['status'] === true) {
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
            Log::error('Paystack refund error: ' . $e->getMessage());
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
    public static function generateReference($prefix = 'PAY')
    {
        return $prefix . '_' . time() . '_' . strtoupper(substr(md5(uniqid()), 0, 8));
    }
}

