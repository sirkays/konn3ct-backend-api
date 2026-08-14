<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe Secret Key
    |--------------------------------------------------------------------------
    | Used to authenticate server-side Stripe API calls. Never expose this
    | to the frontend. Set STRIPE_SECRET in your .env file.
    */
    'secret' => env('STRIPE_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Stripe Webhook Signing Secret
    |--------------------------------------------------------------------------
    | Used to verify the Stripe-Signature header on incoming webhooks.
    | Obtain from the Stripe Dashboard → Webhooks → Signing secret.
    */
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Checkout Redirect URLs
    |--------------------------------------------------------------------------
    | These URLs are passed to Stripe Checkout Session as success/cancel URLs.
    */
    'checkout_success_url' => env('STRIPE_CHECKOUT_SUCCESS_URL', ''),
    'checkout_cancel_url'  => env('STRIPE_CHECKOUT_CANCEL_URL', ''),

];
