<?php

namespace Leeuwenkasteel\Install;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Leeuwenkasteel\Install\View\Components\LayoutComponent;
use Leeuwenkasteel\Install\Console\Commands\MigrateCommand;
use Leeuwenkasteel\Install\Http\Middleware\InstallMiddleware;
use Leeuwenkasteel\Install\Livewire\Pull;
use Leeuwenkasteel\Install\Livewire\Account;
use Leeuwenkasteel\Install\Livewire\Domains;
use Leeuwenkasteel\Install\Livewire\Lang;
use Leeuwenkasteel\Install\Livewire\App;
use Illuminate\Contracts\Http\Kernel;
use Livewire;
use File;

class InstallPackageServiceProvider extends ServiceProvider{
	
	public function register(): void{
	$this->commands([
        MigrateCommand::class,
    ]);
	  //comment!!
  }
  public function boot(Kernel $kernel): void{
    $this->loadViewsFrom(__DIR__.'/../resources/views', 'install');
    $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
	
	Blade::component('install::layout', LayoutComponent::class);
	
	Livewire::component('install::pull', Pull::class);
	Livewire::component('install::account', Account::class);
	Livewire::component('install::domains', Domains::class);
	Livewire::component('install::lang', Lang::class);
	Livewire::component('install::app', App::class);
	
	$kernel->pushMiddleware(InstallMiddleware::class);
	
	if ($this->app->runningInConsole()) {
      $this->commands([
          MigrateCommand::class,
      ]);
	}
  }
}