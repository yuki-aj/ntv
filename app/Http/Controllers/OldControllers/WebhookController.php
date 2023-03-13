<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    // public function handlePaymentCreated()
    public function HandleWebhook()
    {
       Log::debug($event['data']['object']);
    }
}