<template>
  <v-row>
    <v-col cols="11">
      <label>Answer:</label>
      <v-text-field
        v-model="answer.value"
        required
        name="answer"
        v-validate="'required'"
        data-vv-name="answer"
      />
      <span class="kt-font-danger validate-error" v-show="errors.has('answer')">
        {{ errors.first('answer') }}
      </span>
    </v-col>
    <v-col>
      <label class="mt-checkbox">
        <input
          type="checkbox"
          v-model="answer.isCorrect"
          value="true"
          class="mt-12"
        />
        Correct
      </label>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'AnswerField',
  props: {
    value: {
      required: true,
      type: Object,
    },
  },
  computed: {
    answer: {
      get() {
        return this.value;
      },
      set(answer) {
        this.$emit('input', answer);
      },
    },
  },
};
</script>

<style scoped></style>
