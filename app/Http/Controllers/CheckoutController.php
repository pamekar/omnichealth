<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Binkode\Paystack\Facades\Paystack;
use TomatoPHP\TomatoEcommerce\Models\Order;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = \Cart::getContent();
        return view('checkout', compact('cartItems'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        try {
            $reference = Paystack::genTranxRef();
            $amount = \Cart::getTotal() * 100; // Paystack amount is in kobo

            $order = Order::create([
                'user_id' => auth()->id(), // or null if guest checkout
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'total' => \Cart::getTotal(),
                'status' => 'pending',
                'payment_method' => 'paystack',
                'payment_status' => 'pending',
                'transaction_id' => $reference,
            ]);

            foreach (\Cart::getContent() as $item) {
                $order->items()->create([
                    'product_id' => $item->id,
                    'qty' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ]);
            }

            $data = [
                'reference' => $reference,
                'email' => $request->email,
                'amount' => $amount,
                'currency' => 'NGN',
                'callback_url' => route('checkout.success'),
                'metadata' => [
                    'order_id' => $order->id,
                ]
            ];

            return Paystack::getAuthorizationUrl($data)->redirectNow();
        } catch (\Exception $e) {
            return redirect()->route('checkout.error')->with('error', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $paymentDetails = Paystack::getPaymentData();

        if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {
            $order = Order::where('transaction_id', $paymentDetails['data']['reference'])->first();
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);
                \Cart::clear();
                return view('success');
            }
        }

        return redirect()->route('checkout.error')->with('error', 'Payment verification failed.');
    }

    public function error()
    {
        return view('error');
    }
}
