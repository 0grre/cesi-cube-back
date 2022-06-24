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
        Schema::create('relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('relation_type_id')->nullable()->unsigned();
            $table->foreign('relation_type_id')->references('id')->on('relation_types')->onDelete('cascade');
            $table->unsignedBigInteger('first_user_id')->nullable()->unsigned();
            $table->foreign('first_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('second_user_id')->nullable()->unsigned();
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
        Schema::dropIfExists('relations');
    }
};
