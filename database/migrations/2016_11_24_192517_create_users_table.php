<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('fid')->unsigned()->nullable();
            $table->string('username', 120);
            $table->string('name', 120)->nullable();
            $table->string('lastname', 120)->nullable();
            $table->string('email', 255);
            $table->string('password', 255);
            $table->text('picture')->nullable();
            $table->text('social_facebook')->nullable();
            $table->text('social_twitter')->nullable();
            $table->text('social_youtube')->nullable();
            $table->text('social_www')->nullable();
            $table->date('birthday')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 120)->nullable();
            $table->char('state', 2)->nullable();
            $table->string('country', 120)->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_admin')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
