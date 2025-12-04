<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentGatewayInterface;
use Binkode\Paystack\Facades\Paystack;
use Illuminate\Support\Facades\Redirect;

class PaystackGateway implements PaymentGatewayInterface
{
    /**
     * Initialize the payment with Paystack.
     *
     * @param array $data
     * @return mixed
     */
    public function initialize(array $data)
    {
        // Paystack facade handles the request data internally or via params
        // For Binkode\Paystack, we usually pass data to getAuthorizationUrl or let it read from request
        // But to be clean, we should pass the data explicitly if the library supports it,
        // or ensure the request has the data.
        // Based on the controller code: Paystack::getAuthorizationUrl($data)->redirectNow();

        try {
            return Paystack::getAuthorizationUrl($data)->redirectNow();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify the payment with Paystack.
     *
     * @param string $reference
     * @return array
     */
    public function verify(string $reference)
    {
        // Paystack facade usually gets reference from request or we can pass it if supported.
        // Binkode\Paystack\Facades\Paystack::getPaymentData() reads from request()->query('reference') usually.
        
        // We will assume the controller calls this when the reference is present in the request.
        return Paystack::getPaymentData();
    }
}
