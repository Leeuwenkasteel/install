<?php
namespace Leeuwenkasteel\Install\Livewire;

use Livewire\Component;
use Leeuwenkasteel\Languages\Models\Country;
use Livewire\WithPagination;

class Lang extends Component
{
	use WithPagination;

    protected $paginationTheme = 'bootstrap';
	
    public $env;
    public $show = false;
	public $lang;
	public $search;
	public $active;

    public function mount()
    {
        $this->info();
    }

    public function info()
    {
        // Cast env string naar boolean netjes
        $raw = env('MULTI_LANGUAGES');
        $this->env = filter_var($raw, FILTER_VALIDATE_BOOLEAN);
        $this->show = $this->env;
		$this->langs = Country::all(); 
		$this->active = Country::whereActive(1)->orWhere('webshop',1)->get();
    }
	
	

    public function updatedEnv($value)
    {
        $this->updateMultiDomainsEnv($value);
        $this->show = (bool) $value;
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

        if (preg_match('/^MULTI_LANGUAGES=.*/m', $env)) {
            // vervang bestaande regel
            $env = preg_replace(
                '/^MULTI_LANGUAGES=.*/m',
                'MULTI_LANGUAGES=' . $textValue,
                $env
            );
        } else {
            // voeg toe als niet aanwezig
            $env .= PHP_EOL . 'MULTI_LANGUAGES=' . $textValue . PHP_EOL;
        }

        file_put_contents($path, $env);
    }

	public function upWeb($id){
		$find = Country::find($id);
		$up = Country::findOrFail($id)->update(['webshop' => !$find->webshop]);
		
		$this->info();
	}
	
	public function upActive($id){
		$find = Country::find($id);
		$up = Country::findOrFail($id)->update(['active' => !$find->active]);
		
		$this->info();
	}



    public function render()
    {
		$query = Country::query();
		if(!empty($this->search)){
			$query->where('country', 'like', '%' . $this->search . '%');

		}
		$items = $query->paginate(25);
        return view('install::livewire.lang', [
            'items' => $items,
		]);
    }
}
