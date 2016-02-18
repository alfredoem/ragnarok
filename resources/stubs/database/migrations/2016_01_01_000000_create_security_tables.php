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
    }

    public function secParameters()
    {
        Schema::create('SecParameters', function (Blueprint $table) {
            $table->increments('parId');
            $table->string('name', 50);
            $table->string('description');
            $table->string('value');
            $table->integer('userIns');
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->integer('userUpd');
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });

        DB::table('SecParameters')->insert([
            [
                'name' => 'API_SECURITY_URL',
                'description' => 'URL del API de seguridad',
                'value'       => 'http://172.16.11.237/crona/public/api/v1'
            ],
            [
                'name' => 'SERVER_SECURITY_URL',
                'description' => 'URL del servidor de seguridad',
                'value'       => 'http://172.16.11.237/crona/public'
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
            $table->tinyInteger('invalidAttempts', false);
            $table->tinyInteger('status', false);
            $table->rememberToken();
            $table->integer('userIns');
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->integer('userUpd');
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });

        DB::table('SecUsers')->insert([
            'firstName' => 'Cloud',
            'lastName'  => 'Strife',
            'email' => 'admin@ragnarok.com',
            'password' => bcrypt('admin'),
        ]);
    }

    public function secApps()// Ragnarok
    {
        Schema::create('SecApps', function(Blueprint $table){
            $table->increments('appId');
            $table->string('name', 100);
            $table->text('description');
            $table->tinyInteger('type');
            $table->string('logo', '250');
            $table->string('url', '250');
            $table->tinyInteger('status')->unsigned();
            $table->integer('userIns');
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->integer('userUpd');
            $table->date('dateUpd')->default('0000-00-00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
        });

        DB::table('SecApps')->insert([
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
            ]
        ]);
    }

    public function secUserSessions()// Ragnarok
    {
        Schema::create('SecUserSessions', function(Blueprint $table){
            $table->increments('userSessionId');
            $table->integer('userId');
            $table->char('sessionCode', 15);
            $table->string('ipAddress', 50);
            $table->char('status', 1)->default(1);
            $table->date('dateIns')->default('0000-00-00');
            $table->dateTime('datetimeIns')->default('0000-00-00 00:00:00');
            $table->dateTime('datetimeUpd')->default('0000-00-00 00:00:00');
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
    }
}
