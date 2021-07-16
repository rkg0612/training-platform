<template>
  <div class="kt-portlet">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm flex-wrap"
    >
      <div class="kt-portlet__head-label pt-3 pt-lg-0">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-chart-line"></i>
        </span>
        <h3 class="kt-portlet__head-title">Team Standing</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group d-flex flex-wrap justify-center">
          <v-btn
            color="primary"
            dark
            @click="exportData()"
            depressed
            text
            class="mt-3 mt-md-0 mx-1"
          >
            <v-icon left>fal fa-file-export</v-icon>
            Export Data
          </v-btn>
          <!-- <v-btn
            class="mt-3 mt-md-0 mx-1"
            dark
            color="indigo"
            @click.stop="warningDialog = true"
            depressed
            small
          >
            <i class="fal fa-database mr-2"></i>
            Show Data
          </v-btn>
          <v-btn
            class="mt-3 mt-md-0 mx-1 mx-1"
            dark
            color="red darken-1"
            @click.stop="warningDialog = true"
            depressed
            small
          >
            <i class="fas fa-undo-alt mr-2"></i>
            Reset
          </v-btn> -->
          <v-dialog v-model="warningDialog" max-width="350">
            <v-card>
              <h1 class="m-3 p-3 text-center display-1">
                <!-- <v-icon color="yellow darken-3">
                  fal fa-exclamation-triangle
                </v-icon> -->
              </h1>
              <p class="text-center subtitle-1">
                <v-progress-circular
                  v-if="this.onProcess"
                  indeterminate
                ></v-progress-circular>
                <v-btn v-else id="downloadBtn" :href="this.filePath" download
                  >Download report</v-btn
                >
              </p>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="red lighten-1"
                  text
                  @click="warningDialog = false"
                >
                  Close
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <div class="row">
        <!-- <div class="col-lg-4">
          <v-autocomplete
            label="Select Groups"
            :items="groups"
            multiple
          ></v-autocomplete>
        </div> -->
        <div class="col-lg-4">
          <v-autocomplete
            label="Select Salespeople to Compare"
            v-model="user_ids"
            :items="users"
            item-value="id"
            item-text="name"
            no-data-text="No Salesperson Available"
            :search-input.sync="searchSalesperson"
            multiple
            clearable
            @change="debouncefetchData"
            :loading="isFetching"
          >
            <template v-slot:selection="{ item, index }">
              <v-chip v-if="index === 0">
                <span>{{ item.name }}</span>
              </v-chip>
              <span v-if="index === 1" class="grey--text caption"
                >(+{{ user_ids.length - 1 }} others)</span
              >
            </template>
          </v-autocomplete>
        </div>
        <div class="col-lg-4" v-if="isManager">
          <v-autocomplete
            label="Select Specific Dealer"
            hint="For automotive groups only"
            :items="spec_dealers"
            v-model="specificDealers"
            :search-input.sync="searchSpecificdealer"
            @change="debounceFetchUsers"
            item-value="id"
            item-text="name"
            multiple
            clearable
          ></v-autocomplete>
        </div>
        <div class="col-lg-4">
          <v-autocomplete
            label="Select Modules"
            :items="modules"
            :search-input.sync="searchModule"
            item-value="id"
            item-text="name"
            multiple
            clearable
            v-model="moduleIds"
            @change="debouncefetchData"
          ></v-autocomplete>
        </div>
        <div class="col-lg-4">
          <v-autocomplete
            label="Select Outstanding Module"
            :items="outstanding_modules"
            item-text="module.name"
            item-value="module_id"
            v-model="outstandingModuleId"
            @change="debounceFetchUsers"
            clearable
            messages="Shows the modules that are currently assigned"
          ></v-autocomplete>
        </div>
        <div class="col-lg-4">
          <v-autocomplete
            label="Select Outstanding Playlist"
            :items="outstanding_playlists"
            item-text="playlist.name"
            item-value="playlist_id"
            v-model="outstandingPlaylistId"
            @change="debounceFetchUsers"
            clearable
            messages="Shows the playlists that are currently assigned"
          ></v-autocomplete>
        </div>
        <div class="col-lg-4" v-if="user_ids.length >= 10">
          <v-checkbox
            label="Show Top 10 Users"
            color="primary"
            v-model="showTop"
            @change="fetchData"
          ></v-checkbox>
          <v-checkbox
            label="Show Bottom 10 Users"
            color="yellow darken-3"
            v-model="showBottom"
            @change="fetchData"
          ></v-checkbox>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <!-- <GChart
            v-if="chartData.length > 1"
            :settings="{ packages: ['bar'] }"
            :data="chartData"
            :options="chartOptions"
            :createChart="(el, google) => new google.charts.Bar(el)"
            @ready="onChartReady"
          >
          </GChart> -->
          <bar-chart
            v-if="chartData.length > 1"
            :chartData="bartChartData"
            :height="150"
          />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import BarChart from './chart/BarChart.vue';

