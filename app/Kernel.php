<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Middleware\HandleCors;




class Kernel extends HttpKernel
{
    protected $middleware = [
    HandleCors::class,
  
];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\SessionTimeoutMiddleware::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
          
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
       
        
    ];
}
