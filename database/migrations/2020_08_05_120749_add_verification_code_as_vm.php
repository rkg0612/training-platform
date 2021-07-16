<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationCodeAsVm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voice_mails', function (Blueprint $table) {
            DB::table('voice_mails')->insert(
                [
                    'name' => 'Verification Code',
                    'value' => 'verification-code',
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voice_mails', function (Blueprint $table) {
            //
        });
    }
}
