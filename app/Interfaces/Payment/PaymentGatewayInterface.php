<?php

namespace App\Interfaces\Payment;

interface PaymentGatewayInterface
{
    /**
     * Initialize the payment and return the authorization URL or data.
     *
     * @param array $data
     * @return mixed
     */
    public function initialize(array $data);

    /**
     * Verify the payment using the reference.
     *
     * @param string $reference
     * @return mixed
     */
    public function verify(string $reference);
}
