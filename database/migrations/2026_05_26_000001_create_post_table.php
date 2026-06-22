<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('created_by');
            $table->timestamps();
            $table->string('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
