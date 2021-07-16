<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="kt-portlet">
        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
              <i class="fal fa-file-chart-line"></i>
              LMS Summary Report
            </h3>
          </div>
        </div>
        <div class="kt-portlet__body">
          <div v-if="!isFetching">
            <div v-for="(selected, index) in selectedUsers">
              <v-data-table
                :headers="headers"
                :items="selected.units"
                :items-per-page="10"
                item-key="unitName"
                class="elevation-1 mt-3"
                :loading="isFetching"
                >a
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
                    :form="showQuizForm"
                    v-model="showQuizDialog"
                    :score="quizScore"
                  />
                </template>
              </v-data-table>
            </div>
          </div>
          <div v-if="isFetching">
            <vue-content-loader
              :width="994"
              :height="554"
              :speed="2"
              primaryColor="#e4e2e2"
              secondaryColor="#ecebeb"
            >
              <rect x="0" y="0" rx="0" ry="0" width="994" height="559" />
            </vue-content-loader>
          </div>
        </div>
      </div>
    </div>
    <note-dialog :notes="showNotes" v-model="showNoteDialog" />
  </div>
</template>
<script>
import NoteDialog from './NoteDialog.vue';
import QuizDialog from './QuizDialog.vue';
import VueContentLoader from 'vue-content-loading';

export default {
  name: 'ModuleProgress',

  components: {
    NoteDialog,
    QuizDialog,
    VueContentLoader,
  },

  props: {
    userId: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      showNotes: [],
      showQuizForm: [],
      warningDialog: false,
      showNoteDialog: false,
      showQuizDialog: false,
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
          text: 'Status',
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
      filePath: '',
      quizScore: 0,
    };
  },

  created() {
    this.fetchData(this.userId);
  },

  methods: {
    showAvailNotes(notes) {
      this.showNotes = notes;
      this.showNoteDialog = true;
    },
    showAvailQuiz(item) {
      this.quizScore = item.quizScore;
      this.showQuizForm = JSON.parse(item.quizForm);
      this.showQuizDialog = true;
    },

    fetchData() {
      this.isFetching = true;
      let params = {};
      params.users = [this.userId];
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
  },
};
</script>
