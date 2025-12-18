<?php
namespace Leeuwenkasteel\Install\Livewire;

use Livewire\Component;
use Symfony\Component\Process\Process;
use Leeuwenkasteel\Auth\Models\User;
use Leeuwenkasteel\Auth\Emails\VerifyMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail;

class Account extends Component
{
	public $info = [];
	public $showCode = false;
	public $user;
	public $messages = [];
	
    public function mount(){
		$this->info();
		$this->changeCode();
    }
	
	public function info(){
		$this->user = User::first();
		$this->info['name'] = $this->user->name;
		$this->info['username'] = $this->user->username;
		$this->info['email'] = $this->user->email;
		$this->info['validate'] = $this->user->email_verified_at;
	}
	
	public function account(){
		if(empty($this->info['password']) || $this->info['password'] != $this->info['confirm']){
			$this->messages[] = 'Wachtwoord komt niet overeen';
			$this->info['password'] = '';
			$this->info['confirm'] = '';
			return;
		}
		
		$code = random_int(100000, 999999);
		$cn = mt_rand(1000000000, 9999999999);
		$random = Str::random(40);
		
		$words = explode(" ", $this->info['name']);
        $acronym = "";

        foreach ($words as $w) {
        	$acronym .= mb_substr($w, 0, 1);
        }
		
		$user = $this->user;
		$user->name = $this->info['name'];
		$user->email = $this->info['email'];
		$user->username = $this->info['username'];
		$user->password = Hash::make($this->info['password']);
		$user->initals = $acronym;
		$user->clientnumber = $cn;
		$user->domain_id = config('app.domainid');
		$user->code = $code;
		$user->token = $random;
		$user->email_verified_at = null;
		$user->update();
		
		$maildata = [
			'title' => __('Verify your emailadress'),
			'code' => $random,
			'name' => $this->info['name'],
		];
		
		Mail::to($this->info['email'])->send(new VerifyMail($maildata));
		
		$this->messages = [];
		$this->messages[] = 'Gegevens zijn opgeslagen';
		$this->messages[] = 'Email is gestuurd';

		$this->changeCode();
		$this->info();
		$this->info['password'] = '';
		$this->info['confirm'] = '';
	}
	
	public function changeCode(){
		$this->showCode = ($this->user->token == null ? false : true);
	}
	
	public function code(){
		if($this->user->token == $this->info['code']){
			$user = $this->user;
			$user->token = null;
			$user->email_verified_at = now();
			$user->update();
		}else{
			$this->info['code'] = '';
			$this->messages = [];
			$this->messages[] = 'foute token';
		}
		
		$this->info();
	}
	
	public function resend(){
		try {
			$maildata = [
				'title' => __('Verify your emailadress'),
				'code' => $this->user->token,
				'name' => $this->user->name,
			];

			Mail::to($this->user->email)->send(new VerifyMail($maildata));

			$this->messages = ['Email is gestuurd'];
		} 
		catch (\Exception $e) {
			$this->messages['MAIL FOUT: ' . $e->getMessage()];

			//$this->messages = ['Er ging iets mis met het versturen van de email'];
		}
	}


    public function render()
    {
        return view('install::livewire.account');
    }
}
