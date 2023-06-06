<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Error;
use Illuminate\Http\Request;
use Stripe\Customer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('product.index', compact('products'));
    }

    public function checkout()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $lineItems= [];
        $products = Product::all();
        $totalPrice = 0;
        foreach($products as $product) {
            $totalPrice += $product->price;
            $lineItems[] = [
                'price_data' => [
                  'currency' => 'usd',
                  'product_data' => [
                    'name' => $product->name,
                    'images' => [$product->image]
                    ],
                  'unit_amount' => $product->price * 100,
                ],
                'quantity' => 1,
            ];
        }

        $checkout_session =  $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' =>  route('checkout.success')."?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('checkout.success'),
            'customer_creation' => 'if_required'
        ]);

        $order = new Order();
        $order->status = 'unpaid';
        $order->total_price = $totalPrice;
        $order->session_id = $checkout_session->id;
        $order->save();

        return redirect($checkout_session->url);
    }

    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $sessionId = $request->get('session_id');
        try {
            $session = $stripe->checkout->sessions->retrieve($sessionId);
            if(!$session) throw new NotFoundHttpException();

            $customer = ($session->customer) ? $stripe->customers->retrieve($session->customer) : $session->customer_details;

            $order = Order::where('session_id', $sessionId)->first();
            if(!$order) throw new NotFoundHttpException();

            if($order && $order->status === 'unpaid') {
                $order->status='paid';
                $order->save();
            }

            return view('product.success', compact('customer'));
        } catch(Error $error) {
            return $error;
        }

    }

    public function webhook()
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
            return response('', 400);
        exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;
                $sessionId = $paymentIntent->id;

                $order = Order::where('session_id', $sessionId)->first();
                if($order && $order->status === 'unpaid') {
                    $order->status='paid';
                    $order->save();
                }
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }

    public function cancel(Request $request)
    {
        $sessionId = $request->get('session_id');
        $order = Order::where('session_id', $sessionId)->first();

        return $order;
    }
}
