<?php
namespace Leeuwenkasteel\Install\Livewire;

use Livewire\Component;
use Leeuwenkasteel\Auth\Models\User;
use Illuminate\Support\Facades\Http;
use Leeuwenkasteel\Domains\Models\Domains;

class App extends Component
{
	
	public $showToken = false;
	public $user;
    public function mount()
    {
        $this->user = User::first();
    }
	
	public function token(){
		$domains = [];
		
		$domains = Domains::whereAppId(env('APP_ID'))->get()->pluck('id')->toArray();
			
		$d = implode(',',$domains);

		//dd(env('APP_ID'), $d, Auth::user()->email, Auth::user()->roles()->first());
		$response = Http::withHeaders([
			'X-Secret-Key' => env('APP_ID'),
		])->post('https://apps.leeuwenkasteel.nl/generate-token', [
			'email' => $this->user->email,
			'domains' => $d,
			'level' => $this->user->roles()->first()->level,
		]);
		
		//dd($response);

		// Check of het lukt
		if ($response->successful()) {
			$this->showToken = $response->json('token');
			// Gebruik token...
		} else {
			//dd($response);
			// Error handling
			$this->showToken = __('Oeps, something went wrong');
		}
	}

    


    public function render()
    {
        return view('install::livewire.app');
    }
}
