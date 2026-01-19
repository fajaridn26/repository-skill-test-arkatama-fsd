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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('code', 20)->unique();
            $table->string('name');           
            $table->string('type');      
            $table->unsignedInteger('age');
            $table->decimal('weight', 5, 2);
            $table->unique(['owner_id', 'name', 'type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
