<template>
  <div
    class="kt-portlet kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid"
  >
    <div class="kt-portlet__head kt-portlet__head--noborder">
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-chart-bar"></i>
        </span>
        <h3 class="kt-portlet__head-title">Secret Shop Data Summary</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-actions">
          <v-btn
            color="light-blue darken-3"
            class="ma-2 white--text"
            @click.stop="showReportModal = true"
            v-if="$auth.check()"
            text
          >
            <v-icon right dark class="m-2"> fal fa-paper-plane </v-icon>
            Send Report
          </v-btn>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body kt-margin-t-0 kt-padding-t-0">
      <report-modal v-model="showReportModal" />
      <!--begin::Widget 29-->
      <v-card class="kt-widget29">
        <div class="kt-widget29__content">
          <h3 class="kt-widget29__title">
            {{
              internetShop.is_dealer
                ? internetShop.specific_dealer
                  ? internetShop.specific_dealer.name
                  : 'No Record'
                : internetShop.competitor
                ? internetShop.competitor.name
                : 'No Record'
            }}
          </h3>
          <span class="heading-result">
            Shopped Date
            {{
              internetShop.start_at | displayDateTime(internetShop.timezone, 1)
            }}
          </span>
          <div class="row">
            <div class="col-lg-6">
              <v-card>
                <v-card-text>
                  <h3 class="kt-widget29__title">
                    <v-icon class="mr-1"> fal fa-envelope </v-icon>
                    Email
                  </h3>
                  <div class="kt-widget29__item">
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle">
                        1st Reponse Time
                      </span>
                      <span
                        class="kt-widget29__stats green-text"
                        :class="
                          colorCodeResponseTime(
                            internetShop.email_response_time,
                            'email'
                          )
                        "
                      >
                        {{
                          internetShop.email_response_time
                            ? internetShop.email_response_time
                            : '--:--'
                        }}
                      </span>
                    </div>
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle">
                        2nd Reponse Time
                      </span>
                      <span
                        class="kt-widget29__stats green-text"
                        :class="
                          colorCodeResponseTime(
                            internetShop.email_second_response_time,
                            'email'
                          )
                        "
                      >
                        {{
                          internetShop.email_second_response_time
                            ? internetShop.email_second_response_time
                            : '--:--'
                        }}
                      </span>
                    </div>
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle text-center">
                        Attempts
                      </span>
                      <span
                        class="kt-widget29__stats green-text text-center"
                        :class="
                          colorCodeAttempts(
                            internetShop.email_attempts,
                            'email'
                          )
                        "
                      >
                        {{
                          internetShop.email_attempts &&
                          0 !== internetShop.email_attempts
                            ? internetShop.email_attempts
                            : 'NA'
                        }}
                      </span>
                    </div>
                  </div>
                </v-card-text>
              </v-card>
            </div>
            <div class="col-lg-6">
              <v-card>
                <v-card-text>
                  <h3 class="kt-widget29__title">
                    <v-icon class="mr-1"> fal fa-phone-rotary </v-icon>
                    Call
                  </h3>
                  <div class="kt-widget29__item">
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle"> Reponse Time </span>
                      <span
                        class="kt-widget29__stats"
                        :class="
                          colorCodeResponseTime(
                            internetShop.call_response_time,
                            'call'
                          )
                        "
                      >
                        {{
                          internetShop.call_response_time
                            ? internetShop.call_response_time
                            : '--:--'
                        }}
                      </span>
                    </div>
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle text-center">
                        Attempts
                      </span>
                      <span
                        class="kt-widget29__stats text-center"
                        :class="
                          colorCodeAttempts(internetShop.call_attempts, 'call')
                        "
                      >
                        {{
                          internetShop.call_attempts &&
                          0 !== internetShop.call_attempts
                            ? internetShop.call_attempts
                            : 'NA'
                        }}
                      </span>
                    </div>
                  </div>
                </v-card-text>
              </v-card>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <v-card>
                <v-card-text>
                  <h3 class="kt-widget29__title">
                    <v-icon class="mr-1">fal fa-sms</v-icon>
                    SMS
                  </h3>
                  <div class="kt-widget29__item">
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle"> Reponse Time </span>
                      <span
                        class="kt-widget29__stats"
                        :class="
                          colorCodeResponseTime(
                            internetShop.sms_response_time,
                            'sms'
                          )
                        "
                      >
                        {{
                          internetShop.sms_response_time
                            ? internetShop.sms_response_time
                            : '--:--'
                        }}
                      </span>
                    </div>
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle text-center">
                        Attempts
                      </span>
                      <span
                        class="kt-widget29__stats text-center"
                        :class="
                          colorCodeAttempts(internetShop.sms_attempts, 'sms')
                        "
                      >
                        {{
                          internetShop.sms_attempts &&
                          0 !== internetShop.sms_attempts
                            ? internetShop.sms_attempts
                            : 'NA'
                        }}
                      </span>
                    </div>
                  </div>
                </v-card-text>
              </v-card>
            </div>
            <div class="col-lg-6">
              <!-- <v-card>
                <v-card-text>
                  <h3 class="kt-widget29__title">
                    <v-icon class="mr-1"> fal fa-comment-dots </v-icon>
                    Chat
                  </h3>
                  <div class="kt-widget29__item">
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle"> Reponse Time </span>
                      <span class="kt-widget29__stats red-text">
                        {{ internetShop.chat_response_time }}
                      </span>
                    </div>
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle text-center">
                        Attempts
                      </span>
                      <span class="kt-widget29__stats red-text text-center">
                        {{ internetShop.chat_attempts }}
                      </span>
                    </div>
                  </div>
                </v-card-text>
              </v-card> -->
            </div>
          </div>
        </div>
      </v-card>
      <!--end::Widget 29-->
    </div>
  </div>
