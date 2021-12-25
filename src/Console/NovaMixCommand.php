<?php

namespace AkkiIo\LaravelNovaAssets\Console;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Symfony\Component\Process\Process;

class NovaMixCommand extends Command
{
    protected $assets;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova:mix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mix all laravel nova assets';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $this->setNova();
        $this->createAssets();

        $this->comment('Publishing the latest webpack file.');
        $this->call('vendor:publish', [
            '--tag' => 'laravel-nova-assets-webpack',
            '--force' => 0,
        ]);

        $this->runMixCommand();
        $this->cleanup();

        $this->comment('Laravel Nova Assets mixed successfully. Enjoy.');
    }

    private function cleanup()
    {
        $this->comment('Doing cleanup.');
        File::deleteDirectory(resource_path('laravel-nova-assets'));
    }

    /**
     * Run mix command.
     *
     * @return void
     */
    private function runMixCommand()
    {
        $this->comment('Run mix command.');
        $process = new Process(['npx', 'mix', '--mix-config=webpack.mix.nova.js', '--production']);
        $process->start();
        foreach ($process as $data) {
            $this->comment($data);
        }
    }

    /**
     * Create assets.
     *
     * @return void
     */
    private function createAssets()
    {
        $this->comment('Delete existing directory...');
        File::deleteDirectory(resource_path('laravel-nova-assets'));

        $this->comment('Generating asset files...');
        $this->assets = [
            'theme-styles' => [
                'files' => Nova::themeStyles(),
                'empty' => 'empty.css',
            ],
            'tool-styles' => [
                'files' => Nova::allStyles(),
                'empty' => 'empty.css',
            ],
            'custom-styles' => [
                'files' => config('laravel-nova-assets.styles'),
                'empty' => 'empty.css',
            ],
            'tool-scripts' => [
                'files' => Nova::allScripts(),
                'empty' => 'empty.js',
            ],
            'custom-scripts' => [
                'files' => config('laravel-nova-assets.scripts'),
                'empty' => 'empty.js',
            ],
        ];

        foreach ($this->assets as $directory => $options) {
            $this->createFiles($directory, $options);
        }
    }

    /**
     * Create files.
     *
     * @param $directory
     * @param array $options
     * @return void
     */
    private function createFiles($directory, array $options)
    {
        $this->comment('Creating Directory '.$directory);
        $directoryPath = resource_path('laravel-nova-assets/'.$directory);
        File::makeDirectory($directoryPath, 0755, true);

        if (count($options['files'])) {
            foreach ($options['files'] as $key => $file) {
                $this->comment('Creating file '.$file);
                $filePath = $directoryPath.'/'.$key.'-'.File::name($file).'.'.File::extension($file);
                file_put_contents($filePath, file_get_contents($file));
            }
        } else {
            $this->comment('Creating empty file ');
            $filePath = $directoryPath.'/'.$options['empty'];
            file_put_contents($filePath, '');
        }
    }

    /**
     * Do the prep work for nova.
     *
     * @return void
     * @throws \Exception
     */
    private function setNova()
    {
        $this->comment('Temporary allow all users to login...');
        Gate::define('viewNova', function ($user) {
            return true;
        });

        $this->comment('Login the first url...');
        $userModel = config('laravel-nova-assets.user');
        Auth::login((new $userModel)::first());

        $this->comment('Set the request to nova URL...');
        app()->handle(Request::create($this->getNovaUrl()));
    }

    /**
     * Get the nova URL.
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string
     */
    private function getNovaUrl()
    {
        if (config('nova.domain')) {
            return config('nova.domain');
        }

        return config('app.url').config('nova.path');
    }
}
