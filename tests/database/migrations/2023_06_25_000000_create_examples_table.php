<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('examples', function (Blueprint $table) {
            $table->id();
            $table->string('role')->nullable();
            $table->unsignedInteger('status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examples');
    }
};
