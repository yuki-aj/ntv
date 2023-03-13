<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function show($user_id, $product_id, $price_id)
    {
        $user = User::findOrFail($user_id);

        // 対象商品をstripeから取得
        $stripe = new \Stripe\StripeClient(config('app.stripe_secret'));
        $product = $stripe->products->retrieve(
            $product_id,
            [],
            ['stripe_account' => $user->stripe_user_id],
        );

        // 対象料金をstripeから取得
        $price = $stripe->prices->retrieve(
            $price_id,
            [],
            ['stripe_account' => $user->stripe_user_id],
        );

        // 税率を取得
        $tax_rates = $stripe->taxRates->all(
            ['active' => true],
            ['stripe_account' => $user->stripe_user_id],
        );

        // Checkoutセッションを作成
        $session = $stripe->checkout->sessions->create([
            'success_url' => route('payment.success', [
                'user_id' => $user_id, 'product_id' => $product_id, 'price_id' => $price_id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.show', [
                'user_id' => $user_id, 'product_id' => $product_id, 'price_id' => $price_id]),
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $price_id,
                    'quantity' => 1,
                    'tax_rates' => [$tax_rate->id],
                ],
            ],
            'payment_intent_data' => [
                'application_fee_amount' => 100,
            ],
            'mode' => 'payment',
            'allow_promotion_codes' => true,
        ], ['stripe_account' => $user->stripe_user_id]);

        return view('payment.show', [
            'user' => $user,
            'product' => $product,
            'price' => $price,
            'session' => $session,
        ]);
    }

    /**
     * 決済完了ページを表示する
     * @return \Illuminate\View\View
     */
    public function success()
    {
        return view('payment.success');
    }
    public function webhook(Request $request)
{
    Stripe::setApiKey(config('app.stripe_secret'));

    $endpoint_secret = config('app.stripe_endpoint_secret');

    $payload = $request->getContent();
    $sig_header = $request->header('stripe-signature');

    $event = null;
    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
    } catch(\UnexpectedValueException $e) {
        // Invalid payload.
        return response()->json('Invalid payload', 400);
    } catch(\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid Signature.
        return response()->json('Invalid signature', 400);
    }

    if ($event->type == 'checkout.session.completed') {
        $connectedAccountId = $event->account;
        $session = $event->data->object;
        $this->handleCompletedCheckoutSession($connectedAccountId, $session);
    }

    return response()->json('ok', 200);
}

private function handleCompletedCheckoutSession($connectedAccountId, $session) {
    logger('Completed', ['Connected account ID' => $connectedAccountId]);
    logger($session);
}
}