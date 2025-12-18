<?php

namespace Leeuwenkasteel\Install\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        // De naam van de cookie die het token bevat
        $cookieName = 'activated_code';
        $token = (isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : null);

        // Als de cookie niet bestaat → redirect terug
        if (!$token) {
            return redirect()->route('code')
                ->with('error', 'Geen geldige installatietoken gevonden.');
        }

        // Valideer token via externe API
        try {
            $response = Http::timeout(5)->post('https://apps.leeuwenkasteel.nl/code-check', [
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            // Als de externe request mislukt (timeout, DNS, etc.)
            return redirect()->route('code')
                ->with('error', 'Verificatieservice niet bereikbaar.');
        }

        // Check de HTTP-status of response body
        if ($response->failed() || $response->json('success') !== true) {
            // Token is ongeldig
            return redirect()->route('code')
                ->with('error', 'Ongeldige of verlopen installatietoken.');
        }

        // Alles in orde, verder met de request
        return $next($request);
    }
}
