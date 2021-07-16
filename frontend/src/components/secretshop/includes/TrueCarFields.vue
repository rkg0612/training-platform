<template>
  <div>
    <v-row>
      <v-col cols="12" sm="12" md="6">
        <v-text-field
          name="shop.csmName"
          v-model="shop.csm_name"
          label="RN's Name"
          @change="changeVal"
          autocomplete="new-password"
        />
      </v-col>
      <v-col cols="12" sm="12" md="6">
        <v-text-field
          name="shop.field_leader_name"
          v-model="shop.field_leader_name"
          @change="changeVal"
          label="Field Leader's Name"
          autocomplete="new-password"
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" sm="12" md="6">
        <v-datetime-picker
          v-validate="'required'"
          name="date_time_followup"
          label="Date and time of followup call"
          v-model="shop.date_time_followup"
          okText="SET"
          dateIcon="fal fa-calendar-alt"
          timeIcon="fal fa-clock"
          date-format="MM-dd-yyyy"
          time-format="hh:mm a"
          ref="date_time_followup"
          @change="changeVal"
        >
          <template slot="dateIcon">
            <i class="fal fa-calendar-alt"></i>
          </template>
          <template slot="timeIcon">
            <i class="fal fa-clock"></i>
          </template>
        </v-datetime-picker>
      </v-col>
      <v-col cols="12" sm="12" md="6">
        <v-file-input
          label="Recording"
          ref="tcrecording"
          v-model="shopRecording"
          name="tcrecording"
          accept="audio/*"
          @change="handleRecording"
        />
      </v-col>
    </v-row>
    <v-row v-if="shop.recording && shop.id !== undefined">
      <v-col cols="12" sm="12" md="6"> </v-col>
      <v-col cols="12" sm="12" md="6">
        <h6>Current Recording</h6>
        <audio
          :src="`https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/internetshop-recordings/${shop.recording}`"
          ref="audio"
          controls
        ></audio>
      </v-col>
    </v-row>
    <h5>Information for Basic Package</h5>
    <v-row>
      <v-col cols="12" sm="12" md="4">
        <v-checkbox
          v-model="shop.bs_honor_payments_tcdc"
          @change="changeVal"
          label="Did the dealer honor the payments on TCDC?"
        ></v-checkbox>
      </v-col>
      <v-col cols="12" sm="12" md="4">
        <v-checkbox
          v-model="shop.bs_send_picvid"
          @change="changeVal"
          label="Did the dealer send a picture/video of the vehicle?"
        ></v-checkbox>
      </v-col>
      <v-col cols="12" sm="12" md="4">
        <v-checkbox
          v-model="shop.bs_mention_ts_voicemail"
          @change="changeVal"
          label="Did the dealer mention the trusted source in the voicemail?"
        ></v-checkbox>
      </v-col>
      <v-col cols="12" sm="12" md="4">
        <v-checkbox
          v-model="shop.bs_mention_ts_email"
          @change="changeVal"
          label="Did the dealer mention the trusted source in the email?"
        ></v-checkbox>
      </v-col>
      <v-col cols="12" sm="12" md="4">
        <v-checkbox
          v-model="shop.bs_mention_ts_text"
          @change="changeVal"
          label="Did the dealer mention the trusted source in the text?"
        ></v-checkbox>
      </v-col>
      <v-col cols="12" sm="12" md="4">
        <v-checkbox
          v-model="shop.bs_mention_ts_video"
          @change="changeVal"
          label="Did the dealer mention the trusted source in the video?"
        ></v-checkbox>
      </v-col>
      <v-col cols="12" sm="12" md="4">
        <v-checkbox
          v-model="shop.bs_attempt_appointment"
          @change="changeVal"
          label="Was there an attempt to schedule an appointment?"
        ></v-checkbox>
      </v-col>
      <v-col cols="12" sm="12" md="4">
        <v-checkbox
          v-model="shop.bs_home_delivery_offer"
          @change="changeVal"
          label="Was home delivery offered to the customer?"
        ></v-checkbox>
      </v-col>
    </v-row>
    <h5>Recommendation for Basic Package</h5>
    <v-row>
      <v-col cols="12" sm="12" md="12">
        <v-textarea
          v-model="shop.recommendation_email"
          @change="changeVal"
          label="Email"
        >
        </v-textarea>
      </v-col>
      <v-col cols="12" sm="12" md="12">
        <v-textarea
          v-model="shop.recommendation_call"
          @change="changeVal"
          label="Call"
        >
        </v-textarea>
      </v-col>
      <v-col cols="12" sm="12" md="12">
        <v-textarea
          v-model="shop.recommendation_sms"
          @change="changeVal"
          label="SMS"
        >
        </v-textarea>
      </v-col>
      <v-col cols="12" sm="12" md="12">
        <v-textarea
          v-model="shop.general_note"
          @change="changeVal"
          label="General Note"
        >
        </v-textarea>
      </v-col>
    </v-row>
    <h5>Information for Specialty Package</h5>
    <v-row>
      <v-col cols="12" sm="12" md="12">
        <v-select
          v-model="shop.shop_type"
          :items="truecarShopTypes"
          item-text="text"
          item-value="value"
          @change="changeVal"
          label="Type of Speciality Package"
        ></v-select>
      </v-col>
    </v-row>
    <div v-if="shop.shop_type">
      <v-row v-if="shop.shop_type == 'text_only'">
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.to_meet_char_limits"
            @change="changeVal"
            label="Were the texts 135 characters/22 words or less?"
          ></v-checkbox>
        </v-col>
      </v-row>
      <v-row v-if="shop.shop_type == 'access_base'">
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.ab_provide_payment"
            @change="changeVal"
            label="Did the dealer provide payments based on adjusting the total down? (Script 1)"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.ab_honor_offer_tcdc"
            @change="changeVal"
            label="Did the dealer honor the TrueCash Offer from TCDC?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.ab_manual_discount_offer"
            @change="changeVal"
            label="Is the manual discount of at least $100 being offered in the price quote?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="12">
          <v-textarea
            v-model="shop.ab_script_what_payment"
            @change="changeVal"
            label="(Script 1) What will my payment be if I put $3500 total down?"
          >
          </v-textarea>
        </v-col>
        <v-col cols="12" sm="12" md="12">
          <v-textarea
            v-model="shop.ab_script_real_payment"
            @change="changeVal"
            label="(Script 2) When I was on TrueCar, it displayed a payment of $XX with due at signing $XXXX on this car. Is this a real payment?"
          >
          </v-textarea>
        </v-col>
      </v-row>
      <v-row v-if="shop.shop_type == 'military'">
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.military_identify_rank"
            @change="changeVal"
            label="Did the dealer identify the customer by rank?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.military_thank_customer"
            @change="changeVal"
            label="Did the dealer thank customer for their service?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.military_benefits_explained"
            @change="changeVal"
            label="Were the military appreciation benefits explained?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="12">
          <v-textarea
            v-model="shop.military_script_benefits"
            @change="changeVal"
            label="(Script) Can you please explain the military benefits to me?"
          >
          </v-textarea>
        </v-col>
      </v-row>
      <v-row v-if="shop.shop_type == 'penfed'">
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.pf_honor_nf_policy"
            @change="changeVal"
            label="Do they honor the “no flipping” policy? (Script 1)"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.pf_contingencies"
            @change="changeVal"
            label="Were there any contingencies for bringing financing from PenFed? (If yes, input verbatim below)"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.pf_buyers_bonus"
            @change="changeVal"
            label="Did they mention Buyer’s Bonus?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.pf_manual_discount_offer"
            @change="changeVal"
            label="Is the manual discount of at least $100 being offered in the price quote?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="12">
          <v-textarea
            v-model="shop.pf_script_approved_loan"
            @change="changeVal"
            label="(Script 1) I’ve been pre-approved for a loan through PenFed. Will that be accepted for my vehicle financing?"
          >
          </v-textarea>
          <v-textarea
            v-model="shop.pf_contingencies_if_yes"
            @change="changeVal"
            label="Were there any contingencies for bringing financing from PenFed? (If yes, input verbatim)"
          >
          </v-textarea>
        </v-col>
      </v-row>
      <v-row v-if="shop.shop_type == 'amex'">
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.amex_allowed_amex"
            @change="changeVal"
            label="Allowed to put money down on AMEX card? (Script 1)"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.amex_mention_bonus"
            @change="changeVal"
            label="Did the dealer mention Buyer’s Bonus?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.amex_manual_discount_offer"
            @change="changeVal"
            label="Is the manual discount of at least $100 being offered in the price quote?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="12">
          <v-textarea
            v-model="shop.amex_script_use_amex"
            @change="changeVal"
            label="(Script 1) I see on the American Express Auto Purchasing program site that I can use my card for some of the car purchase. Can I use my AMEX Card to put money down at your dealership?"
          >
          </v-textarea>
          <v-textarea
            v-model="shop.amex_script_limit"
            @change="changeVal"
            label="(Script 2) Do you have a limit on how much I can put on the card?"
          >
          </v-textarea>
        </v-col>
      </v-row>
      <v-row v-if="shop.shop_type == 'sams_club'">
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.sc_unprompted_bonus"
            @change="changeVal"
            label="Did they mention unprompted Buyer’s Bonus or any potential post-sale benefits, e.g. Pandora gift card?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.sc_bridge_rel_gap"
            @change="changeVal"
            label="Did dealer mention they were a Sam’s Club member themselves to bridge relationship gap on introductory email/call?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.sc_partners"
            @change="changeVal"
            label="Did they mention any other partners they work with TrueCar on during the conversation?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.sc_manual_discount_offer"
            @change="changeVal"
            label="Is the manual discount of at least $100 being offered in the price quote?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.sc_didnt_know_answer"
            @change="changeVal"
            label="Did dealer say at any point if they didn’t know the answer to ask the question at their local Sam’s Club store?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="12">
          <v-textarea
            v-model="shop.sc_script_costco"
            @change="changeVal"
            label="(Script 1) I have a Sam’s Club and Costco membership, can I get a better deal going through Costco and you?"
          >
          </v-textarea>
          <v-textarea
            v-model="shop.sc_script_pandora"
            @change="changeVal"
            label="(Script 2) I saw on the Sam’s Club website that you are doing something with Pandora. Can you tell me more about that?"
          >
          </v-textarea>
          <v-textarea
            v-model="shop.sc_script_what_payment"
            @change="changeVal"
            label="(Script 3) What’s my payment if I put $3500 total down?"
          >
          </v-textarea>
        </v-col>
      </v-row>
      <v-row v-if="shop.shop_type == 'consumer_reports'">
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.cr_comments"
            @change="changeVal"
            label="Did the dealer mention any comments about Consumer Reports and their reviews/recommendations of vehicles?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.cr_bridge_rel_gap"
            @change="changeVal"
            label="Did the dealer mention they were a Consumer Reports member themselves to bridge relationship gap on introductory email/call?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.cr_appointment_attempt"
            @change="changeVal"
            label="Was there an appointment schedule attempt?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.cr_home_delivery"
            @change="changeVal"
            label="Was there a home delivery offer?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.cr_partners"
            @change="changeVal"
            label="Did they mention any other partners they work with TrueCar on during the conversation?"
          ></v-checkbox>
          <span class="kt-font-primary"> </span>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.cr_manual_discount_offer"
            @change="changeVal"
            label="Is the manual discount of at least $100 being offered in the price quote?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="4">
          <v-checkbox
            v-model="shop.cr_didnt_know_answer"
            @change="changeVal"
            label="Did the dealer say at any point if they didn’t know the answer to ask the question through Consumer Reports’ website?"
          ></v-checkbox>
        </v-col>
        <v-col cols="12" sm="12" md="12">
          <v-textarea
            v-model="shop.cr_script_my_products_web"
            @change="changeVal"
            label="(Script 1) When I buy my car from you, will it appear within the “My Products” area on the website?"
          >
          </v-textarea>
          <v-textarea
            v-model="shop.cr_script_what_payment"
            @change="changeVal"
            label="(Script 2) What’s my payment if I put $3500 total down?"
          >
          </v-textarea>
        </v-col>
      </v-row>
    </div>
  </div>
</template>
<script>
import { map as _map } from 'lodash-es';

export default {
  name: 'TrueCarFields',
  props: {
    shop: Object,
  },
  data: () => ({
    recording: [],
    isTruecar: true,
    truecarShopTypes: [
      {
        text: 'Text Only',
        value: 'text_only',
      },
      {
        text: 'Access Base',
        value: 'access_base',
      },
      {
        text: 'Military',
        value: 'military',
      },
      {
        text: 'PenFed',
        value: 'penfed',
      },
      {
        text: 'AMEX',
        value: 'amex',
      },
      {
        text: "Sam's Club",
        value: 'sams_club',
      },
      {
        text: 'Consumer Reports',
        value: 'consumer_reports',
      },
    ],
    choices: [
      {
        text: 'Yes',
        value: 1,
      },
      {
        text: 'No',
        value: 0,
      },
    ],
    shopRecording: [],
  }),

  computed: {},

  methods: {
    changeVal() {
      this.$emit('update-tc-fields', this.shop);
    },
    handleRecording() {
      this.shop.recording = this.shopRecording;
      this.changeVal();
    },
  },
};
</script>
<style lang="stylus" scoped>
kt-font-primary {
  width: 400px;
  height: 400px;
}
</style>
