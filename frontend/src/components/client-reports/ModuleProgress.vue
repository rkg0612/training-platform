<template>
  <div class="kt-portlet mt-5 mt-md-3 mt-lg-0">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm flex-wrap"
    >
      <div class="kt-portlet__head-label pt-3 pt-md-0">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-chart-bar"></i>
        </span>
        <h3 class="kt-portlet__head-title">
          Progress Report
          <small>Use the filters below to show the data.</small>
        </h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group d-flex flex-wrap justify-center">
          <v-btn
            color="primary"
            dark
            @click="exportData()"
            depressed
            class="mt-3 mt-md-0 mx-1"
            text
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
        <div class="col-lg-4">
          <v-autocomplete
            label="Select Salesperson"
            :items="users"
            item-value="id"
            item-text="name"
            no-data-text="No Salesperson Available"
            :search-input.sync="searchSalesperson"
            @change="debouncefetchData"
            v-model="salesperson"
            multiple
            clearable
            :loading="isFetching"
          >
            <template v-slot:selection="{ item, index }">
              <v-chip v-if="index === 0">
                <span>{{ item.name }}</span>
              </v-chip>
              <span v-if="index === 1" class="grey--text caption"
                >(+{{ salesperson.length - 1 }} others)</span
              >
            </template>
          </v-autocomplete>
        </div>
        <!--If dealer is automotive group
        this field will show -->
        <div class="col-lg-4" v-if="isManager">
          <v-autocomplete
            label="Select a Specific Dealer"
            :items="spec_dealers"
            item-text="name"
            item-value="id"
            :search-input.sync="searchSpecificdealer"
            @change="debounceFetchUsers"
            v-model="specificDealers"
            clearable
            multiple
            messages="For automotive groups and account managers only"
          />
        </div>
        <div class="col-lg-4">
          <v-autocomplete
            label="Select Modules"
            :items="modules"
            :search-input.sync="search"
            item-value="id"
            item-text="name"
            multiple
            clearable
            @change="debouncefetchData"
            v-model="moduleIds"
          ></v-autocomplete>
        </div>
        <!-- <div class="col-lg-4">
          <v-autocomplete
            label="Select a User Group"
            :items="groups"
            v-model="dealerGroup"
            messages="Shows groups that the managers created"
          ></v-autocomplete>
        </div> -->
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
      </div>
      <div class="row" v-for="(selected, index) in selectedUsers">
        <div class="col-lg-12">
          <v-card>
            <v-card-title>
              <h4 class="headline">{{ selected.name }}</h4>
            </v-card-title>
            <v-card-text>
              <v-progress-linear
                v-if="moduleIds.length !== 0"
                :value="selected.overall_progress"
                height="15"
                rounded
                color="light-blue darken-3"
              >
                <strong>{{ selected.overall_progress }}%</strong>
              </v-progress-linear>
              <v-data-table
                :headers="headers"
                :items="selected.units"
                :items-per-page="10"
                item-key="unitName"
                class="elevation-1 mt-3"
                :loading="isFetching"
              >
                <template v-slot:top>
                  <v-toolbar flat color="white">
                    <v-toolbar-title class="subtitle-2">
                      <!-- Note:<br />
                      <ul>
                        <li>Viewed - The video was played but not completed</li>
                        <li>
                          Completed - the unit has been marked as completed or
                          the quiz was taken
                        </li>
                      </ul> -->
                    </v-toolbar-title>
                  </v-toolbar>
                </template>
                <template v-slot:item.action="{ item }">
                  <v-btn
                    text
                    small
                    color="primary"
                    :disabled="item.hasNote !== 'YES'"
                    @click.stop="showAvailNotes(item.notes)"
                  >
                    <v-icon left> fal fa-book-open </v-icon>
                    View Notes
                  </v-btn>
                  <v-btn
                    text
                    small
                    color="blue-grey"
                    :disabled="item.quizForm == null"
                    @click.stop="showAvailQuiz(item)"
                  >
                    <v-icon left> fal fa-clipboard-list-check </v-icon>
                    View Quiz Answers
                  </v-btn>
                  <quiz-dialog
                    v-model="showQuizDialog"
                    :form="showQuizForm"
                    :score="quizResult"
                  />
                </template>
                <template v-slot:item.date_completed="{ item }">
                  {{ formatDate(item.date_completed) }}
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </div>
      </div>
    </div>
    <note-dialog :notes="showNotes" v-model="showNoteDialog" />
  </div>
