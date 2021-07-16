<template>
  <v-row class="seriesRow">
    <div class="pl-2 toolbar">
      <i class="fa fa-plus" @click="addSeries" />
      <i class="fas fa-bring-forward ml-3" @click="cloneSeries" />
      <i class="fa fa-trash ml-3" @click="deleteSeries" />
    </div>
    <v-col cols="12" sm="12" md="6" class="seriesInput">
      <v-text-field
        v-model="series.name"
        label="Name of Section (Optional)"
      ></v-text-field>
    </v-col>
    <v-col cols="3" sm="3" md="6" class="seriesInput">
      <v-autocomplete
        v-model="series.highlight_unit"
        :items="units"
        item-text="name"
        item-value="id"
        :menu-props="{ closeOnClick: true }"
        label="Select a highlight unit"
        messages="A highlight unit is a unit that shows the thumbnail as a banner."
      ></v-autocomplete>
    </v-col>
    <v-col cols="9" sm="9" md="9" class="seriesInput">
      <v-autocomplete
        v-model="series.units"
        multiple
        :items="units"
        item-text="name"
        item-value="id"
        :menu-props="{ closeOnClick: true }"
        label="Select the Unit/s (arrange in order)"
      ></v-autocomplete>
    </v-col>
    <v-col cols="3" sm="3" md="3" class="seriesInput">
      <v-switch
        v-model="series.is_banner"
        class="ma-2"
        label="Banner"
      ></v-switch>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'ModuleSeries',
  props: {
    series: {
      required: true,
    },
    unitSection: {
      type: Array,
      required: true,
    },
    index: {
      type: Number,
      required: true,
    },
  },
  computed: {
    units() {
      return this.$store.state.moduleBuilds.listUnits;
    },
  },
  methods: {
    addSeries() {
      this.unitSection.push({
        name: '',
        highlight: null,
        units: [],
        is_banner: false,
      });
    },
    cloneSeries() {
      this.unitSection.push(this.series);
    },
    deleteSeries() {
      if (this.unitSection.length === 1) {
        return;
      }

      this.unitSection.splice(this.index, 1);
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
