<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamps();
        });
    }

    public function down(): void
    {
    // Beddel kan:


// U beddel kan:
Schema::createIfNotExists('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamps();
        });
    }
};