<template>
  <div>
    <v-row class="seriesRow">
      <div class="pl-2 toolbar">
        <i class="fa fa-plus" @click="addAnswer" />
        <i class="fas fa-bring-forward ml-3" @click="cloneAnswer" />
        <i class="fa fa-trash ml-3" @click="removeAnswer" />
      </div>
    </v-row>
    <v-row>
      <v-col cols="11">
        <label>Answer:</label>
        <v-textarea
          solo
          required
          v-model="answer.value"
          name="answer"
          label="Answer"
          v-validate="'required'"
          data-vv-name="answer"
        ></v-textarea>
        <span
          class="kt-font-danger validate-error"
          v-show="errors.has('answer')"
        >
          {{ errors.first('answer') }}
        </span>
      </v-col>
      <v-col>
        <label class="mt-checkbox">
          <input
            v-model="answer.is_correct"
            type="checkbox"
            value="true"
            class="mt-12"
          />
          Correct
        </label>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { cloneDeep as _cloneDeep } from 'lodash-es';

export default {
  name: 'QuizAnswer',
  props: {
    answer: {
      required: true,
    },
    answers: {
      required: true,
      type: Array,
    },
    index: {
      required: true,
      type: Number,
    },
  },
  methods: {
    addAnswer() {
      this.answers.push({
        value: '',
        is_correct: '',
      });
    },
    removeAnswer() {
      if (this.answers.length === 1) {
        return;
      }

      this.answers.splice(this.index, 1);
    },
    cloneAnswer() {
      const answer = _cloneDeep(this.answers[this.index]);
      this.answers.push(answer);
    },
  },
};
</script>

<style scoped lang="stylus">
.nav-anchor
  display none

.navigation
  display none

.toolbar
  background #fafafa
  width 100%

.toolbar i
  cursor pointer
  font-size 15px
</style>
