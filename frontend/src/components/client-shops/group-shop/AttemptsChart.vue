<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-chart-line"></i>
        </span>
        <h3 class="kt-portlet__head-title">Attempts</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-card>
        <GChart
          class="m-3"
          :settings="{ packages: ['bar'] }"
          :data="chartData"
          :options="chartOptions"
          :createChart="(el, google) => new google.charts.Bar(el)"
          @ready="onChartReady"
        >
        </GChart>
      </v-card>
    </div>
  </div>
</template>
<script>
import { forEach as _forEach } from 'lodash-es';

export default {
  name: 'AttemptsChart',
  props: {
    internetShops: {
      required: true,
      type: Array,
    },
    hideDealerName: {
      default: false,
    },
  },

  data() {
    return {
      chartsLib: null,
      chartData: [
        ['Dealer', 'Email Attempts', 'Call Attempts', 'SMS Attempts'],
      ],
    };
  },

  created() {
    if (
      this.$route.name === 'GroupShop' ||
      this.$route.name === 'GroupShopGuestView'
    ) {
      _forEach(this.internetShops, (shop, index) => {
        const data = [
          this.dealerName(shop, index),
          shop.email_attempts || 0,
          shop.call_attempts || 0,
          shop.sms_attempts || 0,
        ];
        this.chartData.push(data);
      });
    }
  },
  computed: {
    chartOptions() {
      if (!this.chartsLib) return null;
      return this.chartsLib.charts.Bar.convertOptions({
        chart: {
          title: 'Total Attempts',
        },
        bars: 'vertical',
        hAxis: { format: 'decimal' },
        height: 400,
        colors: ['#0277BD'],
      });
    },
  },

  methods: {
    dealerName(shop, index) {
      if (!shop.is_dealer) {
        return this.hideDealerName
          ? `Competitor ${index + 1}`
          : shop.competitor.name;
      }
      return this.hideDealerName
        ? `Dealer ${index + 1}`
        : shop.specific_dealer.name;
    },
    onChartReady(chart, google) {
      this.chartsLib = google;
    },
  },
};
</script>
