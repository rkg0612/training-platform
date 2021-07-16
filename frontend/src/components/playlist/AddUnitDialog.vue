<template>
  <v-dialog v-model="addUnitDialog" persistent max-width="600" scrollable>
    <v-card>
      <component-loader :active="loading" />
      <v-card-title class="headline">Add Unit</v-card-title>
      <v-card-text>
        <v-text-field
          label="Search"
          append-icon="fa-search"
          v-model="filter"
        ></v-text-field>
        <ul class="list-unstyled pl-1">
          <li
            v-for="(unit, index) in filteredUnits"
            :key="index"
            class="border-bottom"
            @click="toggleUnitSwitch(unit)"
          >
            <div class="row">
              <div class="col-md-3">
                <img
                  :src="unit.thumbnail"
                  alt="thumbnail"
                  style="width: 100%"
                />
              </div>
              <div class="col-md-9 pt-4 position-relative">
                <h6>{{ unit.name }}</h6>
                <p class="kt-font-info mb-1">{{ unit.module.name }}</p>
                <p class="mb-0">
                  <i class="far fa-clock" />
                  {{ unit.video_duration }}
                </p>
                <i
                  v-show="unit.checked"
                  class="fa fa-check position-absolute unitChecked kt-font-primary"
                />
              </div>
            </div>
          </li>
        </ul>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text @click="toggleAddUnit">Close</v-btn>
        <v-btn
          color="primary"
          text
          @click="addUnits"
          :loading="addUnitsLoader"
          :disabled="addUnitsLoader"
          >Add</v-btn
        >
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import {
  filter as _filter,
  includes as _includes,
  map as _map,
  findIndex as _findIndex,
} from 'lodash-es';

function initialState() {
  return {
    units: [],
    loading: true,
    filter: '',
    addUnitsLoader: false,
  };
}

export default {
  name: 'AddUnitDialog',
  props: {
    addUnitDialog: Boolean,
    toggleAddUnit: Function,
  },
  data: () => initialState(),
  computed: {
    filteredUnits() {
      if (this.filter === '') return this.units;

      return _filter(
        this.units,
        (s) =>
          _includes(s.name.toLowerCase(), this.filter.toLowerCase()) ||
          _includes(s.module.name.toLowerCase(), this.filter.toLowerCase())
      );
    },
    playlist() {
      return this.$store.state.playlist.playlist;
    },
  },
  methods: {
    toggleUnitSwitch(unit) {
      const index = _findIndex(this.units, unit);
      this.units[index].checked = !this.units[index].checked;
    },
    addUnits() {
      let units = _filter(this.units, (item) => item.checked);
      units = _map(units, (item) => item.id);

      if (units.length < 1) {
        return;
      }

      const unitWord = `unit${units.length > 1 ? 's' : ''}`;

      this.addUnitsLoader = true;
      this.$http
        .post('/playlist/add/unit', {
          playlist_id: this.playlist.id,
          units,
        })
        .then(({ data }) => {
          this.$notify(
            'success',
            `Successfully added the unit${unitWord} to the playlist`
          );
          this.addUnitsLoader = false;
          this.$store.dispatch('playlist/setPlaylist', data);
          this.toggleAddUnit();
        })
        .catch(() => {
          this.addUnitsLoader = false;
          this.$notify(
            'error',
            'Encountered an error while loading the playlists. Please try again'
          );
        });
    },
    loadUnits() {
      this.$http
        .get('/playlist/unit')
        .then(({ data }) => {
          this.loading = false;
          const list = data;

          list.map((item) => (item.checked = false));

          this.units = list;
        })
        .catch(() => {
          this.loading = false;
          console.log('Oops! looks like we got some errors here!');
        });
    },
  },
  watch: {
    addUnitDialog(show) {
      if (show) {
        this.loadUnits();
      } else {
        Object.assign(this.$data, initialState());
      }
    },
  },
};
</script>

<style scoped lang="stylus">
.unitChecked
  right 15px
  top 30px
  z-index 20
  font-size 20px

ul li
  cursor pointer
</style>
