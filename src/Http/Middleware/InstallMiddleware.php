<?php

namespace Leeuwenkasteel\Install\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class InstallMiddleware{
    public function handle(Request $request, Closure $next){
		if (
				$request->is('code*') ||
				$request->is('install*') ||
				$request->segment(1) == 'app' ||
				$request->segment(1) == 'livewire'
			) {
            return $next($request);
		}
		if (env('INSTALL') == 1) {
			return Redirect::route('install');
		}
		return $next($request);
	}
}