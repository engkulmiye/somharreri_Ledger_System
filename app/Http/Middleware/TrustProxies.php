<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    protected $proxies = '*';

    protected $headers =
<<<<<<< HEAD
    Request::HEADER_X_FORWARDED_FOR |
=======
        Request::HEADER_X_FORWARDED_FOR |
>>>>>>> e068951e0b368bfffdaf3773e4ea8a4386a856ab
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
