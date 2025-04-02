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
        Schema::table('tickets', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('assigned_user_id');
            $table->unsignedBigInteger('closed_by_id')->nullable()->after('supervisor_id');
            $table->timestamp('closed_at')->nullable()->after('updated_at');
            $table->timestamp('sla_due_at')->nullable()->after('severity');

            // Foreign key constraints ( `users`)
            $table->foreign('supervisor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('closed_by_id')->references('id')->on('users')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
            $table->dropForeign(['supervisor_id']);
            $table->dropForeign(['closed_by_id']);
            $table->dropColumn(['supervisor_id', 'closed_by_id', 'closed_at']);
        });
    }
};
