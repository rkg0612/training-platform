<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head kt-portlet__head--noborder">
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-car"></i>
        </span>
        <h3 class="kt-portlet__head-title">For TrueCar</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-expansion-panels focusable flat>
        <v-expansion-panel>
          <v-expansion-panel-header>
            <h5 class="kt-widget29__title">Qualitative Data</h5>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <div class="kt-widget6">
              <div class="kt-widget6__body">
                <div class="kt-widget6__item">
                  <span>Did the dealer honor the payments on TCDC?</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.bs_honor_payments_tcdc
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer send a picture/video of the vehicle?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.bs_send_picvid ? 'YES' : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer mention the trusted source in the
                    voicemail?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.bs_mention_ts_voicemail
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer mention the trusted source in the
                    email?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.bs_mention_ts_email
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer mention the trusted source in the
                    text?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.bs_mention_ts_text
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer mention the trusted source in the
                    video?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.bs_mention_ts_video
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>Was there an attempt to schedule an appointment?</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.bs_attempt_appointment
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>Was home delivery offered to the customer?</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.bs_home_delivery_offer
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
              </div>
            </div>
          </v-expansion-panel-content>
        </v-expansion-panel>
        <v-expansion-panel>
          <v-expansion-panel-header>
            <h5 class="kt-widget29__title">Recommendation</h5>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <div class="kt-widget6">
              <div class="kt-widget6__body">
                <div class="kt-widget6__item">
                  <span>Email</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.recommendation_email
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>Call</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.recommendation_call
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>SMS</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.recommendation_sms
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>General Note</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.general_note
                    }}</strong>
                  </span>
                </div>
              </div>
            </div>
          </v-expansion-panel-content>
        </v-expansion-panel>
        <v-expansion-panel v-if="internetShop.truecar_fields.shop_type">
          <v-expansion-panel-header>
            <h5 class="kt-widget29__title">
              {{ shopTypes[internetShop.truecar_fields.shop_type] }}
            </h5>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <div class="kt-widget6">
              <div
                v-if="internetShop.truecar_fields.shop_type == 'text_only'"
                class="kt-widget6__body"
              >
                <div class="kt-widget6__item">
                  <span>Were the texts 135 characters/22 words or less?</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.to_meet_char_limits
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
              </div>
              <div
                v-if="internetShop.truecar_fields.shop_type == 'access_base'"
                class="kt-widget6__body"
              >
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer provide payments based on adjusting the
                    total down? (Script 1)</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.ab_provide_payment
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer honor the TrueCash Offer from TCDC?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.ab_honor_offer_tcdc
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Is the manual discount of at least $100 being offered in
                    the price quote?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.ab_manual_discount_offer
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 1) What will my payment be if I put $3500 total
                    down?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.ab_script_what_payment
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 2) When I was on TrueCar, it displayed a payment of
                    $XX with due at signing $XXXX on this car. Is this a real
                    payment?" down?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.ab_script_real_payment
                    }}</strong>
                  </span>
                </div>
              </div>
              <div
                v-if="internetShop.truecar_fields.shop_type == 'military'"
                class="kt-widget6__body"
              >
                <div class="kt-widget6__item">
                  <span>Did the dealer identify the customer by rank? </span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.military_identify_rank
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>Did the dealer thank customer for their service?</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.military_thank_customer
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Were the military appreciation benefits explained?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.military_benefits_explained
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script) Can you please explain the military benefits to
                    me?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.military_script_benefits
                    }}</strong>
                  </span>
                </div>
              </div>
              <div
                v-if="internetShop.truecar_fields.shop_type == 'penfed'"
                class="kt-widget6__body"
              >
                <div class="kt-widget6__item">
                  <span
                    >Do they honor the “no flipping” policy? (Script 1)</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.pf_honor_nf_policy
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Were there any contingencies for bringing financing from
                    PenFed (e.g., dealer increased price of the vehicle since
                    they were not financing through the dealership)? Verbatim
                    Below if applicable</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.pf_contingencies
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div
                  class="kt-widget6__item"
                  v-if="internetShop.truecar_fields.pf_contingencies"
                >
                  <span>Verbatim</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.pf_contingencies_if_yes
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>Did they mention Buyer’s Bonus?</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.pf_buyers_bonus ? 'YES' : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Is the manual discount of at least $100 being offered in
                    the price quote?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.pf_manual_discount_offer
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 1) I’ve been pre-approved for a loan through
                    PenFed. Will that be accepted for my vehicle financing?
                  </span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.pf_script_approved_loan
                    }}</strong>
                  </span>
                </div>
              </div>
              <div
                v-if="internetShop.truecar_fields.shop_type == 'amex'"
                class="kt-widget6__body"
              >
                <div class="kt-widget6__item">
                  <span
                    >Allowed to put money down on AMEX card? (Script 1)</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.amex_allowed_amex
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>Did the dealer mention Buyer’s Bonus?</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.amex_mention_bonus
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Is the manual discount of at least $100 being offered in
                    the price quote?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.amex_manual_discount_offer ===
                      1
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 1) I see on the American Express Auto Purchasing
                    program site that I can use my card for some of the car
                    purchase. Can I use my AMEX Card to put money down at your
                    dealership?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.amex_script_use_amex
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 2) Do you have a limit on how much I can put on the
                    card?
                  </span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.amex_script_limit
                    }}</strong>
                  </span>
                </div>
              </div>
              <div
                v-if="internetShop.truecar_fields.shop_type == 'sams_club'"
                class="kt-widget6__body"
              >
                <div class="kt-widget6__item">
                  <span
                    >Did they mention unprompted Buyer’s Bonus or any potential
                    post-sale benefits, e.g. Pandora gift card?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.sc_unprompted_bonus
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did dealer mention they were a Sam’s Club member themselves
                    to bridge relationship gap on introductory email/call?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.sc_bridge_rel_gap
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did they mention any other partners they work with TrueCar
                    on during the conversation?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.sc_partners ? 'YES' : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Is the manual discount of at least $100 being offered in
                    the price quote?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.sc_manual_discount_offer
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did dealer say at any point if they didn’t know the answer
                    to ask the question at their local Sam’s Club store?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.sc_didnt_know_answer
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 1) I have a Sam’s Club and Costco membership, can I
                    get a better deal going through Costco and you?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.sc_script_costco
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 2) I saw on the Sam’s Club website that you are
                    doing something with Pandora. Can you tell me more about
                    that?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.sc_script_pandora
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 3) What’s my payment if I put $3500 total
                    down?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.sc_script_what_payment
                    }}</strong>
                  </span>
                </div>
              </div>
              <div
                v-if="
                  internetShop.truecar_fields.shop_type == 'consumer_reports'
                "
                class="kt-widget6__body"
              >
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer mention any comments about Consumer Reports
                    and their reviews/recommendations of vehicles?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.cr_comments ? 'YES' : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer mention they were a Consumer Reports member
                    themselves to bridge relationship gap on introductory
                    email/call?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.cr_bridge_rel_gap
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>Was there an appointment schedule attempt?</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.cr_appointment_attempt
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span>Was there a home delivery offer?</span>
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.cr_home_delivery
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did they mention any other partners they work with TrueCar
                    on during the conversation?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.cr_partners ? 'YES' : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Is the manual discount of at least $100 being offered in
                    the price quote?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.cr_manual_discount_offer
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >Did the dealer say at any point if they didn’t know the
                    answer to ask the question through Consumer Reports’
                    website?</span
                  >
                  <span class="blue--text kt-font-bold">
                    <strong>{{
                      internetShop.truecar_fields.cr_didnt_know_answer
                        ? 'YES'
                        : 'NO'
                    }}</strong>
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 1) When I buy my car from you, will it appear
                    within the “My Products” area on the website?</span
                  >
                  <span
                    class="blue--text kt-font-bold"
                    v-html="
                      internetShop.truecar_fields.cr_script_my_products_web
                    "
                  >
                  </span>
                </div>
                <div class="kt-widget6__item">
                  <span
                    >(Script 2) What’s my payment if I put $3500 total
                    down?</span
                  >
                  <span
                    class="blue--text kt-font-bold"
                    v-html="internetShop.truecar_fields.cr_script_what_payment"
                  >
                  </span>
                </div>
              </div>
            </div>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </div>
  </div>
</template>
<script>
export default {
  name: 'TrueCar',

  props: {
    internetShop: {
      required: false,
      type: Object,
    },
  },
  data() {
    return {
      shopTypes: {
        text_only: 'Text Only',
        access_base: 'Access Base',
        military: 'Military',
        penfed: 'PenFed',
        amex: 'AMEX',
        sams_club: "Sam's Club",
        consumer_reports: 'Consumer Reports',
      },
    };
  },
};
</script>
