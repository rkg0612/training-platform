<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-book"></i>
        </span>
        <h3 class="kt-portlet__head-title">Quiz Builder</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <div class="row">
        <div class="col-12">
          <v-data-table :headers="headers" :items="questions">
            <template v-slot:top>
              <div class="row">
                <div class="col-4">
                  <v-text-field
                    label="Quiz Title"
                    v-validate="'required'"
                    name="quiz_title"
                    v-model="quizTitle"
                    class="inline"
                  ></v-text-field>
                  <span
                    class="kt-font-danger validate-error"
                    v-show="errors.has('quiz_title')"
                    >{{ errors.first('quiz_title') }}</span
                  >
                </div>
                <div class="col">
                  <div class="float-right">
                    <v-btn
                      dark
                      color="blue-grey 3"
                      class="mr-1 mt-3 mt-sm-0"
                      @click.stop="
                        showSearchQuestionDialog = !showSearchQuestionDialog
                      "
                    >
                      Search Question
                    </v-btn>
                    <v-btn
                      dark
                      color="primary"
                      @click.stop="
                        showAddQuestionDialog = !showAddQuestionDialog
                      "
                    >
                      Add Question
                    </v-btn>
                  </div>
                </div>
              </div>
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
              <button
                type="button"
                class="btn btn-label-info btn-sm btn-icon units-btn"
                @click.stop="editItem(item)"
              >
                <i class="fal fa-edit" />
              </button>
              <button
                type="button"
                class="btn btn-label-dark btn-sm btn-icon units-btn"
                @click.stop="removeItem(item)"
              >
                <i class="far fa-trash" />
              </button>
            </template>
          </v-data-table>
        </div>
        <div class="col-12">
          <div class="float-right">
            <v-btn
              color="blue darken-3"
              dark
              @click="saveQuiz()"
              :loading="loading"
              >Save</v-btn
            >
          </div>
        </div>
      </div>
    </div>
    <add-question-modal v-model="showAddQuestionDialog" />
    <edit-question-answer-modal v-model="editQuestionAnswerDialog" />
    <search-question-dialog
      v-model="showSearchQuestionDialog"
    ></search-question-dialog>
  </div>
</template>

<script>
import { findIndex as _findIndex } from 'lodash-es';
import AddQuestionModal from './AddQuestionModal';
import EditQuestionAnswerModal from './EditQuestionAnswerModal';
import SearchQuestionDialog from './SearchQuestionDialog';
export default {
  name: 'QuizBuilder',
  components: {
    AddQuestionModal,
    EditQuestionAnswerModal,
    SearchQuestionDialog,
  },
  data() {
    return {
      headers: [
        {
          text: 'Question',
          align: 'left',
          value: 'value',
          sortable: true,
        },
        {
          text: 'Answers',
          align: 'left',
          value: 'answers',
          sortable: true,
        },
        {
          text: 'Actions',
          align: 'center',
          width: 120,
          value: 'actions',
          sortable: true,
        },
      ],
      id: '',
      loading: false,
      showAddQuestionDialog: false,
      editQuestionAnswerDialog: false,
      showSearchQuestionDialog: false,
      quizTitle: '',
      selectedQuestion: {
        id: '',
        title: String,
        answers: Array,
      },
    };
  },
  computed: {
    questions() {
      return this.$store.state.quizBuilder.newQuiz.questions;
    },
  },
  created() {
    this.$validator.localize('en', {
      attributes: {
        quiz_title: 'Quiz Title',
      },
    });
    this.id = this.checkIfEdit();
  },
  methods: {
    checkIfEdit() {
      this.$store.commit('quizBuilder/resetQuizQuestions');
      if (this.$route.params.id) {
        this.$store
          .dispatch('quizBuilder/getQuiz', {
            id: this.$route.params.id,
          })
          .then(({ data }) => {
            this.quizTitle = data.title;
            const questions = data.questions;
            questions.forEach((question) => {
              this.$store.commit('quizBuilder/setQuestion', question);
            });
          });
        return this.$route.params.id;
      }
      return null;
    },
    setRequest() {
      return {
        title: this.quizTitle,
        questions: this.questions,
      };
    },
    saveQuiz() {
      this.$validator.validate('*').then((result) => {
        if (result && this.$route.params.id === undefined) {
          return this.createQuiz();
        }
        if (result && this.$route.params.id) {
          return this.updateQuiz();
        }
      });
    },
    createQuiz() {
      this.loading = true;
      this.axios
        .post('lms-manager/builder/quizzes', this.setRequest())
        .then(({ data }) => {
          this.$notify('success', 'Successfully created!');
          this.$router.push({
            name: 'LMS Management',
          });
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.loading = false;
          this.$store.commit('quizBuilder/resetQuestion');
          this.quizTitle = null;
        });
    },
    updateQuiz() {
      this.loading = true;
      this.axios
        .patch(`lms-manager/builder/quizzes/${this.id}`, this.setRequest())
        .then(({ data }) => {
          this.$notify('success', 'Successfully updated!');
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.id = this.checkIfEdit();
          this.loading = false;
        });
    },
    editItem(item) {
      this.$store.commit('quizBuilder/resetSelectedQuestion');
      this.$store.commit('quizBuilder/setSelectedQuestion', item);
      this.editQuestionAnswerDialog = !this.editQuestionAnswerDialog;
    },
    removeItem(item) {
      const index = _findIndex(
        this.$store.state.quizBuilder.newQuiz.questions,
        item
      );
      this.$store.commit('quizBuilder/removeQuestion', index);
      this.$notify('success', 'Question removed successfully!');
    },
  },
};
</script>

<style scoped></style>
