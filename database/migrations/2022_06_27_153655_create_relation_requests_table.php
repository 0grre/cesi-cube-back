<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('relation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->unsignedBigInteger('first_user_id')->unsigned();
            $table->foreign('first_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('second_user_id')->unsigned();
            $table->foreign('second_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('relation_requests');
    }
};
