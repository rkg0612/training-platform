<template>
  <div class="kt-portlet kt-portlet--height-fixed">
    <div
      class="kt-portlet_head kt-portlet__head kt-portlet__head--lg kt-portle__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-ballot-check"></i>
        </span>
        <h3 class="kt-portlet__head-title">Quizzes Manager</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :server-items-length="totalItems"
        :loading="loading"
        :items-per-page="5"
        :items="quizzes"
        @update:page="fetchQuizzes"
      >
        <template v-slot:top>
          <div class="row no-gutters px-3 mt-3">
            <div class="col-xl-4">
              <v-select
                :loading="loading"
                :disabled="loading"
                @change="fetchQuizzes(currentPage)"
                dense
                v-model="filters.status"
                multiple
                prepend-inner-icon="fas fa-filter"
                :items="['Active', 'Inactive']"
              >
                <template v-slot:prepend-item>
                  <v-list-item ripple @click="toggleAllFilter">
                    <v-list-item-content>
                      <v-list-item-title>All</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                  <v-divider class="mt-2"></v-divider>
                </template>
              </v-select>
            </div>
            <div class="col-xl-5">
              <div lcass="d-flex flex-wrap flex-sm-nowrap">
                <!--  <router-link
                  to="lms-admin/quiz-builder"
                  class="btn btn-primary btn-sm text-white float-right"
                  >Create Quiz</router-link
                > -->
                <v-text-field
                  clearable
                  :loading="loading"
                  class="mt-0 pt-1 ml-5 mr-sm-5 w-70"
                  v-model.trim="filters.search"
                  @change="fetchQuizzes(currentPage)"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                />
              </div>
            </div>
            <div class="col-xl-3 text-right">
              <v-btn color="primary" dark to="lms-admin/quiz-builder" text>
                <v-icon class="mr-2" small>fal fa-plus</v-icon>
                New Quiz
              </v-btn>
            </div>
          </div>
        </template>
        <template v-slot:item.answers="{ item }">{{
          item.questions.length
        }}</template>
        <template v-slot:item.action="{ item }">
          <template v-if="item.deleted_at === null">
            <v-btn
              :to="{
                name: 'Quiz Builder',
                params: {
                  id: item.id,
                },
              }"
              color="grey darken-3"
              text
            >
              <v-icon class="" small>fal fa-edit</v-icon>
            </v-btn>
            <v-btn @click="duplicate(item)" text color="primary">
              <v-icon small>fal fa-copy</v-icon>
            </v-btn>
            <v-btn text @click="deleteItem(item)" color="red darken-3" small>
              <v-icon small>fal fa-trash</v-icon>
            </v-btn>
          </template>
          <template v-else>
            <v-btn text @click="restoreItem(item)" color="blue darken-3" small>
              <v-icon small class="mr-2">fal fa-trash-restore</v-icon>
              Restore
            </v-btn>
          </template>
        </template>
      </v-data-table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'QuizSection',
  data() {
    return {
      filters: {
        search: '',
        status: [],
      },
      headers: [
        {
          text: 'Title',
          align: 'left',
          sortable: true,
          value: 'title',
        },
        {
          text: '# of Questions',
          align: 'left',
          value: 'answers',
          sortable: false,
        },
        {
          text: 'Action',
          align: 'center',
          value: 'action',
          sortable: false,
          width: 210,
        },
      ],
      currentPage: 1,
      totalItems: 1,
      loading: false,
    };
  },
  mounted() {
    this.toggleAllFilter();
    this.fetchQuizzes(1);
  },
  computed: {
    quizzes() {
      return this.$store.state.quizBuilder.list;
    },
  },
  methods: {
    toggleAllFilter() {
      this.filters.status = ['Active', 'Inactive'];
    },
    fetchQuizzes(page) {
      this.currentPage = page;
      this.loading = true;
      this.$store
        .dispatch('quizBuilder/getQuizzes', {
          page: this.currentPage,
          search: this.filters.search,
          status: this.filters.status,
        })
        .then(({ data }) => {
          this.currentPage = data.current_page;
          this.totalItems = data.total;
          this.$store.commit('quizBuilder/setQuizzes', data.data);
          this.loading = false;
        })
        .catch((error) => {
          if (error.response.status === 400) {
            this.$notify('danger', 'Please select filters!');
            this.loading = false;
          }
        });
    },
    deleteItem(item) {
      this.$store
        .dispatch('quizBuilder/deleteQuiz', {
          id: item.id,
        })
        .then(({ data }) => {
          this.$notify('success', 'Quiz archived');
          this.fetchQuizzes(this.currentPage);
        });
    },
    restoreItem(item) {
      this.$store
        .dispatch('quizBuilder/restoreQuiz', {
          id: item.id,
        })
        .then(({ data }) => {
          this.$notify('success', 'Quiz restored');
          this.fetchQuizzes(this.currentPage);
        });
    },
    duplicate(item) {
      this.axios
        .post('lms-manager/builder/quiz/duplicate/', {
          id: item.id,
        })
        .then(({ data }) => {
          this.$notify('success', 'Quiz successfully duplicated!');
          this.fetchQuizzes(this.currentPage);
        });
    },
  },
};
</script>

<style scoped></style>
