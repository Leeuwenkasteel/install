<?php

namespace Leeuwenkasteel\Install\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class MigrateCommand extends Command{

    protected $signature = 'install:migrate';
    protected $description = 'Migrate to the database';

    public function handle(){
		$this->info('start migrate ..');

		Artisan::call('migrate');

		$this->info(Artisan::output());
	}
}