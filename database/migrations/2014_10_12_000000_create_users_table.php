<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('userid');
            $table->string('sponsor_id');
            $table->string('placement_id');
            $table->string('name');
            $table->string('email');
            $table->integer('mobile');
            $table->string('password');
            $table->string('trans_pass');
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->integer('pincode')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->integer('status')->default('0');
            $table->decimal('roi', 15,2)->default('0');
            $table->decimal('booster', 15,2)->default('0');
            $table->decimal('direct', 15,2)->default('0');
            $table->decimal('matching', 15,2)->default('0');
            $table->decimal('direct_team_matching', 15,2)->default('0');
            $table->decimal('reward', 15,2)->default('0');
            $table->decimal('wallet', 15,2)->default('0');
            $table->decimal('topup_wallet', 15,2)->default('0');
            $table->timestamp('activation_timestamp')->nullable();
            $table->integer('direct_mems')->default('0');
            $table->integer('left_direct')->default('0');
            $table->integer('right_direct')->default('0');
            $table->decimal('total_earning', 15,2)->default('0');
            $table->string('earning_date')->default('');
            $table->string('wallet_lock')->default('Unlock');
            $table->timestamps();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('bank_details')->onDelete('cascade');
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
