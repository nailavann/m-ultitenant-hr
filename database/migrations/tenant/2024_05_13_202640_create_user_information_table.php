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
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->date('birth_date')->nullable()->index();
            $table->unsignedTinyInteger('gender')->nullable()->index();
            $table->string('blood_type')->nullable()->index();
            $table->string('phone', 25)->nullable()->unique();
            $table->unsignedTinyInteger('company_id')->index();
            $table->unsignedTinyInteger('department_id')->index();
            $table->date('position_started_at')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_information');
    }
};
