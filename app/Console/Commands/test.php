<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class test extends Command
{
    protected $signature = 'command:name';
    protected $description = 'Command description';

    final public function handle(): void
    {
    }
}
