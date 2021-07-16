<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-th-list"></i>
        </span>
        <h3 class="kt-portlet__head-title">Shop Logs</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-card>
        <v-tabs
          next-icon="fad fa-arrow-square-right"
          prev-icon="fad fa-arrow-square-left"
          show-arrows
        >
          <v-tab> Call </v-tab>
          <v-tab> Email </v-tab>
          <v-tab> SMS </v-tab>
          <!-- <v-tab> Chat </v-tab> -->
          <v-tab-item>
            <call-logs
              :shop="internetShop"
              :toggleTranscript="toggleTranscript"
            />
          </v-tab-item>
          <v-tab-item>
            <email-logs :shop="internetShop" />
          </v-tab-item>
          <v-tab-item>
            <sms-logs :shop="internetShop" />
          </v-tab-item>
          <!-- <v-tab-item>
            <chat-logs :shop="internetShop" />
          </v-tab-item> -->
        </v-tabs>
      </v-card>
    </div>
  </div>
</template>
<script>
import CallLogs from './Logs/CallLogs.vue';
import EmailLogs from './Logs/EmailLogs.vue';
import SmsLogs from './Logs/SmsLogs.vue';
import ChatLogs from './Logs/ChatLogs.vue';

export default {
  name: 'ShopLogs',

  props: {
    internetShop: {
      required: false,
      type: Object,
    },
    toggleTranscript: Function,
  },

  components: {
    CallLogs,
    EmailLogs,
    SmsLogs,
    ChatLogs,
  },
};
</script>
