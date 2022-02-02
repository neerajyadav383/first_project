<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('holder_name')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('name')->nullable();
            $table->string('branch')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('account_no')->nullable();
            $table->string('account_type')->nullable();
            $table->timestamps();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_details');
    }
}
