<?php

use Illuminate\Support\Facades\Schema;
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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('ic')->nullable();
            $table->string('detachment')->nullable();
            $table->string('map_lat')->nullable();
            $table->string('map_lng')->nullable();
            $table->string('place_id')->nullable();
            $table->string('formatted_address')->nullable();
            $table->string('health_issues')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->integer('membership_type_id')->nullable()->unsigned();
            $table->foreign('membership_type_id')->references('id')->on('membership_types')->onDelete('cascade');

            $table->integer('blood_type_id')->nullable()->unsigned();
            $table->foreign('blood_type_id')->references('id')->on('blood_types')->onDelete('cascade');

            $table->integer('branch_id')->nullable()->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
