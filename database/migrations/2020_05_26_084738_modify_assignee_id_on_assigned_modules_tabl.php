<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAssigneeIdOnAssignedModulesTabl extends Migration
{
    public function up()
    {
        Schema::table('assigned_modules', function (Blueprint $table) {
            $table->dropForeign(['assignee_id']);

            $table->foreign('assignee_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}
