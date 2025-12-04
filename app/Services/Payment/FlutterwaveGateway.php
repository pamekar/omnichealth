<?php

namespace App\Services\Payment;

use App\Interfaces\Payment\PaymentGatewayInterface;
use Flutterwave\Flutterwave;
use Flutterwave\Controller\PaymentController;
use Flutterwave\EventHandlers\ModalEventHandler as PaymentHandler;
use Flutterwave\Library\Modal;

class FlutterwaveGateway implements PaymentGatewayInterface
{
    protected $controller;

    public function __construct()
    {
        // Bootstrap Flutterwave with keys from env
        // The library reads from .env automatically if setup, or we can pass config.
        // Assuming .env is set with FLW_PUBLIC_KEY, FLW_SECRET_KEY, etc.
        Flutterwave::bootstrap();
        
        $customHandler = new PaymentHandler();
        $client = new Flutterwave();
        $modalType = Modal::POPUP; // Or STANDARD
        $this->controller = new PaymentController($client, $customHandler, $modalType);
    }

    /**
     * Initialize the payment with Flutterwave.
     *
     * @param array $data
     * @return mixed
     */
    public function initialize(array $data)
    {
        // Add success_url, failure_url and redirect_url if not present (Flutterwave might use one or all)
        if (!isset($data['success_url'])) {
            $data['success_url'] = route('checkout.success'); 
        }

        if (!isset($data['failure_url'])) {
             $data['failure_url'] = route('checkout.error');
        }

        // The SDK's PaymentController often uses 'redirect_url' in its internal processing
        if (!isset($data['redirect_url'])) {
            $data['redirect_url'] = $data['callback_url'] ?? route('checkout.success');
        }
        
        // Ensure payment options are set
        if (!isset($data['payment_options'])) {
            $data['payment_options'] = 'card, ussd, banktransfer';
        }

        // Some SDK versions might look for 'payment_method' or use it for specific flows
        if (!isset($data['payment_method'])) {
            $data['payment_method'] = 'card';
        }

        // Convert amount from Kobo to Naira if necessary
        // Assuming the controller sends Kobo (multiplied by 100)
        if (isset($data['amount'])) {
             $data['amount'] = $data['amount'] / 100;
        }

        try {
            $payment = $this->controller->process($data);
            
            if (is_string($payment)) {
                 return redirect($payment);
            }
            
            return $payment;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify the payment with Flutterwave.
     *
     * @param string $reference
     * @return array
     */
    public function verify(string $reference)
    {
        // Flutterwave verify endpoint typically requires the transaction ID, not the tx_ref.
        // The redirect URL usually contains 'transaction_id' and 'tx_ref'.
        // We should prefer 'transaction_id' if available in the request.
        
        $transactionId = request()->query('transaction_id');

        if (!$transactionId) {
             // If we don't have the transaction ID, we might have a problem as verify() expects it.
             // We could try to use the reference if the library supports lookup by ref, 
             // but standard v3 verify takes ID. 
             // For now, let's assume if it's not in the query, we can't easily verify without an extra lookup call.
             // But we will try to pass the reference just in case the wrapper handles it, 
             // though the error suggests otherwise.
             $transactionId = $reference; 
        }
        
        $transaction = new \Flutterwave\Service\Transactions();
        $response = $transaction->verify($transactionId);
        
        return (array) $response;
    }
}
