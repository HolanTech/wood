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
        Schema::create('juals', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal');
            $table->string('po_in_id');
            $table->string('po_out_id');
            $table->string('customor_id');
            $table->string('order');
            $table->string('qty');
            $table->string('harga');
            $table->string('yatim');
            $table->string('transport');
            $table->string('laba');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juals');
    }
};
