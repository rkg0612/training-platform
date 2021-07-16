<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeThumbnailsColumnsToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->change();
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('thumbnail')->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('thumbnail')->change();
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->string('thumbnail')->change();
        });
    }
}
