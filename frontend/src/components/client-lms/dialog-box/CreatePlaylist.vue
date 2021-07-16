<template>
  <v-dialog v-model="showCreatePlaylist" persistent max-width="600px">
    <v-card>
      <v-card-title>
        <span class="headline">
          <v-icon class="ml-1">fal fa-plus</v-icon>
          Create Playlist
        </span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <v-text-field
                v-model="playlistName"
                label="Name of Playlist"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <v-autocomplete
                v-model="unitsSelected"
                :items="units"
                item-value="id"
                item-text="name"
                :menu-props="{ closeOnClick: true }"
                label="Select units by typing the name..."
                :search-input.sync="searchUnits"
                hide-no-data
                return-object
                multiple
                clearable
                @change="selectedUnitsChanged"
              ></v-autocomplete>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="save"> Save </v-btn>
        <v-btn color="blue-grey" text @click="showCreatePlaylist = false">
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { union as lodashUnion, map as lodashMap } from 'lodash-es';

export default {
  name: 'CreatePlaylist',

  props: {
    value: Boolean,
  },

  data() {
    return {
      playlistName: '',
      unitsSelected: '',
      searchUnits: null,
      unitsSearchResult: [],
    };
  },

  computed: {
    showCreatePlaylist: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
    units() {
      return lodashUnion(this.unitsSelected, this.unitsSearchResult);
    },
  },

  methods: {
    loadUnits(filter) {
      const params = {};
      if (filter) {
        params.filter = filter;
      }

      this.$http
        .get('/playlist/unit', { params })
        .then(({ data }) => {
          const list = data;

          list.map((item) => (item.checked = false));

          this.unitsSearchResult = list;
        })
        .catch(() => {
          console.log('Error loading the units. Please try again');
        });
    },
    save() {
      this.$http
        .post('/playlist/', {
          name: this.playlistName,
          units: lodashMap(this.unitsSelected, 'id'),
        })
        .then(({ data }) => {
          this.showCreatePlaylist = false;
          this.$notify('success', 'Successfully created the new playlist.');
          this.$store.dispatch('lmsClient/addPlaylist', data);
        })
        .catch(() => {
          this.$notify(
            'error',
            'Error saving the new playlist. Please try again'
          );
        });
    },
    selectedUnitsChanged() {
      this.searchUnits = '';
      // this.usersSearchResult = [];
    },
  },
  watch: {
    searchUnits(val) {
      if (val) {
        this.loadUnits(val);
      }
    },
  },
};
</script>
