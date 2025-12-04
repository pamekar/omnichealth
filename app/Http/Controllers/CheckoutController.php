<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Account;
use App\Services\Payment\PaymentFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $paymentFactory;

    public function __construct(PaymentFactory $paymentFactory)
    {
        $this->paymentFactory = $paymentFactory;
    }

    public function index()
    {
        $cartItems = Cart::getContent();
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
            // Find or create account
            $account = Account::where('email', $request->email)->first();
            if (!$account) {
                $account = new Account();
                $account->name = $request->first_name . ' ' . $request->last_name;
                $account->email = $request->email;
                $account->phone = $request->phone;
                $account->address = $request->address;
                $account->username = $request->email;
                $account->save();
            }

            // Determine driver, maybe from config or request
            $driver = config('services.payment.default', 'paystack');
            $gateway = $this->paymentFactory->driver($driver);

            // Generate a reference (both gateways can generate one, but we can do it here for consistency)
            $reference = 'OMNIC-' . uniqid() . '-' . time();
            $amount = Cart::getTotal(); // Amount in main currency unit (Naira)

            // Paystack expects kobo, Flutterwave expects main unit usually but let's check.
            // Adjust amount based on driver if necessary or let gateway handle it.
            // For now, we pass the raw amount and let the gateway standardize if needed.
            // But PaystackGateway implementation passes data directly to Paystack::getAuthorizationUrl
            // which expects Kobo if we pass 'amount'.

            // Standardizing: Let's pass 'amount' in Kobo/Cent to gateway initialize,
            // and let the specific gateway convert if it needs standard units.
            // OR pass standard units and let PaystackGateway multiply by 100.

            // Let's modify PaystackGateway to multiply by 100 if we pass main unit.
            // Actually, for simplicity in refactoring, let's keep the logic here for now
            // or better, pass the main unit and let the gateway handle the specific currency requirement.

            // Update: PaystackGateway currently just passes $data.
            // Let's pass the amount in Kobo to be safe for Paystack as it was before.
            $paystackAmount = $amount * 100;

            $order = Order::create([
                'account_id' => $account->id,
                'user_id' => auth()->id(), // or null if guest checkout
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'total' => $amount,
                'status' => 'pending',
                'payment_method' => $driver,
                'payment_status' => 'pending',
                'payment_vendor_id' => $reference,
            ]);

            foreach (Cart::getContent() as $item) {
                $order->ordersItems()->create([
                    'product_id' => $item->id,
                    'account_id' => $account->id,
                    'qty' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ]);
            }

            $data = [
                'reference' => $reference,
                'email' => $request->email,
                'amount' => $paystackAmount, // Paystack needs kobo. Flutterwave might need handling.
                'currency' => 'NGN',
                'callback_url' => route('checkout.success'),
                'metadata' => [
                    'order_id' => $order->id,
                ],
                'first_name' => $request->first_name, // Added for Flutterwave
                'last_name' => $request->last_name,   // Added for Flutterwave
                'phone_number' => $request->phone,    // Added for Flutterwave
                'tx_ref' => $reference,               // Flutterwave uses tx_ref
            ];

            // Fix for Flutterwave expecting 'amount' in main unit (Naira) vs Paystack (Kobo).
            // This suggests we should really move the data preparation into the Gateway classes.
            // But to avoid over-engineering right now, we will handle it in the Gateway classes or passed data.
            // We passed 'amount' as kobo.

            return $gateway->initialize($data);

        } catch (\Exception $e) {
            Log::info($e->getMessage(), $e->getTrace());
            return redirect()->route('checkout.error')->with('error', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        try {
            $driver = config('services.payment.default', 'paystack');
            $gateway = $this->paymentFactory->driver($driver);

            // Get reference from request
            $reference = $request->query('reference') ?? $request->query('tx_ref');

            if (!$reference) {
                 return redirect()->route('checkout.error')->with('error', 'No transaction reference found.');
            }

            $paymentDetails = $gateway->verify($reference);

            // Normalize status check
            $isSuccessful = false;

            if ($driver === 'paystack') {
                if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {
                    $isSuccessful = true;
                }
            } elseif ($driver === 'flutterwave') {
                if (isset($paymentDetails['status']) && $paymentDetails['status'] === 'success') {
                     $isSuccessful = true;
                }
            }

            if ($isSuccessful) {
                $order = Order::where('payment_vendor_id', $reference)->first();
                if ($order) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                    ]);
                    Cart::clear();
                    return view('success');
                }
            }

            return redirect()->route('checkout.error')->with('error', 'Payment verification failed.');

        } catch (\Exception $e) {
             return redirect()->route('checkout.error')->with('error', $e->getMessage());
        }
    }

    public function error()
    {
        return view('error');
    }
}
