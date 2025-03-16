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
        Schema::create('resolution_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id'); // Foreign key to resolutions
            $table->enum('status', ['0', '1', '2', '3'])->default('0');
            $table->timestamps();

                // Foreign key constraint
                $table->foreign('reference_id')->references('id')->on('resolutions')->onDelete('cascade');
                       // Indexing for better performance
            $table->index('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resolution_status');
    }
};
