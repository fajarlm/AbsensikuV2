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
        Schema::create('study_groups', function (Blueprint $table) {
            $table->id();
            $table->enum('class_number', ['1', '2', '3', '4', '5', '6']);
            $table->enum('grade', ['X', 'XI', 'XII'])->change();
            $table->enum('major', ['PPLG', 'TJKT', 'DKV', 'PMN', 'MPLB', 'KLN', 'HTL']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_groups');
    }
};
