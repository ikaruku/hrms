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
        //
        Schema::create('empltable', function (Blueprint $table) {
            $table->id();
            $table->string('emplname');
            $table->string('address');
            $table->string('emplpicture');
            $table->date('joindate');
            $table->date('permanentdate');
            $table->date('leavedate');
            $table->string('deptid');
            $table->string('positionid');
            $table->string('email');
            $table->string('phonenumber');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empltable');
        //
    }
};
