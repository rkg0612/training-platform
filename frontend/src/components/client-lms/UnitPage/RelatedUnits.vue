<template>
  <v-card>
    <component-loader :active="isLoaded"></component-loader>
    <v-app-bar flat>
      <h3 class="m-0">Related Units</h3>
    </v-app-bar>
    <div class="mx-3 py-4">
      <v-text-field
        label="Search units in the module"
        prepend-icon="fal fa-search"
        v-model="search"
      ></v-text-field>
      <div class="search-con">
        <v-expansion-panels v-if="isPlaylist" flat focusable>
          <v-expansion-panel>
            <v-expansion-panel-header class="title">
              {{ relatedUnits.name }}
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-list-item
                v-for="unit in filteredUnits(relatedUnits.units)"
                :key="unit.id"
                :to="{
                  name: 'UnitPage',
                  params: { id: unit.id, playlistId: isPlaylist },
                }"
                link
              >
                <v-list-item-icon>
                  <v-icon v-if="unit.is_completed">fas fa-check</v-icon>
                  <v-icon v-else-if="!unit.is_completed"
                    >fas fa-circle-notch</v-icon
                  >
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title v-text="unit.name"></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
        <v-expansion-panels v-else v-model="panel" flat focusable>
          <v-expansion-panel
            value="1"
            multiple="true"
            v-for="series in relatedUnits.series"
            :key="series.id"
          >
            <v-expansion-panel-header class="title">
              <span color="grey darken-2">{{ series.name }}</span>
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-list-item
                v-for="unit in filteredUnits(series.units)"
                :key="unit.id"
                :to="{ name: 'UnitPage', params: { id: unit.id } }"
                link
              >
                <v-list-item-icon>
                  <v-icon v-if="unit.is_completed">fas fa-check</v-icon>
                  <v-icon v-else-if="!unit.is_completed"
                    >fas fa-circle-notch</v-icon
                  >
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title v-text="unit.name"></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </div>
    </div>
  </v-card>
</template>

<script>
import {
  map as _map,
  flatten as _flatten,
  filter as _filter,
  findIndex as _findIndex,
  isEmpty as _isEmpty,
} from 'lodash-es';

export default {
  name: 'RelatedUnits',
  data() {
    return {
      search: '',
      panel: '',
      playlistId: '',
    };
  },
  computed: {
    relatedUnits() {
      if (this.$route.params.playlistId) {
        return this.$store.state.relatedUnits.playlist;
      }
      return this.$store.state.relatedUnits.singleUnit;
    },
    isPlaylist() {
      return this.$route.params.playlistId;
    },
    isLoaded() {
      return !this.$store.state.relatedUnits.isLoaded;
    },
  },
  props: ['unitId'],
  created() {
    this.determineWhatPanel();
    if (!_isEmpty(this.$route.params.playlistId)) {
      this.playlistId = this.$route.params.playlistId;
    }
  },
  methods: {
    determineWhatPanel() {
      if (this.$store.state.relatedUnits.type === 'playlist') {
        return (this.panel = [0]);
      }
      for (
        let index = 0;
        index <= this.$store.state.relatedUnits.singleUnit.series.length - 1;
        index++
      ) {
        const isLocated = _findIndex(
          this.$store.state.relatedUnits.singleUnit.series[index].units,
          { id: this.unitId }
        );
        if (isLocated !== -1) {
          return (this.panel = index);
        }
      }
    },
    filteredUnits(series) {
      return _filter(series, (unit) => {
        return unit.name
          .toString()
          .toLowerCase()
          .includes(this.search.toLowerCase());
      });
    },
  },
};
</script>

<style scoped></style>
