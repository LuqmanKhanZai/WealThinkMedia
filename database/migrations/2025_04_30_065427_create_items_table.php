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
        if(Schema::hasTable('items')) return;
        Schema::create('items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('item_name');
            $table->string('price')->nullable();
            $table->longText('description')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('item_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
