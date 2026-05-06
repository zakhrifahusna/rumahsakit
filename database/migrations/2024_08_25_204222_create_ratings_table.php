<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokter_id')->constrained('dokter')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->foreignId('antrian_id')->constrained('antrian')->onDelete('cascade');
            $table->integer('rating')->comment('Rating antara 1 sampai 5');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating');
    }
};
