<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-chart-line"></i>
        </span>
        <h3 class="kt-portlet__head-title">
          Compare Dealers Performance
          <small>
            Use the filters below to select the data you want to compare.
          </small>
        </h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <div class="row">
        <div class="col-lg-4">
          <v-menu
            ref="rangeMenu"
            v-model="rangeMenu"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            max-width="290px"
            :disabled="loading.fetchAttempts"
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="rangeFilter"
                label="Filter Range"
                prepend-icon="fal fa-calendar"
                @click:clear="resetDateField"
                clearable
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="form.date"
              range
              locale="en-US"
              no-title
              color="blue"
              value="mm-dd-YYYY"
            >
              <v-spacer></v-spacer>
              <v-btn text color="primary" @click="rangeMenu = false">
                Cancel
              </v-btn>
              <v-btn
                text
                color="primary"
                @click="fetchSpecificDealersOnSpecificDate"
              >
                OK
              </v-btn>
            </v-date-picker>
          </v-menu>
        </div>
        <div class="col-lg-4">
          <v-autocomplete
            label="Select a Dealer"
            :items="dealersOne"
            :disabled="disabled.fetchingSpecificDealer"
            :loading="loading.fetchDealers"
            v-model="form.dealerOne"
            item-value="id"
            item-text="name"
            v-validate="'required'"
            name="dealer_one"
            clearable
            @click:clear="form.dealerOne === null"
          ></v-autocomplete>
          <span class="kt-font-danger validate-error">
            {{ errors.first('dealer_one') }}
          </span>
        </div>
        <div class="col-lg-4">
          <v-autocomplete
            label="Select Another Dealer to Compare"
            :items="dealersTwo"
            :disabled="disabled.fetchingSpecificDealer"
            :loading="loading.fetchDealers"
            v-model="form.dealerTwo"
            item-value="id"
            item-text="name"
            v-validate="'required'"
            name="dealer_two"
            clearable
            @click:clear="form.dealerTwo === null"
          ></v-autocomplete>
          <span class="kt-font-danger validate-error">
            {{ errors.first('dealer_two') }}
          </span>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="float-right">
            <v-btn
              class="ma-2"
              color="primary"
              dark
              @click="fetchSummary"
              @click.stop="warningDialog = true"
              :loading="loading.fetchAttempts"
              :disabled="loading.fetchSummary"
              text
            >
              <i class="fal fa-database mr-2 fa-2x"></i>
              Show Data
            </v-btn>
            <v-btn
              class="ma-2"
              color="grey darken-3"
              dark
              @click.stop="warningDialog = true"
              @click="resetFields"
              :loading="loading.fetchAttempts"
              :disabled="loading.fetchSummary"
              text
            >
              <i class="fal fa-undo-alt mr-2 fa-2x"></i>
              Reset
            </v-btn>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <GChart
            :settings="{ packages: ['bar'] }"
            :data="chartSettings"
            :options="chartOptions"
            :createChart="(el, google) => new google.charts.Bar(el)"
            @ready="onChartReady"
          >
          </GChart>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {
  reject as _reject,
  filter as _filter,
  includes as _includes,
} from 'lodash-es';

