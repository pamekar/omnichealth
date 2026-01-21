<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Account;
use App\Models\Order;
use App\Services\Payment\PaymentFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteRequested;

class CheckoutComponent extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;

    public function mount()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $account = Account::where('email', $user->email)->first();

            $this->email = $user->email;

            // Try to split name if available
            $names = explode(' ', $user->name, 2);
            $this->first_name = $names[0] ?? '';
            $this->last_name = $names[1] ?? '';

            if ($account) {
                $this->phone = $account->phone;
                $this->address = $account->address;

                // Override names if account has them more accurately?
                // Let's stick to user name for now or account name if user name is empty
            }
        }
    }

    public function render()
    {
        $cartItems = Cart::getContent();
        return view('livewire.checkout-component', compact('cartItems'))
            ->extends('layouts.app')
            ->section('content')
            ->title('Checkout - OMNIC Medical Store');
    }

    public function process(PaymentFactory $paymentFactory)
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        try {
            // Find or create account
            $account = Account::where('email', $this->email)->first();
            if (!$account) {
                $account = new Account();
                $account->name = $this->first_name . ' ' . $this->last_name;
                $account->email = $this->email;
                $account->phone = $this->phone;
                $account->address = $this->address;
                $account->username = $this->email;
                $account->save();
            }

            // Determine driver
            $driver = config('services.payment.default', 'paystack');
            $gateway = $paymentFactory->driver($driver);

            $reference = 'OMNIC-' . uniqid() . '-' . time();
            $amount = Cart::getTotal();

            // Paystack expects kobo
            $paystackAmount = $amount * 100;

            $order = Order::create([
                'account_id' => $account->id,
                'company_id' => config('filament-ecommerce.default_company_id', 1),
                'user_id' => auth()->id(),
                'name' => $this->first_name . ' ' . $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'total' => $amount,
                'status' => 'pending',
                'payment_method' => config('filament-ecommerce.enable_pricing') ? $driver : 'quote',
                'payment_status' => 'pending',
                'transaction_id' => $reference,
            ]);

            foreach (Cart::getContent() as $item) {
                $order->ordersItems()->create([
                    'product_id' => $item->id,
                    'account_id' => $account->id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'total' => $item->price * $item->qty,
                ]);
            }

            if (!config('filament-ecommerce.enable_pricing')) {
                Cart::clear();

                $storeEmail = config('filament-ecommerce.store_email');
                if ($storeEmail) {
                    try {
                        Mail::to($storeEmail)->send(new QuoteRequested($order));
                    } catch (\Exception $e) {
                        Log::error('Failed to send quote request email: ' . $e->getMessage());
                    }
                }

                return $this->redirectRoute('checkout.success', ['reference' => $reference], navigate: true);
            }

            $data = [
                'reference' => $reference,
                'email' => $this->email,
                'amount' => $paystackAmount,
                'currency' => 'NGN',
                'callback_url' => route('checkout.success'),
                'metadata' => [
                    'order_id' => $order->id,
                ],
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone_number' => $this->phone,
                'tx_ref' => $reference,
            ];

            // Livewire action usually expects a redirect return if we want to change page
            // The gateway initialize usually returns a redirect or response object.
            // If it returns a RedirectResponse, returning it here works in Livewire.

            return $gateway->initialize($data);

        } catch (\Exception $e) {
            Log::info($e->getMessage(), $e->getTrace());
            session()->flash('error', $e->getMessage());
            return redirect()->route('checkout.error');
        }
    }
}
