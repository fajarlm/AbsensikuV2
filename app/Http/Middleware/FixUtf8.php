<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FixUtf8
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$item) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
            }
        });

        $request->replace($input);

        return $next($request);
    }
}