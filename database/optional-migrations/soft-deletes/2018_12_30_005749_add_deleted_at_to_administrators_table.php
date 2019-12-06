<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToAdministratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (
            !Schema::hasTable('administrators') ||
            Schema::hasColumn('administrators', 'deleted_at')
        ) {
            return;
        }

        Schema::table('administrators', function (Blueprint $table) {
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('administrators')) {
            return;
        }

        Schema::table('administrators', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);

            $table->dropColumn('deleted_at');
        });
    }
}
