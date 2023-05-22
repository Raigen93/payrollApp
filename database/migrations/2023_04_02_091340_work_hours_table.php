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
        Schema::create('work', function (Blueprint $table) {
            $table->id();
            $table->string('job_name');
            $table->timestamps();
            $table->integer('budget_hours');
            $table->integer('hours_worked')->default(0);
            $table->integer('dept_hours_eng')->default(0);
            $table->integer('dept_hours_elec')->default(0);
            $table->integer('dept_hours_pipe')->default(0);
            $table->integer('dept_hours_stru')->default(0);
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
