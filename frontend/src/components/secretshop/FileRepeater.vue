<template>
  <v-row class="fileRepeaterRow">
    <v-col cols="12" sm="12" md="12" class="fileInputField">
      <v-file-input v-model="fileRepeater.fileUpload" label="Upload a file">
      </v-file-input>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'FileRepeater',
  props: {
    value: {
      type: Object,
      required: true,
    },
  },
  computed: {
    fileRepeater: {
      get() {
        return this.value;
      },
      set(fileRepeater) {
        this.$emit('input', fileRepeater);
      },
    },
  },
};
</script>
<style lang="stylus">
.nav-anchor
  display none

.navigation
  display none

.toolbar
  background #fafafa
  padding 5px
.toolbar > *
  margin 5px
</style>
