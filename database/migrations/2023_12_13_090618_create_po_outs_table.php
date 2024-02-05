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
        Schema::create('po_outs', function (Blueprint $table) {
            $table->string('po_in')->primary();
            $table->string('pengrajin_id');
            $table->string('order');
            $table->string('qty');
            $table->string('harga');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_outs');
    }
};
