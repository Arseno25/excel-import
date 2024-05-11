<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('excel-import', function (Blueprint $table) {
            $table->id();
            // Add your fields here
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excel-import');
    }
};