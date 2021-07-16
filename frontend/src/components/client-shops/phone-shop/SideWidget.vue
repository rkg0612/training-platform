<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head">
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-info-circle"></i>
        </span>
        <h3 class="kt-portlet__head-title">Shop Info</h3>
      </div>
      <div class="kt-portlet__head-toolbar" v-show="hideReportButton">
        <div class="kt-portlet__head-actions">
          <v-btn
            color="light-blue darken-3"
            class="ma-2 white--text"
            :disabled="loading"
            @click.stop="showReportModal = true"
            text
            small
          >
            <v-icon right dark class="m-2"> fal fa-paper-plane </v-icon>
            Send Report
          </v-btn>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <component-loader :active="loading" />
      <report-modal v-model="showReportModal" />
      <div class="kt-widget6">
        <div class="kt-widget6__body">
          <div class="kt-widget6__item">
            <span>Lead's Name</span>
            <span class="kt-font-bold">
              {{ phoneShop.lead_name }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span> Date and Time Shopped </span>
            <span class="blue--text kt-font-bold">
              {{ phoneShop.start_date | displayDateTime }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span>Specific Dealer's Name</span>
            <span class="kt-font-bold" v-if="phoneShop.specific_dealer">
              {{ phoneShop.specific_dealer.name }}
            </span>
          </div>
          <div class="kt-widget6__item">
            <span>Salesperson's Name</span>
            <span class="kt-font-bold">
              {{ phoneShop.sales_person_name }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ReportModal from '../ReportModal.vue';

export default {
  name: 'SideWidget',
  props: {
    loading: {
      required: true,
      default: false,
    },
    shopContent: {
      required: false,
      type: Object,
    },
  },

  components: {
    ReportModal,
  },

  data() {
    return {
      showReportModal: false,
    };
  },

  computed: {
    phoneShop() {
      if (
        this.$route.name === 'GroupShop' ||
        this.$route.name === 'GroupShopGuestView'
      ) {
        return this.shopContent;
      }
      return this.$store.state.phoneShops.selectedItem;
    },
    hideReportButton() {
      if (
        this.$route.name === 'GroupShop' ||
        this.$route.name.toLowerCase().includes('guest')
      ) {
        return false;
      }

      return true;
    },
  },
};
</script>
