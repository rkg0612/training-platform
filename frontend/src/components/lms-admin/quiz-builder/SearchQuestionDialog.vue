<template>
  <v-dialog v-model="show" fullscreen transition="dialog-bottom-transition">
    <v-card>
      <v-toolbar dark color="secondary">
        <v-card-title>
          <i class="fal fa-book mr-2"></i>
          <span class="headLine">Search Question</span>
        </v-card-title>
        <v-spacer></v-spacer>
        <button
          type="button"
          class="btn btn-light btn-elevate-hover btn-circle btn-icon btn-sm"
          @click="show = false"
        >
          <i class="fal fa-times"></i>
        </button>
      </v-toolbar>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col>
              <v-data-table
                :loading="loading"
                :headers="headers"
                :items="questions"
                :server-items-length="total"
                :items-per-page="10"
                @update:page="changePage"
              >
                <template v-slot:top>
                  <v-row>
                    <v-col cols="8">
                      <v-text-field
                        v-model="search"
                        label="Search Question"
                        @change="searchQuestions"
                      >
                      </v-text-field>
                      <small
                        >Please press enter after typing the keyword...</small
                      >
                    </v-col>
                  </v-row>
                </template>
                <template v-slot:item.answers="{ item }">
                  <p
                    v-for="(answer, index) in item.answers"
                    v-bind:key="index"
                    :class="
                      answer.isCorrect || answer.is_correct
                        ? 'text-success'
                        : 'text-danger'
                    "
                  >
                    {{ answer.value }}
                  </p>
                </template>
                <template v-slot:item.actions="{ item }">
                  <v-btn color="info" small @click="getQuestion(item)">
                    Get Question
                  </v-btn>
                </template>
              </v-data-table>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import { map as _map } from 'lodash-es';
export default {
  name: 'SearchQuestionDialog',
  props: {
    value: Boolean,
  },
  data() {
    return {
      loading: false,
      currentPage: 1,
      search: '',
      total: 1,
      headers: [
        {
          text: 'Question',
          align: 'left',
          value: 'value',
          sortable: false,
        },
        {
          text: 'Answers',
          align: 'left',
          value: 'answers',
          sortable: false,
        },
        {
          text: 'Actions',
          align: 'left',
          value: 'actions',
          sortable: false,
        },
      ],
    };
  },
  computed: {
    show: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
    questions() {
      return this.$store.state.quizBuilder.questionList;
    },
  },
  created() {
    this.searchQuestions();
  },
  methods: {
    changePage(pageNumber) {
      this.currentPage = pageNumber;
      this.searchQuestions();
    },
    searchQuestions() {
      this.loading = true;
      this.$store
        .dispatch('quizBuilder/searchQuestions', {
          page: this.currentPage,
          search: this.search,
        })
        .then(({ data }) => {
          this.$store.commit('quizBuilder/setQuestionList', data.data);
          this.currentPage = data.current_page;
          this.total = data.total;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    getQuestion(question) {
      const answers = _map(question.answers, (answer) => {
        return {
          value: answer.value,
          is_correct: answer.is_correct,
        };
      });
      this.$store.commit('quizBuilder/setQuestion', {
        value: question.value,
        answers,
      });
      this.$notify('success', 'Question added');
      this.show = false;
    },
  },
};
</script>
