<template>
  <v-dialog v-model="show" fullscreen transition="dialog-bottom-transition">
    <v-card>
      <v-toolbar dark color="secondary">
        <v-card-title>
          <i class="fal fa-book mr-2"></i>
          <span class="headline">Edit Question</span>
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
              <label for="question">Question</label>
              <v-text-field
                id="question"
                name="question"
                v-model="question"
                v-validate="'required'"
              />
              <span
                class="kt-font-danger validate-error"
                v-show="errors.has('question')"
                >{{ errors.first('question') }}</span
              >
            </v-col>
          </v-row>
          <template v-for="(answer, index) in selectedQuestion.answers">
            <quiz-answer
              :answer="answer"
              :answers="answers"
              :index="index"
            ></quiz-answer>
          </template>
          <v-row>
            <v-col>
              <v-btn @click="save" color="primary">Save</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import QuizAnswer from '../../quiz-management/QuizAnswer';
import { map as _map } from 'lodash-es';
export default {
  props: {
    value: Boolean,
  },
  components: {
    QuizAnswer,
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
    selectedQuestion() {
      return this.$store.state.quizBuilder.selectedQuestion;
    },
  },
  watch: {
    show: {
      immediate: true,
      handler: function (val) {
        if (val) {
          this.question = this.selectedQuestion.value;
          this.answers = this.selectedQuestion.answers;
        } else {
          this.close();
        }
      },
    },
    answers: {
      immediate: true,
      deep: true,
      handler: (val) => {
        console.log(val);
      },
    },
  },
  data() {
    return {
      question: '',
      answers: [],
    };
  },
  methods: {
    checkForCorrectAns(arr) {
      const { length } = arr;
      const id = length + 1;
      const fBool = arr.some((el) => el.is_correct == true);
      const fString = arr.some((el) => el.is_correct == 'true');
      return fBool || fString;
    },
    save() {
      this.$validator.validate('*').then((result) => {
        if (this.checkForCorrectAns(this.answers)) {
          if (result) {
            this.$store.commit('quizBuilder/updateSelectedQuestion', {
              id: this.$store.state.quizBuilder.selectedQuestion.id,
              payload: {
                id: this.$store.state.quizBuilder.selectedQuestion.id,
                value: this.question,
                answers: this.answers,
              },
            });
            this.$notify('success', 'Question updated...');
            this.close();
          }
        } else {
          this.$notify('error', 'Needs at least one correct answer!');
        }
      });
    },
    close() {
      this.show = false;
      this.question = '';
      this.$store.commit('quizBuilder/resetSelectedQuestion');
      this.answers = [];
    },
  },
};
</script>

<style scoped></style>