</template>
<script>
import NoteDialog from './NoteDialog.vue';
import QuizDialog from './QuizDialog.vue';
import dayjs from 'dayjs';

export default {
  name: 'ModuleProgress',

  components: {
    NoteDialog,
    QuizDialog,
  },

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
      search: null,
      searchModule: null,
      searchSalesperson: null,
      searchSpecificdealer: null,
      showNotes: [],
      showQuizForm: [],
      warningDialog: false,
      showNoteDialog: false,
      showQuizDialog: false,
      date: ['', ''],
      rangeMenu: false,
      specificDealer: '',
      salesperson: [],
      dealerGroup: '',
      courseProgress: 33,
      specificDealers: [],
      groups: ['All', 'BDC', 'Service', 'Showroom', 'Service'],
      sampleModules: [
        'Select All Modules',
        'Internet Buyers',
        'Inbound Calls',
        'Unsold Showroom Traffic',
        'Objection Handling',
      ],
      moduleIds: [],
      outstandingModuleId: null,
      outstandingPlaylistId: null,
      headers: [
        {
          text: 'Name of Unit',
          align: 'left',
          value: 'name',
          width: 200,
        },
        {
          text: 'From Module/Playlist Name',
          value: 'module',
          align: 'center',
          width: 200,
        },
        {
          text: 'Type',
          value: 'type',
          align: 'center',
          width: 200,
        },
        {
          text: 'Completed the Video',
          value: 'played',
          align: 'center',
          width: 200,
        },
        {
          text: 'Unit Status',
          value: 'status',
          align: 'center',
          width: 200,
        },
        {
          text: 'Date Completed',
          value: 'date_completed',
          align: 'center',
          width: 200,
        },
        {
          text: 'Has Note',
          value: 'hasNote',
          align: 'center',
          width: 200,
        },
        {
          text: 'Quiz Score',
          value: 'quizScore',
          align: 'center',
          width: 200,
        },
        {
          text: 'Action',
          value: 'action',
          align: 'center',
          width: 300,
        },
      ],
      selected: {
        name: 'Name',
        overall_progress: 0,
        units: [],
      },
      selectedUsers: [],
      isFetching: false,
      debounce: null,
      onProcess: false,
      filePath: '',
      quizResult: 0,
    };
  },

  created() {
    //
  },

  computed: {
    rangeFilter() {
      return this.date.join(' ~ ');
    },
    isManager() {
      return (
        this.$auth.user().is_manager && this.$auth.user().dealer.is_automotive
      );
    },
  },

  methods: {
    formatDate(date) {
      if (date == 'N/A') return date;
      return dayjs(date).format('MMM D, YYYY, h:mm a');
    },
    showAvailNotes(notes) {
      this.showNotes = notes;
      this.showNoteDialog = true;
    },
    showAvailQuiz(item) {
      this.showQuizForm = JSON.parse(item.quizForm);
      this.quizResult = item.quizScore;
      this.showQuizDialog = true;
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
        users: this.salesperson,
        module_ids: this.moduleIds,
        outstanding_module_id: this.outstandingModuleId,
        outstanding_playlist_id: this.outstandingPlaylistId,
        specific_dealers: this.specificDealers,
      };

      this.axios
        .post('/client/reports/fetchdata', params)
        .then(({ data }) => {
          this.selectedUsers = data;
          this.isFetching = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    // exportData() {
    //   let params = {};
    //   params.users = this.salesperson;
    //   params.module_ids = this.moduleIds;
    //   params.outstanding_module_id = this.outstandingModuleId;
    //   params.outstanding_playlist_id = this.outstandingPlaylistId;
    //   params.specific_dealers = this.specificDealers;
    //   params.download = true;
    //   if (params.users.length !== 0) {
    //     this.warningDialog = true;
    //     this.onProcess = true;
    //     this.axios
    //       .post('/client/reports/fetchdata', params)
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
      params.type = 'module-progress';
      params.data = this.selectedUsers;
      if (this.salesperson.length !== 0) {
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
      this.salesperson = [];
      this.selectedUsers = [];
      this.isFetching = true;
      this.axios
        .post('/client/reports/fetchusers', {
          outstanding_playlist_id: this.outstandingPlaylistId,
          outstanding_module_id: this.outstandingModuleId,
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
  },
};
</script>
