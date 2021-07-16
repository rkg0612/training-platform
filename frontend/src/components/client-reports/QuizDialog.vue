<template>
  <v-dialog v-model="showQuizDialog" max-width="900">
    <v-card>
      <v-app-bar dark color="primary">
        <v-toolbar-title class="headline"> Quiz Results: </v-toolbar-title>
      </v-app-bar>
      <v-container>
        <div class="row">
          <div class="col-lg-12">
            <h5>
              Total Score: {{ quizScore }}/{{
                form instanceof Array ? form.length : ''
              }}
            </h5>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12" v-for="(question, index) in form" :key="index">
            <h5>{{ index + 1 }}. {{ question.value }}</h5>
            <template v-for="(answer, index) in question.answers">
              <div class="form-check mb-2 mx-4">
                <input
                  class="form-check-input"
                  :type="question.is_multiple ? 'checkbox' : 'radio'"
                  :name="question.value"
                  :value="answer.id"
                  :id="answer.id"
                  :checked="includes(question.user_answer, answer.id)"
                  disabled
                />
                <label
                  class="form-check-label"
                  :for="answer.id"
                  :class="answer.is_correct ? 'text-success' : 'text-danger'"
                  >{{ answer.value }}</label
                >
              </div>
            </template>
          </div>
        </div>
      </v-container>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red lighten-1" dark @click="showQuizDialog = false">
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { includes as _includes } from 'lodash-es';

export default {
  name: 'QuizDialog',

  props: {
    value: Boolean,
    form: Array,
    score: Number,
  },

  data() {
    return {
      includes: _includes,
    };
  },

  computed: {
    quizScore() {
      return this.score;
    },
    showQuizDialog: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
  },
};
</script>