export default {
  name: 'TeamProgress',

  components: { BarChart },

  props: {
    users: {
      type: Array,
      required: true,
    },
    spec_dealers: {
      type: Array,
      required: true,
    },
    modules: {
      type: Array,
      required: true,
    },
    outstanding_modules: {
      type: Array,
      required: true,
    },
    outstanding_playlists: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      searchModule: null,
      searchSalesperson: null,
      searchSpecificdealer: null,
      search: null,
      groups: ['All', 'BDC', 'Service', 'Showroom', 'Service'],
      salespersons: ['John Lim', 'Kyle Disher', 'Bob Melian', 'Gian Carlo'],
      user_ids: [],
      moduleIds: [],
      specificDealers: [],
      outstandingModuleId: null,
      outstandingPlaylistId: null,
      chartsLib: null,
      chartData: [['Salespeople', 'Progress']],
      bartChartData: {},
      bartChartStyles: {
        height: '100px',
        position: 'relative',
      },
      showTop: false,
      showBottom: false,
      isFetching: false,
      debounce: null,
      warningDialog: false,
      onProcess: false,
      filePath: '',
    };
  },

  computed: {
    chartOptions() {
      if (!this.chartsLib) return null;
      return this.chartsLib.charts.Bar.convertOptions({
        chart: {
          title: "Team's Total Course Progress",
          subtitle: 'Based on the percentage of units completed',
        },
        minValue: 0,
        maxValue: 100,
        bars: 'horizontal',
        hAxis: { format: 'decimal' },
        colors: ['#0277BD'],
      });
    },
    isManager() {
      return (
        this.$auth.user().is_manager && this.$auth.user().dealer.is_automotive
      );
    },
  },

  created() {
    // this.fetchData();
  },

  methods: {
    fillData(arr) {
      let label = arr.map((item) => {
        return item.name;
      });
      let data = arr.map((item) => {
        return item.progress;
      });

      return {
        labels: label,
        datasets: [
          {
            label: 'Progress',
            backgroundColor: '#0277BD',
            pointBackgroundColor: 'white',
            borderWidth: 1,
            pointBorderColor: '#249EBF',
            data: data,
          },
        ],
      };
    },

    debouncefetchData() {
      if (this.debounce !== null) clearTimeout(this.debounce);
      this.searchSpecificdealer = null;
      this.searchModule = null;
      this.searchSalesperson = null;
      let $ = this;
      this.debounce = setTimeout(function () {
        $.fetchData();
      }, 1000);
    },

    debounceFetchUsers() {
      if (this.debounce !== null) clearTimeout(this.debounce);
      this.searchSpecificdealer = null;
      this.searchModule = null;
      this.searchSalesperson = null;
      const that = this;
      this.debounce = setTimeout(function () {
        that.fetchUsers();
      }, 1000);
    },

    fetchData() {
      this.isFetching = true;
      const params = {
        user_ids: this.user_ids,
        show_bottom: this.showBottom,
        show_top: this.showTop,
        module_ids: this.moduleIds,
        outstanding_module_id: this.outstandingModuleId,
        outstanding_playlist_id: this.outstandingPlaylistId,
      };

      this.axios
        .post('/client/reports/fetchteam', params, { timeout: 45000 })
        .then(({ data }) => {
          this.chartData = data;
          this.bartChartData = this.fillData(data);
          this.chartData.unshift(['Salespeople', 'Progress']);
          this.isFetching = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    // exportData() {
    //   let params = {};
    //   params.user_ids = this.user_ids;
    //   params.show_bottom = this.showBottom;
    //   params.module_ids = this.moduleIds;
    //   params.outstanding_module_id = this.outstandingModuleId;
    //   params.outstanding_playlist_id = this.outstandingPlaylistId;
    //   params.download = true;
    //   if (params.user_ids.length !== 0) {
    //     this.warningDialog = true;
    //     this.onProcess = true;
    //     this.axios
    //       .post('/client/reports/fetchteam', params)
    //       .then(({ data }) => {
    //         this.filePath = data.filepath;
    //         this.onProcess = false;
    //       })
    //       .catch((error) => {
    //         console.log(error);
    //       });
    //   } else {
    //     this.$notify('error', 'No Salesperson Selected.');
    //   }
    // },

    exportData() {
      let params = {};
      params.type = 'team-progress';
      params.data = this.chartData;
      params.data.shift();
      if (this.user_ids.length !== 0) {
        this.warningDialog = true;
        this.onProcess = true;
        this.axios
          .post('/client/reports/exportData', params)
          .then(({ data }) => {
            this.filePath = data.filepath;
            this.onProcess = false;
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        this.$notify('error', 'No Salesperson Selected.');
      }
    },

    fetchUsers() {
      this.isFetching = true;
      this.axios
        .post('/client/reports/fetchusers', {
          outstanding_module_id: this.outstandingModuleId,
          outstanding_playlist_id: this.outstandingPlaylistId,
          specific_dealers: this.specificDealers,
        })
        .then(({ data }) => {
          this.users = data;
          this.isFetching = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    onChartReady(chart, google) {
      this.chartsLib = google;
    },
  },
};
</script>
