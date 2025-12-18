<?php
namespace Leeuwenkasteel\Install\Livewire;

use Livewire\Component;
use Leeuwenkasteel\Domains\Models\Domains as D;

class Domains extends Component
{
    public $env;
    public $show = false;
    public $domain = [];
    public $domains;

    public function mount()
    {
        $this->info();
    }

    public function info()
    {
        // Cast env string naar boolean netjes
        $raw = env('MULTI_DOMAINS');
        $this->env = filter_var($raw, FILTER_VALIDATE_BOOLEAN);
        $this->show = $this->env;
        $this->domains = D::whereAppId(env('APP_ID'))->get();
    }

    /**
     * Livewire hook: wordt aangeroepen nadat $env is geüpdatet via wire:model
     * $value is de nieuwe boolean waarde (true/false)
     */
    public function updatedEnv($value)
    {
        $this->updateMultiDomainsEnv($value);

        // update show direct zonder full info() call om recursion te voorkomen
        $this->show = (bool) $value;

        // Als je wilt dat config-cache wordt geleegd wanneer je deze env wijziging maakt,
        // uncomment de volgende regel (kost een artisan-call):
        // \Artisan::call('config:clear');
    }

    protected function updateMultiDomainsEnv($value)
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            return;
        }

        // Zorg dat we 'true' of 'false' in de .env schrijven (lowercase)
        $textValue = $value ? 'true' : 'false';

        $env = file_get_contents($path);

        if (preg_match('/^MULTI_DOMAINS=.*/m', $env)) {
            // vervang bestaande regel
            $env = preg_replace(
                '/^MULTI_DOMAINS=.*/m',
                'MULTI_DOMAINS=' . $textValue,
                $env
            );
        } else {
            // voeg toe als niet aanwezig
            $env .= PHP_EOL . 'MULTI_DOMAINS=' . $textValue . PHP_EOL;
        }

        file_put_contents($path, $env);
    }

    public function upActive($id)
    {
        $find = D::find($id);
        if ($find) {
            $find->update(['active' => !$find->active]);
        }
        $this->info();
    }

    public function upDefault($id)
    {
        $item = D::find($id);
        if ($item) {
            D::whereAppId(env('APP_ID'))->update(['default' => null]);
            $item->update(['default' => 1]);
        }
        $this->info();
    }

    public function saveDomain()
    {
        $new = new D($this->domain);
        $new->app_id = env('APP_ID');
        $new->env = env('APP_ENV');
        $new->save();

        $this->info();
    }

    public function render()
    {
        return view('install::livewire.domains');
    }
}
