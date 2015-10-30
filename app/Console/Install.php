<?php namespace Alfredoem\Ragnarok\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class Install extends Command
{
    /**
     * The name and signature of the console command
     *
     * @var string
     */
    protected $signature = 'Ragnarok:install {--force}';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Install the Ragnarok scaffolding into the application';

    public function handle()
    {
        $this->installMigrations();
        $this->installMiddleware();
        $this->updateAuthConfig();

        // here copy migrations
        $this->comment('**********************************************');
        $this->comment('****************Ragnarok**********************');
        $this->comment('**********************************************');
        $this->comment('');
        if ($this->option('force') || $this->confirm('Would you like to run your database migrations?', 'yes')) {
            (new Process('php artisan migrate', base_path()))->setTimeout(null)->run();
        }

        $this->publishStyles();
        $this->displayPostInstallationNotes();
    }

    protected function InstallMigrations()
    {
        copy(
            RAGNAROK . '/resources/stubs/database/migrations/2015_09_25_191344_create_security_tables.php',
            database_path('migrations/' . date('Y_m_d_His') .'_create_security_tables.php')
        );
    }

    /**
     * Update the "auth" configuration file.
     *
     * @return void
     */
    protected function updateAuthConfig()
    {
        $path = config_path('auth.php');

        file_put_contents($path, str_replace(
            'users', 'SecUsers', file_get_contents($path)
        ));

        file_put_contents($path, str_replace(
            'App\User::class', 'Alfredoem\Ragnarok\SecUser::class', file_get_contents($path)
        ));
    }

    protected function publishStyles()
    {
        (new Process('php artisan vendor:publish --tag=public --force', base_path()))->setTimeout(null)->run();
    }

    protected function installMiddleware()
    {
        copy(
            RAGNAROK . '/resources/stubs/app/Http/Middleware/Authenticate.php',
            app_path('Http/Middleware/Authenticate.php')
        );
    }

    protected function displayPostInstallationNotes()
    {
        $this->line('<info>Default user</info>');
        $this->line('<info>admin@shinra.com:admin</info>');
        $this->line('<info>✔ Feel Good Inc. </info>');
    }

}