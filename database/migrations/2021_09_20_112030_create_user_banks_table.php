<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_banks', function (Blueprint $table) {
            $table->unsignedBigInteger('userid');
            $table->unsignedBigInteger('bankdetail_id');
            $table->primary(['userid','bankdetail_id']);

            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bankdetail_id')->references('id')->on('bank_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_banks');
    }
}
