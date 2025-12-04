<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentGatewayInterface;
use Exception;

class PaymentFactory
{
    /**
     * Get the payment gateway instance.
     *
     * @param string|null $driver
     * @return PaymentGatewayInterface
     * @throws Exception
     */
    public function driver($driver = null): PaymentGatewayInterface
    {
        $driver = $driver ?? config('services.payment.default', 'paystack');

        switch ($driver) {
            case 'paystack':
                return new PaystackGateway();
            case 'flutterwave':
                return new FlutterwaveGateway();
            default:
                throw new Exception("Payment driver [$driver] not supported.");
        }
    }
}
