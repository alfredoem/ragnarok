# Ragnarok
Authentication, Roles (coming soon) and Menus (coming soon) for a specific bussines logic

## Installation

1. Require this package in your composer.json and run composer update (or run `composer require alfredoem/ragnarok` directly, if you want get the last release add dev-master):

		"alfredoem/ragnarok": "^0.3.0"
		
2. After composer update, add service providers to the `config/app.php`

		Alfredoem\Ragnarok\RagnarokServiceProvider::class,
		
3. Configure your database.
	    
3. The next step is to install Ragnarok Security component. Run this command in terminal:

		$ php artisan Ragnarok:install


