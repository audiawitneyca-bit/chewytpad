<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            // Kita pakai string biasa biar simpel: 'todo', 'on_progress', 'done'
            $table->string('status')->default('todo')->after('content');
        });
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
