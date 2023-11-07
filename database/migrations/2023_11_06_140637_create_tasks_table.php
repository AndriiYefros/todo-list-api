<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['todo', 'done']);
            $table->tinyInteger('priority', false, true);
            $table->string('title');
            $table->text('description');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('parent_id');
            $table->index('user_id');
            $table->index('priority');

            $table->fullText(['title', 'description']);

            $table->foreign('user_id')->references('id')->on('users');
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
