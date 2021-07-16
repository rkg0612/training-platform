<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-watch"></i>
        </span>
        <h3 class="kt-portlet__head-title">Response Times</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-actions">
          <template v-if="authenticated">
            <v-btn
              color="light-blue darken-3"
              class="ma-2 white--text"
              @click="exportGroupShop"
              text
              small
            >
              <v-icon right dark class="m-2"> fal fa-download </v-icon>
              Export
            </v-btn>
            <v-btn
              color="light-blue darken-3"
              class="ma-2 white--text"
              @click.stop="showReportModal = true"
              text
              small
            >
              <v-icon right dark class="m-2"> fal fa-paper-plane </v-icon>
              Send Report
            </v-btn>
          </template>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <group-shop-send-report-modal v-model="showReportModal" />
      <v-card>
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Name of Dealer</th>
                <th class="text-center">First Email Response Time</th>
                <th class="text-center" style="display: none">
                  Second Email Response Time
                </th>
                <th class="text-center">Call Response Time</th>
                <th class="text-center">SMS Response Time</th>
                <!--                <th class="text-center">Chat Response Time</th>-->
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in internetShops" :key="item.id">
                <td>
                  <span v-if="item.is_dealer">
                    {{
                      hideDealerName
                        ? `Dealer ${index + 1}`
                        : item.specific_dealer.name
                    }}</span
                  >
                  <span v-else>{{
                    hideDealerName
                      ? `Competitor ${index + 1}`
                      : item.competitor.name
                  }}</span>
                </td>
                <td class="text-center">
                  {{
                    item.email_response_time
                      ? item.email_response_time
                      : '-- : -- : --'
                  }}
                </td>
                <!--                <td class="text-center">-->
                <!--                  {{-->
                <!--                    item.email_second_response_time-->
                <!--                      ? item.email_second_response_time-->
                <!--                      : '-- : -- : --'-->
                <!--                  }}-->
                <!--                </td>-->
                <td class="text-center">
                  {{
                    item.call_response_time
                      ? item.call_response_time
                      : '-- : -- : --'
                  }}
                </td>
                <td class="text-center">
                  {{
                    item.sms_response_time
                      ? item.sms_response_time
                      : '-- : -- : --'
                  }}
                </td>
                <!--                <td class="text-center">-->
                <!--                  {{-->
                <!--                    item.chat_response_time-->
                <!--                      ? item.chat_response_time-->
                <!--                      : '-- : -- : --' -->
                <!--                  }}-->
                <!--                </td>-->
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card>
    </div>
  </div>
</template>
<script>
import GroupShopSendReportModal from '@/components/client-shops/group-shop/GroupShopSendReportModal.vue';

export default {
  name: 'ResponseTime',

  props: {
    internetShops: {
      required: true,
      type: Array,
    },
    hideDealerName: {
      default: false,
    },
  },

  components: {
    GroupShopSendReportModal,
  },

  computed: {
    authenticated() {
      return this.$auth.check();
    },
  },

  data() {
    return {
      showReportModal: false,
      downloadReportLoading: false,
    };
  },

  methods: {
    exportGroupShop() {
      this.downloadReportLoading = true;
      const { id } = this.$route.params;
      this.axios
        .get(`/secret-shop-management/export/group-shop/${id}`)
        .then(({ data }) => {
          window.open(data, '_blank');
          this.downloadReportLoading = false;
        })
        .catch(() => {
          this.downloadReportLoading = false;
        });
    },
  },
};
</script>
