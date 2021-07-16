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
    </div>
    <div class="kt-portlet__body kt-margin-t-0 kt-padding-t-0">
      <v-card class="kt-widget29">
        <div class="kt-widget29__content">
          <div class="row">
            <div class="col-lg-3">
              <v-menu
                ref="rangeMenu"
                v-model="rangeMenu"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                max-width="290px"
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    :value="dateRange"
                    label="Filter Range"
                    prepend-icon="fal fa-calendar"
                    v-on="on"
                    clearable
                    @click:clear="resetForm('all')"
                    clear-icon="fal fa-times-circle"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="form.start_at"
                  range
                  locale="en-US"
                  no-title
                  color="blue"
                  value="mm-dd-YYYY"
                >
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="rangeMenu = false"
                    >Cancel</v-btn
                  >
                  <v-btn text color="primary" @click="debouncefetchData"
                    >OK</v-btn
                  >
                </v-date-picker>
              </v-menu>
            </div>
            <div class="col-lg-3">
              <v-autocomplete
                v-model="form.state_id"
                :menu-props="{ closeOnClick: true }"
                :items="filterStates"
                item-text="name"
                item-value="id"
                autocomplete="new-off"
                name="state"
                :return-object="false"
                label="Filter by State"
                :loading="filtersLoading"
                :disabled="filtersLoading"
                @click:clear="resetForm('state_id')"
                @change="debouncefetchData"
                clearable
                clear-icon="fal fa-times-circle"
              ></v-autocomplete>
            </div>
            <div class="col-lg-3">
              <v-autocomplete
                v-model="form.city_id"
                :menu-props="{ closeOnClick: true }"
                :items="filterCities"
                item-value="id"
                item-text="value"
                @change="debouncefetchData"
                @click:clear="resetForm('city_id')"
                name="city"
                :return-object="false"
                :loading="filtersLoading"
                :disabled="filtersLoading"
                label="Filter by City"
                clearable
                autocomplete="new-off"
                clear-icon="fal fa-times-circle"
              ></v-autocomplete>
            </div>
            <div class="col-lg-3">
              <v-autocomplete
                v-model="form.zipCode"
                :menu-props="{ closeOnClick: true }"
                :items="filterZipCodes"
                item-text="name"
                item-value="id"
                @change="debouncefetchData"
                @click:clear="resetForm('zipCode')"
                name="zipCode"
                :return-object="false"
                label="Filter by Zip Code"
                :loading="filtersLoading"
                :disabled="filtersLoading"
                clearable
                autocomplete="new-off"
                clear-icon="fal fa-times-circle"
              ></v-autocomplete>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3">
              <v-autocomplete
                v-model="form.is_shop_new"
                :menu-props="{ closeOnClick: true }"
                :items="shopTypes"
                item-value="value"
                item-text="type"
                name="is_shop_new"
                :return-object="false"
                label="Filter by Shop Type"
                @change="debouncefetchData"
                @click:clear="resetForm('is_shop_new')"
                :loading="filtersLoading"
                :disabled="filtersLoading"
                clearable
                autocomplete="new-off"
                clear-icon="fal fa-times-circle"
              >
              </v-autocomplete>
            </div>
            <div class="col-lg-3">
              <v-autocomplete
                v-model="form.make"
                :menu-props="{ closeOnClick: true }"
                :items="filterMake"
                label="Filter by Make"
                @change="debouncefetchData"
                @click:clear="resetForm('make')"
                clearable
                :loading="filtersLoading"
                :disabled="filtersLoading"
                autocomplete="new-off"
                clear-icon="fal fa-times-circle"
              ></v-autocomplete>
            </div>
            <div class="col-lg-3">
              <v-autocomplete
                v-model="form.model"
                :menu-props="{ closeOnClick: true }"
                :items="filterModel"
                label="Filter by Model"
                @change="debouncefetchData"
                @click:clear="resetForm('model')"
                clearable
                :loading="filtersLoading"
                :disabled="filtersLoading"
                autocomplete="new-off"
                clear-icon="fal fa-times-circle"
              ></v-autocomplete>
            </div>
            <div class="col-lg-3">
              <v-autocomplete
                v-model="form.specific_dealer_id"
                :menu-props="{ closeOnClick: true }"
                :items="filterSpecificDealers"
                item-value="id"
                item-text="name"
                @change="debouncefetchData"
                @click:clear="resetForm('specific_dealer_id')"
                multiple
                :loading="filtersLoading"
                :disabled="filtersLoading"
                label="Filter by Specific Dealer"
                clearable
                autocomplete="new-off"
                clear-icon="fal fa-times-circle"
              ></v-autocomplete>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <v-autocomplete
                v-model="form.lead_source"
                :menu-props="{ closeOnClick: true }"
                :items="filterSource"
                item-value="id"
                item-text="value"
                label="Filter by Source"
                clearable
                @change="debouncefetchData"
                @click:clear="resetForm('lead_source')"
                :loading="filtersLoading"
                :disabled="filtersLoading"
                autocomplete="new-off"
                clear-icon="fal fa-times-circle"
              ></v-autocomplete>
            </div>
            <div class="col-lg-4">
              <v-autocomplete
                v-model="form.salesperson_name"
                :menu-props="{ closeOnClick: true }"
                :items="filterSalesPerson"
                label="Filter by Salesperson"
                clearable
                @change="debouncefetchData"
                @click:clear="resetForm('salesperson_name')"
                :loading="filtersLoading"
                :disabled="filtersLoading"
                autocomplete="new-off"
                clear-icon="fal fa-times-circle"
              ></v-autocomplete>
            </div>
            <div class="col-lg-4" v-if="this.$auth.user().dealer_id === 48">
              <v-autocomplete
                v-model="form.csm_name"
                :menu-props="{ closeOnClick: true }"
                :items="filterCSM"
                label="Filter by RN"
                clearable
                @change="debouncefetchData"
                @click:clear="resetForm('csm_name')"
                :loading="filtersLoading"
                :disabled="filtersLoading"
                autocomplete="new-off"
                clear-icon="fal fa-times-circle"
              ></v-autocomplete>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div style="float: right">
                <v-btn
                  class="ma-2 white--text"
                  color="primary"
                  :loading="filtersLoading"
                  :disabled="filtersLoading"
                  @click="exportData"
                  @click.stop="warningDialog = true"
                  text
                >
                  <i class="fal fa-download mr-2 fa-2x"></i>
                  Export Shops
                </v-btn>
                <v-btn
                  class="ma-2 white--text"
                  color="grey darken-2"
                  @click="resetForm"
                  :loading="filtersLoading"
                  :disabled="filtersLoading"
                  @click.stop="warningDialog = true"
                  text
                >
                  <i class="fal fa-undo mr-2 fa-2x"></i>
                  Reset
                </v-btn>
              </div>
            </div>
          </div>
          <div class="row" v-if="dataLoading">
            <div class="col-lg-6" v-for="summary in dataLoader">
              <v-card>
                <v-card-text>
                  <h3 class="kt-widget29__title" v-text="summary.title">
                    <v-icon class="mr-1">{{ summary.icon }}</v-icon>
                  </h3>
                  <div class="kt-widget29__item">
                    <div class="kt-widget29__info">
                      <v-skeleton-loader type="table-cell"></v-skeleton-loader>
                      <v-skeleton-loader type="table-cell"></v-skeleton-loader>
                    </div>
                    <div class="kt-widget29__info">
                      <v-skeleton-loader type="table-cell"></v-skeleton-loader>
                      <v-skeleton-loader type="table-cell"></v-skeleton-loader>
                    </div>
                  </div>
                </v-card-text>
              </v-card>
            </div>
          </div>
          <div class="row" v-if="dataSummary && !dataLoading">
            <div class="col-lg-6" v-for="summary in dataSummary">
              <v-card>
                <v-card-text v-if="!form.noFilter">
                  <h3 class="kt-widget29__title" v-text="summary.title">
                    <v-icon class="mr-1">{{ summary.icon }}</v-icon>
                  </h3>
                  <div class="kt-widget29__item">
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle">
                        Top 10% Average Response Time
                      </span>
                      <span
                        class="kt-widget29__stats kt-font-success"
                        v-text="summary.data.top_response_time"
                      ></span>
                      <span class="kt-widget29__subtitle">
                        Bottom 10 Average Response Time
                      </span>
                      <span
                        class="kt-widget29__stats kt-font-success"
                        v-text="summary.data.bottom_response_time"
                      ></span>
                    </div>
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle">
                        Top 10% Average Attempts
                      </span>
                      <span
                        class="kt-widget29__stats kt-font-primary"
                        v-text="
                          summary.data.top_attempts !== 0
                            ? summary.data.top_attempts
                            : 'NA'
                        "
                      ></span>
                      <span class="kt-widget29__subtitle">
                        Bottom 10% Average Attempts
                      </span>
                      <span
                        class="kt-widget29__stats kt-font-primary"
                        v-if="isAllRecord != true"
                        v-text="
                          summary.data.bottom_attempts !== 0
                            ? summary.data.bottom_attempts
                            : 'NA'
                        "
                      ></span>
                    </div>
                  </div>
                </v-card-text>
                <v-card-text v-else>
                  <h3 class="kt-widget29__title" v-text="summary.title">
                    <v-icon class="mr-1">{{ summary.icon }}</v-icon>
                  </h3>
                  <div class="kt-widget29__item">
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle">
                        Average Response Time
                      </span>
                      <span
                        class="kt-widget29__stats kt-font-success"
                        v-text="
                          summary.data.top_response_time !== '00:00:00'
                            ? summary.data.top_response_time
                            : '--:--:--'
                        "
                      ></span>
                    </div>
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle">
                        Average Attempts
                      </span>
                      <span
                        class="kt-widget29__stats kt-font-primary"
                        v-text="
                          summary.data.top_attempts !== 0
                            ? summary.data.top_attempts
                            : 'NA'
                        "
                      ></span>
                    </div>
                  </div>
                </v-card-text>
                <v-card-text v-else>
                  <h3 class="kt-widget29__title" v-text="summary.title">
                    <v-icon class="mr-1">{{ summary.icon }}</v-icon>
                  </h3>
                  <div class="kt-widget29__item">
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle">
                        Average Response Time
                      </span>
                      <span
                        class="kt-widget29__stats kt-font-success"
                        v-text="
                          summary.data.top_response_time !== '00:00:00'
                            ? summary.data.top_response_time
                            : '--:--:--'
                        "
                      ></span>
                    </div>
                    <div class="kt-widget29__info">
                      <span class="kt-widget29__subtitle">
                        Average Attempts
                      </span>
                      <span
                        class="kt-widget29__stats kt-font-primary"
                        v-text="
                          summary.data.top_attempts !== 0
                            ? summary.data.top_attempts
                            : 'NA'
                        "
                      ></span>
                    </div>
                  </div>
                </v-card-text>
              </v-card>
            </div>
          </div>
        </div>
      </v-card>
      <v-dialog v-model="exportDialog" max-width="350">
        <v-card>
          <h1 class="m-3 p-3 text-center display-1">
            <!-- <v-icon color="yellow darken-3">
              fal fa-exclamation-triangle
            </v-icon> -->
          </h1>
          <p class="text-center subtitle-1">
            <v-progress-circular
              v-if="this.exportOnProcess"
              indeterminate
            ></v-progress-circular>
            <v-btn v-else id="downloadBtn" :href="this.exportFilepath" download
              >Download report</v-btn
            >
          </p>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red lighten-1" text @click="exportDialog = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