</template>
<script>
import ReportModal from '../ReportModal.vue';
import { find as _find } from 'lodash-es';
export default {
  name: 'TopWidget',
  props: ['internetShop'],

  components: {
    ReportModal,
  },

  data() {
    return {
      showReportModal: false,
    };
  },

  methods: {
    getDealerOption(key) {
      if (
        this.internetShop &&
        undefined != this.internetShop.dealer.options &&
        undefined != _find(this.internetShop.dealer.options, { name: key })
      ) {
        const option = _find(this.internetShop.dealer.options, { name: key });
        return option.value;
      }

      return null;
    },
    parseResponseTimeToMinutes(val) {
      const responseTime = this.getDealerOption(val);
      if (null != responseTime) {
        if (!responseTime) {
          return false;
        }
        const formatted = responseTime.split(':');

        if (!formatted[0] || !formatted[1] || !formatted[2]) {
          return false;
        }

        const minutes =
          parseInt(formatted[0] * 60) +
          parseInt(formatted[1]) +
          parseInt(formatted[2] / 60);
        if (minutes <= 0) {
          return false;
        }

        return minutes;
      }

      return false;
    },
    colorCodeResponseTime(responseTime, type) {
      // for reference: https://prnt.sc/vbp8h7

      const yellowParsedResponseTime = this.parseResponseTimeToMinutes(
        `${type}_yellow_response_time`
      );
      const redParsedResponseTime = this.parseResponseTimeToMinutes(
        `${type}_red_response_time`
      );

      const yellow =
        false != yellowParsedResponseTime ? yellowParsedResponseTime : 5;
      const red = false != redParsedResponseTime ? redParsedResponseTime : 30;
      console.log(yellowParsedResponseTime, redParsedResponseTime);
      if (!responseTime) {
        return 'red-text';
      }

      const formatted = responseTime.split(':');

      if (!formatted[0] || !formatted[1] || !formatted[2]) {
        return 'red-text';
      }

      const minutes =
        parseInt(formatted[0] * 60) +
        parseInt(formatted[1]) +
        parseInt(formatted[2] / 60);

      if (minutes < 0) {
        return 'red-text';
      }

      if (minutes < yellow) {
        return 'green-text';
      }

      if (minutes >= yellow && minutes <= red) {
        return 'yellow-text';
      }

      if (minutes > red) {
        return 'red-text';
      }

      return 'red-text';
    },
    colorCodeAttempts(attempts, type) {
      // Email is green if 4+ attempts w/in 5 days, yellow if 2-3, and red if 0 - 1.
      // Call is green if 6+ attempts w/ in 5 days, yellow if 3 - 5 and red if 0 -2.
      // SMS is green if 6+ attempts w/ in 5 days, yellow if 3 - 5 and red if 0 -2.

      const data = attempts;
      // green = 6, yellowMin = 3, yellowMax = 5, redMin = 0, redMax = 2
      const greenAttemptOption = this.getDealerOption(`${type}_green_attempts`);
      const yellowAttemptOption = this.getDealerOption(
        `${type}_yellow_attempts`
      );
      const redAttemptOption = this.getDealerOption(`${type}_red_attempts`);

      const green = null != greenAttemptOption ? greenAttemptOption : 6;
      const yellow = null != yellowAttemptOption ? yellowAttemptOption : 5;
      const red = null != yellowAttemptOption ? yellowAttemptOption : 2;

      if (!attempts) {
        return 'red-text';
      }

      if (yellow < data) {
        return 'green-text';
      }

      if (yellow >= data && red < data) {
        return 'yellow-text';
      }

      if (red >= data) {
        return 'red-text';
      }

      return 'red-text';
    },
  },
};
</script>
<style lang="stylus" scoped>
.kt-widget29__title
  font-family Oswald

.heading-result
  color #333

.kt-portlet.kt-portlet--solid-dark
  background #333 !important

.green-text
  color #43A047


.yellow-text
  color #F9A825


.red-text
  color #C62828
</style>
