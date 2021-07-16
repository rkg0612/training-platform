<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrueCarFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('true_car_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('internet_shop_id');
            $table->string('field_leader_name')->nullable();
            $table->longText('recommendation_email')->nullable();
            $table->longText('recommendation_call')->nullable();
            $table->longText('recommendation_sms')->nullable();
            $table->string('shop_type')->nullable();
            //Basic Shop
            $table->boolean('bs_honor_payments_tcdc')->nullable();
            $table->boolean('bs_send_picvid')->nullable();
            $table->boolean('bs_mention_ts_voicemail')->nullable();
            $table->boolean('bs_mention_ts_email')->nullable();
            $table->boolean('bs_mention_ts_text')->nullable();
            $table->boolean('bs_mention_ts_video')->nullable();
            $table->boolean('bs_attempt_appointment')->nullable();
            $table->boolean('bs_home_delivery_offer')->nullable();
            //Text Only
            $table->boolean('to_meet_char_limits')->nullable();
            //Access BAse
            $table->boolean('ab_provide_payment')->nullable();
            $table->boolean('ab_honor_offer_tcdc')->nullable();
            $table->boolean('ab_manual_discount_offer')->nullable();
            $table->longText('ab_script_what_payment')->nullable();
            //Military
            $table->boolean('military_identify_rank')->nullable();
            $table->boolean('military_thank_customer')->nullable();
            $table->boolean('military_benefits_explained')->nullable();
            $table->longText('military_script_benefits')->nullable();
            //PenFed
            $table->boolean('pf_honor_nf_policy')->nullable();
            $table->boolean('pf_contingencies')->nullable();
            $table->longText('pf_contingencies_if_yes')->nullable();
            $table->boolean('pf_buyers_bonus')->nullable();
            $table->boolean('pf_manual_discount_offer')->nullable();
            $table->longText('pf_script_approved_loan')->nullable();
            //AMEX
            $table->boolean('amex_allowed_amex')->nullable();
            $table->boolean('amex_mention_bonus')->nullable();
            $table->boolean('amex_manual_discount_offer')->nullable();
            $table->longText('amex_script_use_amex')->nullable();
            $table->longText('amex_script_limit')->nullable();
            //Sams Club
            $table->boolean('sc_unprompted_bonus')->nullable();
            $table->boolean('sc_bridge_rel_gap')->nullable();
            $table->boolean('sc_partners')->nullable();
            $table->boolean('sc_manual_discount_offer')->nullable();
            $table->boolean('sc_didnt_know_answer')->nullable();
            $table->longText('sc_script_costco')->nullable();
            $table->longText('sc_script_pandora')->nullable();
            $table->longText('sc_script_what_payment')->nullable();
            //Consumer Report
            $table->boolean('cr_comments')->nullable();
            $table->boolean('cr_bridge_rel_gap')->nullable();
            $table->boolean('cr_appointment_attempt')->nullable();
            $table->boolean('cr_home_delivery')->nullable();
            $table->boolean('cr_partners')->nullable();
            $table->boolean('cr_manual_discount_offer')->nullable();
            $table->boolean('cr_didnt_know_answer')->nullable();
            $table->longText('cr_script_my_products_web')->nullable();
            $table->longText('cr_script_what_payment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('true_car_fields');
    }
}
