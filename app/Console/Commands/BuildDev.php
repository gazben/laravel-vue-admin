<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BuildDev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build and links the frontend dependencies';

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
        chdir(resource_path('assets' . DIRECTORY_SEPARATOR .'frontend'));
        $this->info('Building frontend');
        $process = new Process('npm run build-dev');
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                $this->error($buffer);
            } else {
                $this->info($buffer);
            }
        });
        $this->info('Linking..');

        // Symlink folders
        $res = [
            'assets' . DIRECTORY_SEPARATOR .'css',
            'assets' . DIRECTORY_SEPARATOR .'img',
            'assets' . DIRECTORY_SEPARATOR .'js',
            'assets' . DIRECTORY_SEPARATOR .'fonts',
            'logo.png',
            'app.js',
            'vendor.js',
        ];

        foreach ($res as $entry) {
            $target = resource_path('assets' . DIRECTORY_SEPARATOR .'frontend' . DIRECTORY_SEPARATOR . 'dist'  . DIRECTORY_SEPARATOR . $entry);
            $link = public_path($entry);
            $this->info('Creating symlink. Target: ' . $target . ' Link: ' . $link);
            if (file_exists($target) && !file_exists($link)) {
                try{
                    if(!symlink($target, $link)){
                        $this->error('Symlink creation failed!');
                    }
                } catch(Exception $exception){
                    $this->error('Symlink creation failed! Cause: ' . $exception->getMessage() .' You have to link manually:');
                    $this->error('ln -s ' . $target . ' ' . $link);
                }
            } else {
                $this->info('Symlink creation skipped. Target exists: ' . (file_exists($target) ? 'true' : 'false')
                    . ' Link exists: ' . (file_exists($link) ? 'true' : 'false') );
            }
        }

        $index = file_get_contents(resource_path('assets' . DIRECTORY_SEPARATOR .'frontend' . DIRECTORY_SEPARATOR .'dist' . DIRECTORY_SEPARATOR .'index.html'));
        $index = str_replace('<meta charset="utf-8">',
            '
            <meta charset="utf-8">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <script>
                window.Laravel = {!! json_encode( [ "csrfToken" => csrf_token() ]) !!}
            </script>
    </script>
        ', $index);

        $appBladePath = resource_path('views' . DIRECTORY_SEPARATOR .'layouts' . DIRECTORY_SEPARATOR .'app.blade.php');
        $this->info('Removing ' . $appBladePath);

        if(file_exists($appBladePath)){
            if(!unlink($appBladePath)){
                $this->error('Error removing ' . $appBladePath);
                return;
            }
        }

        $this->info('Writing app.blade.php...');
        // $this->info($index);
        file_put_contents($appBladePath, $index);
    }
}
