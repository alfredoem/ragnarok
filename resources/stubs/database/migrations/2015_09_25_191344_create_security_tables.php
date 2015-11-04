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
    }

    public function secParameters()
    {
        Schema::create('SecParameters', function (Blueprint $table) {
            $table->increments('parId');
            $table->string('name', 50);
            $table->string('description');
            $table->string('value');
            $table->integer('userIns', 10);
            $table->dateTime('datetimeIns');
            $table->integer('userUpd', 10);
            $table->dateTime('datetimeUpd');
            $table->rememberToken();
        });

        DB::table('SecParameters')->insert([
            'name' => 'API_SECURITY_URL',
            'description' => 'URL del servidor de seguridad',
            'value'       => 'http://local.ragnarok.security.com/ragnarok/api/v1'
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
            'email' => 'admin@shinra.com',
            'password' => bcrypt('admin'),
        ]);
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
    }
}
