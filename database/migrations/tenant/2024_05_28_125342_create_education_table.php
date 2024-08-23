<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('department');
            $table->enum('degree', ['Açık Öğretim', 'Önlisans', 'Lisans', 'Yüksek Lisans', 'Doktora']);
            $table->enum('status', ['Mezun', 'Devam Ediyor', 'Terk']);
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
