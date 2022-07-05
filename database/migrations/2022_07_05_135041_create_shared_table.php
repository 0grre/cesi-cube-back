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
        Schema::create('shared', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id')->unsigned();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->unsignedBigInteger('relation_type_id')->unsigned();
            $table->foreign('relation_type_id')->references('id')->on('relation_types')->onDelete('cascade');
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
        Schema::dropIfExists('shared');
    }
};
