<?php

require_once '../vendor/autoload.php';
require_once 'secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost:';

$checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
        'price' => $actualPriceId,  // Replace with actual price ID
        'quantity' => 1,
    ]],
    'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/success.html',
  'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);