export default {
  name: 'DealerComparison',
  data() {
    return {
      form: {
        date: ['', ''],
        dealerOne: '',
        dealerTwo: '',
      },
      defaultForm: {
        date: ['', ''],
        dealerOne: '',
        dealerTwo: '',
      },
      dateRange: '',
      rangeMenu: false,
      chartsLib: null,
      dealerList: [],
      chartSettings: [
        ['Dealer', 'Email Attempts', 'Call Attempts', 'SMS Attempts'],
      ],
      sampleValue: [
        ['Dealer A', 0, 0, 0],
        ['Dealer B', 0, 0, 0],
      ],
      loading: {
        fetchDealers: false,
        fetchAttempts: false,
      },
      disabled: {
        fetchingSpecificDealer: false,
      },
      filteredSpecificDealers: [],
    };
  },

  mounted() {
    this.chartSettings = this.chartSettings.concat(this.sampleValue);
    this.fetchDealers();
  },

  created() {
    this.$validator.localize('en', {
      attributes: {
        dealer_one: 'dealer one',
        dealer_two: 'dealer two',
      },
    });
  },

  computed: {
    rangeFilter() {
      return this.formatDateRangeForTextField(this.form.date);
    },

    chartOptions() {
      if (!this.chartsLib) return null;
      return this.chartsLib.charts.Bar.convertOptions({
        chart: {
          title: 'Total Attempts',
          subtitle: this.form.date,
        },
        bars: 'vertical',
        hAxis: { format: 'decimal' },
        height: 400,
        colors: ['#0277BD'],
      });
    },

    dealersOne() {
      let list = this.dealerList;
      if (this.form.dealerTwo) {
        const id = this.form.dealerTwo;
        list = _reject(list, { id });
      }
      if (this.filteredSpecificDealers.length) {
        list = _filter(list, (specificDealer) => {
          return _includes(this.filteredSpecificDealers, specificDealer.id);
        });
      }
      return list;
    },

    dealersTwo() {
      let list = this.dealerList;
      if (this.form.dealerOne) {
        const id = this.form.dealerOne;
        list = _reject(list, { id });
      }
      if (this.filteredSpecificDealers.length) {
        list = _filter(list, (specificDealer) => {
          return _includes(this.filteredSpecificDealers, specificDealer.id);
        });
      }
      return list;
    },
  },

  methods: {
    fetchDealers() {
      this.loading.fetchDealers = true;
      this.axios
        .get('/secret-shop-data-summary/specific-dealers')
        .then(({ data }) => {
          this.dealerList = data;
          this.loading.fetchDealers = false;
        });
    },

    onChartReady(chart, google) {
      this.chartsLib = google;
    },

    createParams() {
      const params = {};
      if (this.form.date) {
        const dateRange = this.form.date.filter(function (str) {
          return /\S/.test(str);
        });
        if (dateRange.length) {
          params.date = dateRange;
        }
      }
      params.dealerOne = this.form.dealerOne;
      params.dealerTwo = this.form.dealerTwo;
      return params;
    },

    fetchSummary() {
      const params = this.createParams();
      this.$validator.validate('*').then((result) => {
        if (result) {
          this.sendRequest(params);
        }
      });
    },

    sendRequest(params) {
      this.loading.fetchAttempts = true;
      this.resetChart();
      setTimeout(() => {
        this.axios
          .get('/secret-shop-data-summary/compare-dealers-performance', {
            params,
          })
          .then(({ data }) => {
            data.forEach((dealerResult) => {
              this.chartSettings.push(dealerResult);
            });
            this.$notify('success', 'Total Attempts fetched!');
          })
          .catch(() => {
            this.$notify('error', 'Fetch failed');
          })
          .finally(() => {
            this.loading.fetchAttempts = false;
          });
      }, 1000);
    },

    fetchSpecificDealersOnSpecificDate() {
      this.$refs.rangeMenu.save(this.form.date);
      const params = {
        start_at: this.form.date,
        dealerPerformance: true,
      };
      this.disabled.fetchingSpecificDealer = true;
      setTimeout(() => {
        this.axios
          .get('/secret-shop-data-summary/get-filtered-data', {
            params,
          })
          .then(({ data }) => {
            this.filteredSpecificDealers = data;
            this.disabled.fetchingSpecificDealer = false;
          })
          .catch((error) => {
            if (error.response.status === 404) {
              this.disabled.fetchingSpecificDealer = true;
              this.$notify('error', 'No Dealers found in the selected dates!');
            }
          });
      }, 200);
    },

    resetChart(headersOnly = true) {
      this.chartSettings = [
        ['Dealer', 'Email Attempts', 'Call Attempts', 'SMS Attempts'],
      ];
      if (!headersOnly) {
        this.chartSettings = this.chartSettings.concat(this.sampleValue);
      }
    },

    resetFields() {
      this.loading.fetchAttempts = true;
      setTimeout(() => {
        this.resetChart(false);
        this.$validator.reset('*');
        Object.assign(this.form, this.defaultForm);
        this.loading.fetchAttempts = false;
        this.disabled.fetchingSpecificDealer = false;
      }, 200);
    },

    resetDateField() {
      this.form.date = ['', ''];
      this.disabled.fetchingSpecificDealer = false;
    },
  },
};
</script>
