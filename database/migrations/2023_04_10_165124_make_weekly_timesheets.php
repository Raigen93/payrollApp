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
        
        Schema::create('weeklies', function (Blueprint $table) {
            $table->id();
        $table->foreignId('user_id')->constrained();
        $table->string('employee_first');
        $table->string('employee_last');
        $table->string('monday')->default(0);
        $table->string('tuesday')->default(0);
        $table->string('wednesday')->default(0);
        $table->string('thursday')->default(0);
        $table->string('friday')->default(0);
        $table->string('saturday')->default(0);
        $table->string('sunday')->default(0);
        $table->decimal('total_hours');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
