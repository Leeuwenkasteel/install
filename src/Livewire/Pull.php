<?php
namespace Leeuwenkasteel\Install\Livewire;

use Livewire\Component;
use Symfony\Component\Process\Process;
use Github\Client;
use Illuminate\Support\Facades\File;
use Artisan;
class Pull extends Component
{
    public $packages = [];
    public $table = [];
    public $output = ''; // Voor Git output
    public $unregisteredProvidersClipboard = [];
	public $webshop = false;
	public $single = [];

    public function mount()
    {
        $this->loadGitHubRepos();
        $this->prepareUnregisteredProviders();
    }

    /**
     * Laad private repositories van GitHub
     */
    private function loadGitHubRepos()
    {
        $token = env('GITHUB_TOKEN');

        if (!$token) {
            session()->flash('error', 'GitHub token niet ingesteld.');
            return;
        }

        $client = new Client();
        $client->authenticate($token, null, \Github\AuthMethod::ACCESS_TOKEN);

        $this->packages = $client->currentUser()->repositories([
            'type' => 'private',
            'affiliation' => 'owner,collaborator'
        ]);

        foreach ($this->packages as $repo) {
            $packagePath = base_path("packages/leeuwenkasteel/{$repo['name']}");

            if (is_dir($packagePath)) {
                $this->table[$repo['name']] = [
                    'status' => 'Bestaat al',
                    'class' => 'text-success'
                ];
            } else {
                $this->table[$repo['name']] = [
                    'status' => 'Niet gecloned',
                    'class' => 'text-muted'
                ];
            }
			
			if($repo['name'] == 'webshop'){
				$this->webshop = true;
			}
			
			$allowed = ['cashdesk', 'donate', 'portfolio', 'questions', 'todo'];

			if (in_array($repo['name'], $allowed, true)) {
				$this->single[] = $repo['name'];
			}
        }
    }

    /**
     * Clone een repository
     */
    public function cloneRepo($repoName, $cloneUrl)
    {
        $this->output = ''; 
        $packagePath = base_path("packages/leeuwenkasteel/$repoName");

        if (is_dir($packagePath)) {
            $this->table[$repoName] = [
                'status' => 'Bestaat al',
                'class' => 'text-warning'
            ];
            $this->output = "Repository $repoName bestaat al.\n";
            return;
        }

        $token = env('GITHUB_TOKEN_CLASSSIC');
        $cloneUrlWithToken = "https://{$token}@github.com/Leeuwenkasteel/{$repoName}.git";

        $process = new Process(['git', 'clone', $cloneUrlWithToken, $packagePath]);
        $process->run(function ($type, $buffer) {
            $this->output .= $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->table[$repoName] = [
                'status' => 'Fout bij clone',
                'class' => 'text-danger'
            ];
            \Log::error("Git clone failed for $repoName: " . $process->getErrorOutput());
        } else {
            $this->table[$repoName] = [
                'status' => 'Clone success',
                'class' => 'text-success'
            ];
        }
    }

    /**
     * Vind alle service providers in packages/leeuwenkasteel die nog niet geregistreerd zijn
     */
    public function prepareUnregisteredProviders()
    {
        $basePath = base_path('packages/leeuwenkasteel');
        $allProviders = $this->scanServiceProviders($basePath);
        $registeredProviders = $this->getRegisteredProviders();

        // Normaliseer en filter
        // 1. Haal \src uit de class names + normaliseer slashes
		$normalize = function ($class) {
			$class = str_ireplace('\\src', '', $class);     // verwijder \src
			$parts = explode('\\', $class);

			// Maak elk onderdeel een hoofdletter
			$parts = array_map(fn($p) => ucfirst($p), $parts);

			return implode('\\', $parts);
		};

		$allNormalized = array_map(fn($p) => strtolower($normalize($p)), $allProviders);
		$registeredNormalized = array_map(fn($p) => strtolower($normalize($p)), $registeredProviders);

		$unregistered = [];

		foreach ($allProviders as $provider) {
			$normalized = strtolower($normalize($provider));

			if (!in_array($normalized, $registeredNormalized)) {
				// Bewaar de nette, gestude hoofdletter versie
				$unregistered[] = $normalize($provider);
			}
		}


        // Omzetten naar ::class, formaat
        $lines = array_map(fn($p) => $p . ',', $unregistered);
        $this->unregisteredProvidersClipboard = implode("\n", $lines);
		
		//dd($this->unregisteredProvidersClipboard, $allProviders);
    }

    /**
     * Scan packages/leeuwenkasteel op ServiceProvider.php bestanden
     */
    private function scanServiceProviders($directory)
    {
        $providers = [];
        $files = File::allFiles($directory);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php' && str_ends_with($file->getFilename(), 'ServiceProvider.php')) {
                $relativePath = str_replace(base_path() . '/', '', $file->getRealPath());
                $class = $this->pathToClass($relativePath);
                $providers[] = $class;
            }
        }

