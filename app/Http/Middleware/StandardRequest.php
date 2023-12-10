<?php

namespace App\Http\Middleware;

use App\Helpers\Persian;
use Closure;
use Illuminate\Http\Request;

/**
 * Class ArToEnMiddleware
 * @package Myth\Support\Middlewares
 */
class StandardRequest
{

    /**
     * @var array
     */
    protected $except = [];

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $except = array_merge($this->except, array_slice(func_get_args(), 2));
        $request->merge($this->process($request->except($except)));
        return $next($request);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function process(array $data): array
    {
        array_walk_recursive(
            $data,
            function (&$value, $key) {
                if(!is_null($value) && !empty($value))
                $value = Persian::standard($value);
            }
        );

        return $data;
    }
}
