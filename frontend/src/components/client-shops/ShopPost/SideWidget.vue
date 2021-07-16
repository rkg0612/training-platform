<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head">
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-info-circle"></i>
        </span>
        <h3 class="kt-portlet__head-title">Shop Info</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <div class="kt-widget6">
        <div class="kt-widget6__body">
          <div class="kt-widget6__item">
            <span>Lead's Name</span>
            <span class="blue--text kt-font-bold">
              {{ internetShop.lead_name }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span>Lead's Email</span>
            <span class="blue--text kt-font-bold">
              {{ internetShop.lead_email }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span>Lead's Phone</span>
            <span class="blue--text kt-font-bold">
              {{ internetShop.lead_phone_number }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span> Date and Time Shopped </span>
            <span class="blue--text kt-font-bold">
              {{
                internetShop.start_at
                  | displayDateTime(internetShop.timezone, 1)
              }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span>Shop Duration</span>
            <span class="blue--text kt-font-bold">
              {{ internetShop.shop_duration }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span>Specific Dealer's Name</span>
            <span class="kt-font-bold">
              {{
                internetShop.is_dealer
                  ? internetShop.specific_dealer
                    ? internetShop.specific_dealer.name
                    : 'No Record'
                  : internetShop.competitor
                  ? internetShop.competitor.name
                  : 'No Record'
              }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span>Salesperson's Name</span>
            <span class="blue--text kt-font-bold">
              {{ internetShop.salesperson_name }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span>Lead Source</span>
            <!--Need to put source link in href attribute-->
            <a
              :href="
                internetShop.source_link
                  ? maybeAppendHttpScheme(internetShop.source_link)
                  : '#'
              "
              class="kt-font-danger kt-font-bold"
            >
              {{
                internetShop.source != undefined
                  ? internetShop.source.value
                  : 'None'
              }}
            </a>
          </div>
          <!--          <div class="kt-widget6__item">-->
          <!--            <span>Verification Screenshot</span>-->
          <!--            &lt;!&ndash;Need to put source link in href attribute&ndash;&gt;-->
          <!--            <a-->
          <!--              :href="`https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/${internetShop.id}/${internetShop.verification_screenshot}`"-->
          <!--              class="kt-font-danger kt-font-bold"-->
          <!--              target="_blank"-->
          <!--            >-->
          <!--              Click Here-->
          <!--            </a>-->
          <!--          </div>-->
          <div class="kt-widget6__item">
            <span>Make</span>
            <span class="blue--text kt-font-bold">
              {{ internetShop.make }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span>Make and Model</span>
            <span class="blue--text kt-font-bold">
              {{ internetShop.model }}
            </span>
          </div>
          <div
            v-if="internetShop.dealer_id === 48 && internetShop.truecar_fields"
          >
            <div class="kt-widget6__item">
              <span>RN's Name</span>
              <span class="blue--text kt-font-bold">
                {{ internetShop.csm_name }}
              </span>
            </div>
            <div class="kt-widget6__item">
              <span>Field Leader's Name</span>
              <span class="blue--text kt-font-bold">
                {{ internetShop.truecar_fields.field_leader_name }}
              </span>
            </div>
            <div
              class="kt-widget6__item"
              v-if="internetShop.truecar_fields.date_time_followup"
            >
              <span>Date and Time of followup</span>
              <span class="blue--text kt-font-bold">
                {{
                  internetShop.truecar_fields.date_time_followup
                    | displayDateTime(internetShop.timezone, 1)
                }}
              </span>
            </div>
            <!--            <div
              class="kt-widget6__item"
              v-if="internetShop.truecar_fields.recording"
            >
              <span>Recording</span>
              <audio
                :src="`https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/internetshop-recordings/${internetShop.truecar_fields.recording}`"
                ref="audio"
                controls
              ></audio>
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'SideWidget',
  props: {
    shop: {
      type: Object,
      required: false,
    },
  },
  computed: {
    internetShop() {
      return this.shop;
    },
  },
  methods: {
    maybeAppendHttpScheme(url) {
      if (!/^https?:\/\//i.test(url)) {
        url = 'https://' + url;
      }
      return url;
    },
  },
};
</script>
