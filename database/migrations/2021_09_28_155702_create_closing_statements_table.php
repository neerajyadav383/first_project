<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClosingStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closing_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('roi', 15,2)->default(0);
            $table->decimal('booster', 15,2)->default(0);
            $table->decimal('direct', 15,2)->default(0);
            $table->decimal('matching', 15,2)->default(0);
            $table->decimal('direct_team_matching', 15,2)->default(0);
            $table->decimal('reward', 15,2)->default(0);
            $table->decimal('total_amount', 15,2)->default(0);
            $table->decimal('tds', 15,2)->default(0);
            $table->decimal('avail_amount', 15,2)->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('closing_statements');
    }
}
