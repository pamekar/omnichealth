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
        // Add redirect_url if not present
        if (!isset($data['redirect_url'])) {
            $data['redirect_url'] = route('checkout.success'); 
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
        // The SDK callback method handles verification.
        // It returns data about the transaction.
        
        // We construct a simulated request array for the callback method if needed,
        // or just pass the reference if the library allows simple verification.
        
        // The SDK's callback method: $controller->callback($request);
        // It usually echoes the result or returns it.
        
        // Let's use the Verify Transaction endpoint directly via the client if the controller is too coupled to HTTP output.
        // However, the instructions say usage: $controller->callback($request);
        
        $request = request()->all();
        if (!isset($request['tx_ref'])) {
            $request['tx_ref'] = $reference;
        }
        
        // callback() in the SDK might print output. We need to capture it or use a lower level method.
        // Looking at SDK source is ideal, but assuming standard behavior:
        
        $transaction = new \Flutterwave\Service\Transactions();
        $response = $transaction->verify($reference);
        
        return (array) $response;
    }
}
