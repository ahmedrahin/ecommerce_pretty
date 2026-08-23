<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCartNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            // Redirect to shop page if cart is empty
            return redirect()->route('shop')->with('message', 'Your cart is empty. Please add some products.');
        }
        
        return $next($request);
    }
}
