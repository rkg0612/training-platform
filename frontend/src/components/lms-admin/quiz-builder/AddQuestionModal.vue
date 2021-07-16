<template>
  <v-dialog v-model="show" fullscreen transition="dialog-bottom-transition">
    <v-card>
      <v-toolbar dark color="secondary">
        <v-card-title>
          <i class="fal fa-book mr-2"></i>
          <span class="headline">Add Question</span>
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
              >
                {{ errors.first('question') }}
              </span>
            </v-col>
          </v-row>
          <template v-for="(answer, index) in answers">
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
import VueRepeater from 'vue-repeater';
import { map as _map } from 'lodash-es';
import QuizAnswer from '../../quiz-management/QuizAnswer';
export default {
  props: {
    value: Boolean,
  },
  components: {
    QuizAnswer,
    VueRepeater,
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
  },
  data() {
    return {
      question: '',
      answers: [
        {
          value: '',
          is_correct: '',
        },
      ],
    };
  },
  methods: {
    checkForCorrectAns(arr) {
      const { length } = arr;
      const id = length + 1;
      const found = arr.some((el) => el.is_correct === true);
      return found;
    },
    save() {
      this.$validator.validate('*').then((result) => {
        if (result) {
          if (this.checkForCorrectAns(this.answers)) {
            this.$store.commit('quizBuilder/setQuestion', {
              value: this.question,
              answers: this.answers,
            });
            this.close();
          } else {
            this.$notify('error', 'Needs at least one correct answer!');
          }
        }
      });
    },
    close() {
      this.$notify('success', 'Question added successfully!');
      this.show = false;
      this.question = '';
      this.answers = [
        {
          value: '',
          is_correct: '',
        },
      ];
    },
  },
};
</script>

<style scoped></style>
