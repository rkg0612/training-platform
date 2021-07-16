<template>
  <v-row class="unitRepeaterRow">
    <v-col cols="12" sm="12" md="6" class="unitRepeaterInput">
      <v-text-field
        v-model="unitRepeater.name"
        label="Name of Section (Optional)"
      ></v-text-field>
    </v-col>
    <v-col cols="3" sm="3" md="6" class="unitRepeaterInput">
      <v-autocomplete
        v-model="unitRepeater.highlight_unit"
        :items="units"
        item-text="name"
        item-value="id"
        :menu-props="{ closeOnClick: true }"
        label="Select a highlight unit"
        messages="A highlight unit is a unit that shows the thumbnail as a banner."
      ></v-autocomplete>
    </v-col>
    <v-col cols="9" sm="9" md="9" class="unitRepeaterInput">
      <v-autocomplete
        v-model="unitRepeater.units"
        multiple
        :items="units"
        item-text="name"
        item-value="id"
        :menu-props="{ closeOnClick: true }"
        label="Select the Unit/s (arrange in order)"
      ></v-autocomplete>
    </v-col>
    <v-col cols="3" sm="3" md="3" class="unitRepeaterInput">
      <v-switch
        v-model="unitRepeater.is_banner"
        class="ma-2"
        label="Is this a banner type?"
      ></v-switch>
    </v-col>
  </v-row>
</template>
<script>
export default {
  inheritAttrs: true,
  name: 'FileRepeater',

  props: {
    value: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      // units: [
      //   'Prepping for the Call',
      //   'Shoulda Coulda Woulda',
      //   'Sample Unit Name',
      // ],
    };
  },

  computed: {
    unitRepeater: {
      get() {
        return this.value;
      },
      set(unitRepeater) {
        this.$emit('input', unitRepeater);
      },
    },
    units() {
      const tempUnits = this.$store.state.moduleBuilds.listUnits;
      return tempUnits;
    },
  },
};
</script>
<style lang="stylus">
.nav-anchor {
  display: none;
}

.navigation {
  display: none;
}

.toolbar {
  background: #fafafa;
  padding: 5px;
}

.toolbar > * {
  margin: 5px;
}
</style>
