<?php namespace Alfredoem\Ragnarok\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
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
        $this->installKernel();
        $this->installMigrations();
        $this->installMiddleware();
        $this->updateAuthConfig();
        $this->installRoutes();

        // here copy migrations
        $this->comment('**********************************************');
        $this->comment('****************Ragnarok**********************');
        $this->comment('**********************************************');
        $this->comment('');

        if ($this->option('force') || $this->confirm('Would you like to run your database migrations (make sure you have a database created)?', 'yes')) {
            (new Process('php artisan migrate', base_path()))->setTimeout(null)->run();
        }

        $this->publishStyles();
        $this->displayPostInstallationNotes();
    }

    protected function installKernel()
    {
        copy(
            RAGNAROK . '/resources/stubs/app/Http/Kernel.php',
            app_path('Http/Kernel.php')
        );
    }

    protected function InstallMigrations()
    {
        $fileName = '2016_01_01_000000_create_security_tables.php';
        $defaultUser = '2014_10_12_000000_create_users_table.php';
        $defaultResetPassword= '2014_10_12_100000_create_password_resets_table.php';

        if(File::exists(database_path('migrations/' . $defaultUser))) {
            File::delete(database_path('migrations/' . $defaultUser));
        }

        if(File::exists(database_path('migrations/' . $defaultResetPassword))) {
            File::delete(database_path('migrations/' . $defaultResetPassword));
        }

        if(File::exists(database_path('migrations/' . $fileName))) {
            File::delete(database_path('migrations/' . $fileName));
        }

        copy(
            RAGNAROK . '/resources/stubs/database/migrations/' . $fileName,
            database_path('migrations/' . $fileName)
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
            'App\User::class', 'Alfredoem\Ragnarok\SecUsers\SecUser::class', file_get_contents($path)
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

        copy(
            RAGNAROK . '/resources/stubs/app/Http/Middleware/RedirectIfAuthenticated.php',
            app_path('Http/Middleware/RedirectIfAuthenticated.php')
        );

        copy(
            RAGNAROK . '/resources/stubs/app/Http/Middleware/RagnarokApiGuard.php',
            app_path('Http/Middleware/RagnarokApiGuard.php')
        );

        copy(
            RAGNAROK . '/resources/stubs/app/Http/Middleware/VerifyCsrfToken.php',
            app_path('Http/Middleware/VerifyCsrfToken.php')
        );
    }

    public function installRoutes()
    {
        copy(
            RAGNAROK . '/resources/stubs/app/Http/routes.php',
            app_path('Http/routes.php')
        );
    }

    protected function displayPostInstallationNotes()
    {
        $this->line('<info>Default testing user</info>');
        $this->line('<info>admin@ragnarok.com:admin</info>');
        $this->line('<info>Have Fun!</info>');
        $this->line('<info>✔ Feel Good Inc. </info>');
    }

}