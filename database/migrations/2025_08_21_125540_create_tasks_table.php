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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
             $table->foreignId('column_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->longText('description')->nullable();
    $table->date('due_date')->nullable();
    $table->enum('status', ['draft', 'in_progress', 'done'])->default('draft');
    $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
    $table->integer('sort_order')->default(0);
    $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
