<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Encryption\Encrypter;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    public function __construct(Application $app, Encrypter $encrypter) {
        parent::__construct($app, $encrypter);
        $this->except = [
            env('APP_API').'/order_list_create',
            env('APP_API').'/push_notification_order',
            env('APP_API').'/push_message',
            env('APP_API').'/logout',
        ];
    }
}