import {
  forEach as _forEach,
  find as _find,
  filter as _filter,
  isEmpty as _isEmpty,
} from 'lodash-es';
import secretShopReport from '../../includes/secretShopReport';

export default {
  name: 'SecretShop',
  mixins: [secretShopReport],

  data: () => ({
    rangeMenu: false,
    shopTypes: [
      {
        type: 'New',
        value: 1,
      },
      {
        type: 'Used',
        value: 0,
      },
    ],

    filterStates: [],
    filterCities: [],
    filterZipCodes: [],
    filterMake: [],
    filterModel: [],
    filterSource: [],
    filterSpecificDealers: [],
    filterSalesPerson: [],
    filterCSM: [],

    exportFilepath: '',
    exportDialog: false,
    exportOnProcess: false,
    exportShops: [],

    dataSummary: [],
    dataLoader: [
      {
        title: 'Call',
        icon: 'fal fa-phone-rotary',
      },
      {
        title: 'Email',
        icon: 'fal fa-envelope',
      },
      {
        title: 'Chat',
        icon: 'fal fa-comment-dots',
      },
      {
        title: 'SMS',
        icon: 'fal fa-sms',
      },
    ],
    dataLoading: true,

    form: {
      noFilter: true,
      start_at: [],
      city_id: '',
      state_id: '',
      zipCode: '',
      is_shop_new: '',
      make: '',
      model: '',
      specific_dealer_id: [],
      lead_source: '',
      source: '',
      salesperson_name: '',
      csm: '',
    },
    displaySummary: false,
    isFiltered: false,
    isClear: false,
    isAllRecord: false,
    debounce: null,
    filtersLoading: false,
  }),

  created() {
    this.fetchFilters();
  },

  methods: {
    //NEW FILTERS AND FETCH DATA

    debouncefetchData() {
      if (this.debounce !== null) clearTimeout(this.debounce);
      console.log('test');
      let $ = this;
      this.debounce = setTimeout(function () {
        $.dataLoading = true;
        $.form.noFilter = false;
        $.fetchFilters();
      }, 1000);
    },
    fetchFilters() {
      this.filtersLoading = true;
      this.axios
        .post('/secret-shop-data-summary/fetchFilters', this.form)
        .then(({ data }) => {
          this.filterStates = data.state;
          this.filterCities = data.city;
          this.filterZipCodes = data.zip_codes;
          this.filterMake = data.makeAndModel.make;
          this.filterModel = data.makeAndModel.model;
          this.filterSource = data.lead_source;
          this.filterSpecificDealers = data.specific_dealers;
          this.filterSalesPerson = data.sales_people;
          this.filterCSM = data.csm;
        })
        .catch((error) => {
          console.log('fetching filters got an error!');
        })
        .finally(() => {
          this.fetchData();
          this.filtersLoading = false;
        });
    },
    fetchData() {
      this.dataLoading = true;
      this.axios
        .post('/secret-shop-data-summary/fetchData', this.form)
        .then(({ data }) => {
          console.log(data);
          this.dataSummary = data.datasummary;
          this.exportShops = data.internetshops;
          if (data.internetshops.length) this.exportFilepath = '';
          this.dataLoading = false;
        })
        .catch((error) => {
          this.$notify('error', 'No record found!', true);
        });
    },

    exportData() {
      this.exportDialog = true;
      if (this.exportShops.length && this.exportFilepath == '') {
        this.dataLoading = true;
        this.exportOnProcess = true;
        this.axios
          .post('/secret-shop-data-summary/exportData', {
            internetshops: this.exportShops,
          })
          .then(({ data }) => {
            console.log(data);
            if (data.success) this.exportFilepath = data.filepath;
          })
          .catch((error) => {
            this.$notify('error', 'No record found!', true);
          })
          .finally((response) => {
            this.dataLoading = false;
            this.exportOnProcess = false;
          });
      }
    },

    resetForm(key = null) {
      this.filtersLoading = true;
      console.log(key);
      if (typeof key === 'string') {
        this.form[key] = '';
      } else {
        this.form = {
          noFilter: true,
          start_at: [],
          city_id: '',
          state_id: '',
          zipCode: '',
          is_shop_new: '',
          make: '',
          model: '',
          specific_dealer_id: '',
          lead_source: '',
          source: '',
          salesperson_name: '',
          csm: '',
        };
      }
      this.fetchFilters();
      this.filtersLoading = false;
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
</style>
