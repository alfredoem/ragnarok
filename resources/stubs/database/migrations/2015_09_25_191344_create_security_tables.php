<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
    }

    public function secParameters()
    {
        Schema::create('SecParameters', function (Blueprint $table) {
            $table->increments('parId');
            $table->string('name', 50);
            $table->string('description');
            $table->string('value');
            $table->integer('userIns');
            $table->dateTime('datetimeIns');
            $table->integer('userUpd');
            $table->dateTime('datetimeUpd');
            $table->rememberToken();
        });

        DB::table('SecParameters')->insert([
            'name' => 'API_SECURITY_URL',
            'description' => 'URL del api servidor de seguridad',
            //'value'       => 'http://local.ragnarok.security.com/ragnarok/api/v1'
            'value'       => 'http://172.16.11.237/ragnarok/public/api/v1'
        ]);

        DB::table('SecParameters')->insert([
            'name' => 'SERVER_SECURITY_URL',
            'description' => 'URL del servidor de seguridad',
            //'value'       => 'http://local.ragnarok.security.com'
            'value'       => 'http://172.16.11.237/ragnarok/public'
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
            $table->dateTime('lastPasswordChange');
            $table->tinyInteger('invalidAttempts', false);
            $table->tinyInteger('status', false);
            $table->rememberToken();
        });

        DB::table('SecUsers')->insert([
            'firstName' => 'Cloud',
            'lastName'  => 'Strife',
            'email' => 'admin@ragnarok.com',
            'password' => bcrypt('admin'),
        ]);
    }

    public function secApps()
    {
        \Schema::create('SecApps', function(Blueprint $table){
            $table->increments('appId');
            $table->string('name', 100);
            $table->text('description');
            $table->tinyInteger('type');
            $table->string('logo', '250');
            $table->string('url', '250');
            $table->tinyInteger('status')->unsigned();
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
    }
}
