<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSecurityTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->secUsers();
        $this->secParameters();
        $this->secApps();
        $this->secUserSessions();
        $this->secMenus();
        $this->secRoles();
        $this->secRoleMenus();
        $this->secRoleUsers();
        $this->secActions();
        $this->secLogActions();
        $this->SecLogLoginAttempts();
    }

    public function secParameters()
    {
        Schema::create('SecParameters', function (Blueprint $table) {
            $table->increments('parId');
            $table->string('name', 50);
            $table->string('description');
            $table->string('value');
            $table->unsignedInteger('userIns')->default(0);
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->unsignedInteger('userUpd')->default(0);
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });

        DB::table('SecParameters')->insert([
            [
                'name' => 'API_SECURITY_URL',
                'description' => 'The security API url',
                'value'       => 'http://local.admin.security.com/api/v1'
            ],
            [
                'name' => 'SERVER_SECURITY_URL',
                'description' => 'The security server url',
                'value'       => 'http://local.admin.security.com'
            ],
            [
                'name' => 'MAX_LOGIN_ATTEMPTS',
                'description' => 'The maximum number of login attempts for delaying further attempts',
                'value'       => '5'
            ],
            [
                'name' => 'CONNECTION_TO_SECURITY',
                'description' => 'The connection mode to the security backend',
                'value'       => '5'
            ]
        ]);
    }

    public function secUsers()
    {
        Schema::create('SecUsers', function (Blueprint $table) {
            $table->increments('userId');
            $table->char('email', 50);
            $table->string('password', 60);
            $table->string('lastName', 50);
            $table->string('firstName', 50);
            $table->char('changePassword', 1);
            $table->dateTime('lastPasswordChange')->default('0000-00-00 00:00:00');
            $table->unsignedTinyInteger('invalidAttempts', false);
            $table->unsignedTinyInteger('status', false);
            $table->rememberToken();
            $table->unsignedInteger('userIns')->default(0);
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->unsignedInteger('userUpd')->default(0);
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });

        DB::table('SecUsers')->insert([
            [
                'firstName' => 'Yitan',
                'lastName'  => 'Tribal',
                'email' => 'admin@security.com',
                'password' => bcrypt('admin'),
            ],
            [
                'firstName' => 'Cloud',
                'lastName'  => 'Strife',
                'email' => 'admin@ragnarok.com',
                'password' => bcrypt('admin'),
            ],
        ]);
    }

    public function secApps()// Ragnarok
    {
        Schema::create('SecApps', function(Blueprint $table){
            $table->increments('appId');
            $table->string('name', 100);
            $table->text('description');
            $table->unsignedTinyInteger('type')->default(1);
            $table->string('logo', '250');
            $table->string('url', '250');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('userIns')->default(0);
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->unsignedInteger('userUpd')->default(0);
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });

        DB::table('SecApps')->insert([
            [
                'name' => 'Security Backend',
                'description' => 'Lorem ipsum dolor sit amet',
                'type' => 1,
                'logo' => 'meteor-logo.png',
                'url' => 'http://local.admin.security.com',
                'status' => 1
            ],
            [
                'name' => 'Admin App',
                'description' => 'Lorem ipsum dolor sit amet',
                'type' => 1,
                'logo' => 'meteor-logo.png',
                'url' => 'http://local.admin.app.com',
                'status' => 1
            ],
            [
                'name' => 'Admin App',
                'description' => 'Lorem ipsum dolor sit amet',
                'type' => 1,
                'logo' => 'meteor-logo.png',
                'url' => 'http://172.16.11.237/admin-application/public',
                'status' => 1
            ],
        ]);
    }

    public function secUserSessions()// Ragnarok
    {
        Schema::create('SecUserSessions', function(Blueprint $table){
            $table->increments('userSessionId');
            $table->unsignedInteger('userId');
            $table->char('sessionCode', 15);
            $table->string('ipAddress', 50);
            $table->char('status', 1)->default(1);
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });
    }

    public function secMenus()
    {
        Schema::create('SecMenus', function(Blueprint $table){
            $table->increments('menuId');
            $table->unsignedInteger('menuParentId');
            $table->unsignedInteger('appId');
            $table->char('isChild', 1)->default(0);
            $table->string('name', 50);
            $table->text('description');
            $table->unsignedTinyInteger('position');
            $table->char('division', 1);
            $table->string('icon', 100);
            $table->string('route', 500);
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('userIns')->default(0);
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->unsignedInteger('userUpd')->default(0);
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });

        DB::table('SecMenus')->insert([
            [
                'menuParentId' => 0,
                'appId' => 1,
                'isChild' => 0,
                'name' => 'Security',
                'description' => 'Lorem ipsum dolor set amet',
                'position' => 1,
                'division' => '',
                'icon' => 'fa-shield',
                'route' => '',
                'status' => 1
            ],
            [
                'menuParentId' => 1,
                'appId' => 1,
                'isChild' => 1,
                'name' => 'Menus',
                'description' => 'Lorem ipsum dolor set amet',
                'position' => 2,
                'division' => '',
                'icon' => '',
                'route' => '',
                'status' => 1
            ],
            [
                'menuParentId' => 1,
                'appId' => 1,
                'isChild' => 1,
                'name' => 'Profiles',
                'description' => 'Lorem ipsum dolor set amet',
                'position' => 3,
                'division' => '',
                'icon' => '',
                'route' => '',
                'status' => 1
            ],
            [
                'menuParentId' => 1,
                'appId' => 1,
                'isChild' => 1,
                'name' => 'Users',
                'description' => 'Lorem ipsum dolor set amet',
                'position' => 4,
                'division' => '',
                'icon' => '',
                'route' => '',
                'status' => 1
            ],
        ]);
    }

    public function secRoles()
    {
        Schema::create('SecRoles', function(Blueprint $table){
            $table->increments('roleId');
            $table->unsignedInteger('appId');
            $table->string('name');
            $table->text('description');
            $table->unsignedTinyInteger('level');
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('userIns')->default(0);
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->unsignedInteger('userUpd')->default(0);
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });

        DB::table('SecRoles')->insert([
           [
               'appId' => 1,
               'name'  => 'Shinigami',
               'description' => 'Lorem ipsum dolor sit amet',
               'level' => 0,
           ]
        ]);
    }

    public function secRoleMenus()
    {
        Schema::create('SecRoleMenus', function(Blueprint $table){
            $table->unsignedInteger('roleId')->default(0);
            $table->unsignedInteger('menuId')->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('userIns')->default(0);
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->unsignedInteger('userUpd')->default(0);
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });
    }

    public function secRoleUsers()
    {
        Schema::create('SecRoleUsers', function(Blueprint $table) {
            $table->unsignedInteger('userId');
            $table->unsignedInteger('roleId');
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('userIns')->default(0);
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->unsignedInteger('userUpd')->default(0);
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });
    }

    public function secActions()
    {
        Schema::create('SecActions', function(Blueprint $table){
           $table->char('actionCode', 50);
           $table->string('actionName', 100);
        });
    }

    public function secLogActions()
    {
        Schema::create('SecLogActions', function(Blueprint $table){
           $table->increments('logId');
           $table->unsignedInteger('appId')->default(0);
           $table->unsignedInteger('userId')->default(0);
           $table->unsignedInteger('menuId')->default(0);
           $table->unsignedInteger('actionId')->default(0);
           $table->text('beforeAction');
           $table->text('afterAction');
           $table->char('ipAddress', 50);
           $table->tinyInteger('actionResult');
           $table->date('logDate')->default('0000-00-00');
           $table->unsignedSmallInteger('logDateYear')->default(0);
           $table->unsignedTinyInteger('logDateMonth')->default(0);
           $table->unsignedTinyInteger('logDateHour')->default(0);
           $table->datetime('logDatetime')->default('0000-00-00 00:00:00');
        });
    }

    public function secLogLoginAttempts()
    {
        Schema::create('SecLogLoginAttempt', function (Blueprint $table) {
            $table->increments('logId');
            $table->char('email', 50);
            $table->unsignedInteger('userId')->default(0);
            $table->char('ipAddress', 50)->default('');
            $table->unsignedTinyInteger('invalidAttempts')->default(0);
            $table->unsignedInteger('userStatus')->default(0);
            $table->tinyInteger('loginResult')->default(0);
            $table->date('logDate')->default('0000-00-00');
            $table->unsignedSmallInteger('logDateYear')->default(0);
            $table->unsignedTinyInteger('logDateMonth')->default(0);
            $table->unsignedTinyInteger('logDateHour')->default(0);
            $table->datetime('logDatetime')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('SecUsers');
        Schema::drop('SecParameters');
        Schema::drop('SecApps');
        Schema::drop('SecUserSessions');
        Schema::drop('SecMenus');
        Schema::drop('SecRoles');
        Schema::drop('SecRoleMenus');
        Schema::drop('SecRoleUsers');
        Schema::drop('SecActions');
        Schema::drop('SecLogActions');
        Schema::drop('SecLogLoginAttempt');
    }
}
