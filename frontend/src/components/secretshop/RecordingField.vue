<template>
  <v-row class="fileRepeaterRow">
    <v-col cols="6" sm="6" md="6" class="fileInputField">
      <v-file-input v-model="fileRepeater.fileUpload" label="Upload a file">
      </v-file-input>
    </v-col>
    <v-col cols="6" sm="6" md="6">
      <v-combobox
        v-model="fileRepeater.label"
        :items="dealers"
        clearable
        label="Name of Specific Dealer"
        :menu-props="{ closeOnClick: true }"
      >
      </v-combobox>
    </v-col>
  </v-row>
</template>

<script>
import { map as _map } from 'lodash-es';

export default {
  name: 'FileRepeaterWithTextField',
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
    dealers() {
      return _map(this.$store.state.specificDealers.list, 'name');
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
