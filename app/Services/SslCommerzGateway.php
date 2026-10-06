<?php

namespace App\Services;

use App\Core\Config;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Exception;

/**
 * Talks to SSLCommerz. Only HTTP calls live here — no database work.
 *
 * Docs: https://developer.sslcommerz.com/doc/v4/
 */
class SslCommerzGateway
{
    private string $storeId;
    private string $storePassword;
    private string $baseUrl;
    private bool $verifySsl;

    public function __construct()
    {
        $this->storeId = Config::get('sslcommerz.store_id');
        $this->storePassword = Config::get('sslcommerz.store_password');
        $this->verifySsl = (bool) Config::get('sslcommerz.verify_ssl', true);
        $this->baseUrl = Config::get('sslcommerz.sandbox', true)
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';
    }

    /**
     * Step 1: create a payment session. Returns the SSLCommerz page URL
     * where the customer must be redirected.
     */
    public function createSession(Payment $payment, Booking $booking, User $customer): string
    {
        $response = $this->request('POST', '/gwprocess/v4/api.php', [
            'store_id'         => $this->storeId,
            'store_passwd'     => $this->storePassword,
            'total_amount'     => number_format($payment->amount, 2, '.', ''),
            'currency'         => $payment->currency,
            'tran_id'          => $payment->tran_id,
            'success_url'      => url('/payment/success'),
            'fail_url'         => url('/payment/fail'),
            'cancel_url'       => url('/payment/cancel'),
            'ipn_url'          => url('/payment/ipn'),

            'cus_name'         => $customer->name,
            'cus_email'        => $customer->email,
            'cus_phone'        => $booking->contact_phone,
            'cus_add1'         => $booking->pickup_address,
            'cus_city'         => 'Dhaka',
            'cus_country'      => 'Bangladesh',

            'shipping_method'  => 'NO',
            'num_of_item'      => 1,
            'product_name'     => 'Truck booking ' . $booking->booking_no,
            'product_category' => 'Truck Booking',
            'product_profile'  => 'non-physical-goods',
            'value_a'          => $booking->booking_no,
        ]);

        if (($response['status'] ?? '') !== 'SUCCESS' || empty($response['GatewayPageURL'])) {
            throw new Exception('SSLCommerz session failed: ' . ($response['failedreason'] ?? 'unknown error'));
        }

        return $response['GatewayPageURL'];
    }

    /**
     * Step 2: ask SSLCommerz if a payment (val_id) is really valid.
     * Never trust the browser POST alone.
     */
    public function validate(string $valId): array
    {
        return $this->request('GET', '/validator/api/validationserverAPI.php', [
            'val_id'       => $valId,
            'store_id'     => $this->storeId,
            'store_passwd' => $this->storePassword,
            'format'       => 'json',
        ]);
    }

    private function request(string $method, string $path, array $params): array
    {
        $url = $this->baseUrl . $path;
        $curl = curl_init();

        if ($method === 'GET') {
            $url .= '?' . http_build_query($params);
        } else {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->verifySsl);

        $body = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($body === false || $status !== 200) {
            throw new Exception("Could not connect to SSLCommerz (HTTP $status) $curlError");
        }

        $data = json_decode($body, true);
        if (!is_array($data)) {
            throw new Exception('SSLCommerz returned an invalid response.');
        }
        return $data;
    }
}