        return $providers;
    }

    private function pathToClass($path)
    {
        $path = str_replace(['/', '.php'], ['\\', ''], $path);
        $namespaceStart = 'packages\\';
        if (str_starts_with($path, $namespaceStart)) {
            $path = substr($path, strlen($namespaceStart));
        }
        return $path;
    }

    private function getRegisteredProviders()
    {
        $file = base_path('bootstrap/providers.php');
        if (!file_exists($file)) {
            return [];
        }
        $content = file_get_contents($file);
        preg_match_all('/([A-Za-z0-9_\\\\]+)::class/', $content, $matches);
        return $matches[1] ?? [];
    }
	
public function registerProviders()
{
    $file = base_path('bootstrap/providers.php');

    // Maak weer array van de regels
    $unregisteredProviders = explode("\n", trim($this->unregisteredProvidersClipboard));

    if (empty($unregisteredProviders)) {
        session()->flash('success', 'Geen nieuwe providers om toe te voegen.');
        return;
    }

    // ⇩ HIER: mapnaam corrigeren → StudlyCase (error-logger → ErrorLogger)
    $unregisteredProviders = array_map(function ($p) {

        // trim spaties en komma
        $p = trim($p);
        $p = rtrim($p, ",");

        // splits namespace in delen
        $segments = explode('\\', $p);

        // laatste 3 segmenten kunnen een package mapnaam bevatten
        foreach ($segments as &$segment) {
            // vervang -door spatie → maak elk woord uppercase → verwijder spatie
            $segment = str_replace(' ', '', ucwords(str_replace('-', ' ', $segment)));
        }

        // zet weer in elkaar
        return implode('\\', $segments);

    }, $unregisteredProviders);

    // Voeg ::class toe
    $linesToAdd = array_map(fn($p) => "    {$p}::class,", $unregisteredProviders);

    // Voeg ze toe net voor de afsluitende bracket
    $content = file_get_contents($file);
    $content = preg_replace('/\];\s*$/', implode("\n", $linesToAdd) . "\n];", $content);

    file_put_contents($file, $content);

    $this->dispatch('swal.providers');

    $this->prepareUnregisteredProviders();
}


private function scanLocalPackages()
{
    $path = base_path('packages/leeuwenkasteel');

    if (!is_dir($path)) {
        return [];
    }

    $dirs = array_filter(glob($path . '/*'), 'is_dir');

    return array_map(function ($dir) {
        return basename($dir);
    }, $dirs);
}
private function getRegisteredRepositories()
{
    $composerFile = base_path('composer.json');
    $json = json_decode(file_get_contents($composerFile), true);

    if (!isset($json['repositories'])) {
        return [];
    }

    $registered = [];

    foreach ($json['repositories'] as $repo) {
        if (($repo['type'] ?? '') === 'path' && isset($repo['url'])) {
            $registered[] = basename($repo['url']); // bijv: "install"
        }
    }

    return $registered;
}
public function missingComposerPackages()
{
    $local = $this->scanLocalPackages();
    $registered = $this->getRegisteredRepositories();

    return array_diff($local, $registered);
}
public function registerComposerPackages()
{
    $composerFile = base_path('composer.json');
    $json = json_decode(file_get_contents($composerFile), true);

    $missing = $this->missingComposerPackages();

    if (empty($missing)) {
        $this->dispatch('swal.nocomposer');
        return;
    }

    foreach ($missing as $package) {
        $json['repositories'][] = [
            "type" => "path",
            "url" => "packages/leeuwenkasteel/" . $package,
            "options" => [
                "symlink" => true
            ]
        ];
    }

    // composer.json opnieuw opslaan (mooi geformatteerd)
    file_put_contents(
        $composerFile,
        json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );

    $this->dispatch('swal.composer');
}

public function install(){
	if($this->webshop){
		Artisan::call('install:webshop --package=true');
	}else{
		Artisan::call('install:auth');
	}
	
	$this->dispatch('swal.install');
}

public function migrate(){
	Artisan::call('install:migrate');
	$this->dispatch('swal.migrate');
}

    public function render()
    {
        return view('install::livewire.pull');
    }
}
