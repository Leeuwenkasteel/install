<?php

namespace Leeuwenkasteel\Install\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Leeuwenkasteel\Languages\Table\CountryTable;
use Artisan;

class InstallController extends Controller{ 
	protected $envPath;

    public function __construct()
    {
        $this->envPath = base_path('.env');
    }
	
	public function install(){
    $env = [];

    if (file_exists($this->envPath)) {
        $content = file_get_contents($this->envPath);

        // SESSION_DRIVER forceren naar 'file'
        if (preg_match('/^SESSION_DRIVER=.*/m', $content)) {
            // Reeds aanwezig → vervang
            $content = preg_replace('/^SESSION_DRIVER=.*/m', 'SESSION_DRIVER=file', $content);
        }

        // .env terug opslaan
        file_put_contents($this->envPath, $content);

        // .env opnieuw inlezen in Laravel
        \Artisan::call('config:clear');

        // Nu de env variabelen ophalen voor weergave
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                [$key, $value] = explode('=', $line, 2);
                $env[$key] = $value;
            }
        }
    }

    return view('install::index', compact('env'));
}

	
	public function code(){
		$envPath = base_path('.env');

    if (!file_exists($envPath)) {
        return back()->with('error', '.env bestand niet gevonden.');
    }

    $requiredKeys = [
        'GITHUB_TOKEN'   => '',
        'GITHUB_USERNAME'=> '',
        'GITHUB_API_URL' => 'https://api.github.com',
		'GITHUB_TOKEN_CLASSSIC' => '',
		'INSTALL'   => 'true',
		'ISEEK'   => '',
    ];

    $content = file_get_contents($envPath);

    foreach ($requiredKeys as $key => $defaultValue) {

        // Bestaat de key al?
        if (!preg_match("/^{$key}=.*/m", $content)) {

            // Voeg nieuwe key toe met newline
            $content .= "\n{$key}={$defaultValue}";
        }
    }

    // Schrijf terug naar .env
    file_put_contents($envPath, $content);

    // Refresh Laravel config cache
    Artisan::call('config:clear');
		return view('install::code');
	}
	

public function send(Request $req)
{
    $req->validate([
        'code' => 'required|string'
    ]);

    $response = Http::post('https://apps.leeuwenkasteel.nl/code-activate', [
        'code' => $req->code
    ]);
	
    if (!$response->successful()) {
        return back()->withErrors([
            'code' => 'Er ging iets mis met de validatie. Probeer het opnieuw.'
        ]);
    }

    if ($response->json('success') === true) {
        // Cookie voor 1 uur (3600 seconden)
        setcookie('activated_code', $req->code, time() + 86400, "/");

        return redirect()->route('install')
                         ->with('success', 'Code is correct en geactiveerd!');
    }

    return back()->withErrors([
        'code' => $response->json('message') ?? 'De ingevoerde code is ongeldig.'
    ]);
}

public function env(Request $request){
	if (!file_exists($this->envPath)) {
            return redirect()->back()->with('error', '.env bestand niet gevonden.');
        }
        $content = file_get_contents($this->envPath);
		foreach ($request->except('_token') as $key => $value) {
			if($key == 'DB_CONNECTION'){
				if($value != 'sqlite'){
					$content = preg_replace('/^#\s*(DB_HOST=.*)$/m', '$1', $content);
					$content = preg_replace('/^#\s*(DB_PORT=.*)$/m', '$1', $content);
					$content = preg_replace('/^#\s*(DB_DATABASE=.*)$/m', '$1', $content);
					$content = preg_replace('/^#\s*(DB_USERNAME=.*)$/m', '$1', $content);
					$content = preg_replace('/^#\s*(DB_PASSWORD=.*)$/m', '$1', $content);
				}
			}
		}
        foreach ($request->except('_token') as $rawKey => $value) {
			$key = preg_replace('/^#/', '', $rawKey);

			// Regel veilig vervangen, inclusief behoud van newline
			$content = preg_replace(
				"/^#?\s*{$key}=.*(\r?\n|$)/m",
				"{$key}={$value}$1",
				$content
			);
		}
		//dd( $content, $request->toArray());
        file_put_contents($this->envPath, $content);
		
		Artisan::call('install:migrate');
		
		$content = file_get_contents($this->envPath);

        // SESSION_DRIVER forceren naar 'file'
        if (preg_match('/^SESSION_DRIVER=.*/m', $content)) {
            // Reeds aanwezig → vervang
            $content = preg_replace('/^SESSION_DRIVER=.*/m', 'SESSION_DRIVER=database', $content);
        }

        // .env terug opslaan
        file_put_contents($this->envPath, $content);

        // .env opnieuw inlezen in Laravel
        Artisan::call('config:clear');

        return redirect()->route('github')->with('success', '.env is bijgewerkt!');
}

public function github(){

    return view('install::github',);
}

public function database(){

    return view('install::database',);
}

public function db(Request $req)
{
    $prefixes = ['EX', 'SCHEMA', 'SCHOLEN'];
    $fields   = ['HOST', 'PORT', 'DATABASE', 'USERNAME', 'PASSWORD'];

    $envPath = base_path('.env');
    $envContent = file_get_contents($envPath);

    foreach ($prefixes as $prefix) {
        foreach ($fields as $field) {
            $key = "{$prefix}_DB_{$field}";
            if ($req->has($key)) {
                $value = trim($req->input($key), '"');

                if (str_contains($envContent, "{$key}=")) {
                    // Vervang bestaande key
                    $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
                } else {
                    // Voeg toe als het nog niet bestaat
                    $envContent .= "\n{$key}={$value}";
                }

                // Update runtime config (optioneel)
                config(["database.connections.{$key}" => $value]);
            }
        }
    }

    // Schrijf terug naar .env
    file_put_contents($envPath, $envContent);
	
	Artisan::call('install:domains');
    return redirect()->route('account');
}

public function settings(){
	return view('install::settings');
}
public function account(){
	return view('install::account');
}

public function domains(){
	return view('install::domains');
}

public function lang(){
	$table = CountryTable::class;
	return view('install::lang', compact('table'));
}

public function app(){
	return view('install::app');
}

public function finish(){
	return view('install::finish');
}

public function finis(){
	$content = file_get_contents($this->envPath);

        // SESSION_DRIVER forceren naar 'file'
        if (preg_match('/^INSTALL=.*/m', $content)) {
            // Reeds aanwezig → vervang
            $content = preg_replace('/^INSTALL=.*/m', 'INSTALL=false', $content);
        }

        // .env terug opslaan
        file_put_contents($this->envPath, $content);
		return redirect()->route('login');
}
	
}