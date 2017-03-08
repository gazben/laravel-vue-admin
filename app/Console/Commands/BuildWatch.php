<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BuildWatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild the frontend on change';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time ', -1);
        chdir(resource_path('assets' . DIRECTORY_SEPARATOR .'frontend'));

        $this->info('Copy app.example.blade.php to resources/views/layouts/app.example.blade.php');
        copy(resource_path('views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'app.example.blade.php'),
            resource_path('views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'app.blade.php'));

        $this->info('Watching frontend');

        $process = new Process('npm run watch');
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                $this->error($buffer);
            } else {
                $this->info($buffer);
            }
        });
    }
}
