<template>
  <div class="mb-2">
    <v-text-field
      label=""
      v-model="input"
      ref="input"
      hide-details
      v-if="!showTextarea"
    >
    </v-text-field>
    <v-textarea
      label=""
      v-model="input"
      ref="input"
      full-width
      counter="255"
      filled
      v-else
    ></v-textarea>
    <v-btn small class="mr-1" text @click="toggleInlineEdit(type)">
      CANCEL
    </v-btn>
    <v-btn
      small
      class="mr-1"
      text
      color="primary"
      @click="save"
      :loading="loading"
      :disabled="loading"
    >
      SAVE
    </v-btn>
  </div>
</template>

<script>
export default {
  name: 'EditTextField',
  props: ['value', 'show', 'toggleInlineEdit', 'textarea'],
  data: () => ({
    input: '',
    loading: false,
  }),
  computed: {
    showTextarea() {
      return this.textarea !== undefined;
    },
    type() {
      return this.showTextarea ? 'description' : 'name';
    },
    playlist() {
      return this.$store.state.playlist.playlist;
    },
  },
  methods: {
    save() {
      const dataInput = {
        type: this.type,
        value: this.input,
      };

      this.loading = true;
      this.$http
        .put(`/playlist/${this.playlist.id}`, dataInput)
        .then(() => {
          this.loading = false;
          this.toggleInlineEdit(this.type);
          this.$store.dispatch('playlist/setData', dataInput);
        })
        .catch(() => {
          this.loading = false;
          this.$notify(
            'error',
            'Encountered an error while saving the changes! Please try again.'
          );
        });
    },
  },
  watch: {
    show(check) {
      if (check) {
        this.input = this.value;
        this.$nextTick(() => this.$refs.input.focus());
      }
    },
  },
};
</script>

<style scoped></style>
