<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('alias', 50)->unique();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->enum('role', ['user', 'mod', 'admin'])->default('user');
            $table->text('profile_picture')->nullable();
            $table->text('bio')->nullable();
            $table->integer('reputation')->default(0);
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
