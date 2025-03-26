<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_read')->default(false);
            $table->string('notification_type')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')
                  ->references('id')
                  ->on('tickets')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_notifications');
    }
}
