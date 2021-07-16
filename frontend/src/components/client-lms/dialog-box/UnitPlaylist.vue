<template>
  <v-dialog v-model="showUnitPlaylist" persistent max-width="300px">
    <v-card>
      <component-loader :active="loader" />
      <v-card-title>
        <span class="headline"> Add to.. </span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12" sm="12" md="12" v-if="playlists.length > 0">
              <ul class="list-unstyled">
                <li>
                  <label class="kt-checkbox kt-checkbox--solid">
                    <input type="checkbox" v-model="selectAll" /> Select all
                    <span></span>
                  </label>
                </li>
                <li v-for="(playlist, index) in playlists" :key="index">
                  <label class="kt-checkbox kt-checkbox--solid">
                    <input type="checkbox" v-model="playlist.checked" />
                    {{ playlist.name }}
                    <span></span>
                  </label>
                </li>
              </ul>
            </v-col>
            <h5 v-else>Empty list, please create a playlist first.</h5>
          </v-row>
        </v-container>
      </v-card-text>
      <v-divider v-show="showCreatePlaylist"></v-divider>
      <v-card-text v-show="showCreatePlaylist">
        <v-text-field
          label="Name"
          v-model="newPlaylistName"
          ref="newPlaylistInput"
          name="name"
          :rules="[rules.required]"
          v-validate="'required'"
        ></v-text-field>
        <v-spacer />
        <v-btn
          color="blue-grey"
          text
          @click="toggleCreatePlaylist"
          small
          :loading="createPlaylistLoader"
          :disabled="createPlaylistLoader"
        >
          Cancel
        </v-btn>
        <v-btn color="primary" text @click="createPlaylist" small>
          Create
        </v-btn>
      </v-card-text>
      <v-divider v-show="!showCreatePlaylist"></v-divider>
      <v-card-actions v-show="!showCreatePlaylist">
        <v-btn color="primary" text @click="toggleCreatePlaylist">
          <i class="fa fa-plus mr-1"></i> New Playlist
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn color="blue-grey" text @click="showUnitPlaylist = false">
          Close
        </v-btn>
        <v-btn color="primary" text @click="addToPlaylists"> Save </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { filter as _filter } from 'lodash-es';

function initialState() {
  return {
    dueDateMenu: false,
    selectAll: false,
    showCreatePlaylist: false,
    newPlaylistName: '',
    rules: {
      required: (value) => !!value || 'Required.',
    },
    createPlaylistLoader: false,
    loader: true,
    addToPlaylistsLoader: false,
  };
}

export default {
  name: 'UnitPlaylist',
  props: {
    value: Boolean,
    unit: Object,
  },
  data: () => initialState(),
  computed: {
    showUnitPlaylist: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
    playlists() {
      return this.$store.state.lmsClient.playlists;
    },
  },
  methods: {
    toggleCreatePlaylist() {
      this.showCreatePlaylist = !this.showCreatePlaylist;
      this.$nextTick(() => this.$refs.newPlaylistInput.focus());
    },
    createPlaylist() {
      this.$validator.validateAll().then((valid) => {
        if (!valid) {
          return;
        }

        this.createPlaylistLoader = true;
        this.$http
          .post('/playlist', {
            name: this.newPlaylistName,
          })
          .then(({ data }) => {
            this.createPlaylistLoader = false;
            this.$store.dispatch('lmsClient/addPlaylist', data);
            this.toggleCreatePlaylist();
          })
          .catch(() => {
            this.createPlaylistLoader = false;
            console.log(
              'Encountered an error while saving the playlist. Please try again'
            );
          });
      });
    },
    loadPlaylists() {
      this.$store
        .dispatch('playlist/getPlaylists')
        .then(({ data }) => {
          this.loader = false;

          const list = data;
          list.map((item) => (item.checked = false));
          this.$store.dispatch('lmsClient/setPlaylists', list);
        })
        .catch(() => {
          this.loader = false;
          console.log(
            'Encountered an error while loading the playlists. Please try again'
          );
        });
    },
    addToPlaylists() {
      const playlists = _filter(this.playlists, (item) => item.checked);

      if (playlists.length < 1) {
        return;
      }

      const playlistWord = `playlist${playlists.length > 1 ? 's' : ''}`;

      this.addToPlaylistsLoader = true;
      this.$http
        .post('/playlist/unit', {
          unit_id: this.unit.id,
          playlists,
        })
        .then(({ data }) => {
          this.$notify(
            'success',
            `Successfully added the unit to the ${playlistWord}`
          );
          this.addToPlaylistsLoader = false;
          this.showUnitPlaylist = false;

          // eslint-disable-next-line array-callback-return
          playlists.map((item) => {
            this.$store.dispatch('lmsClient/addUnit', {
              id: item.id,
              unit: data,
            });
          });
        })
        .catch(() => {
          this.addToPlaylistsLoader = false;
          console.log(
            'Encountered an error while loading the playlists. Please try again'
          );
        });
    },
  },
  watch: {
    selectAll(enabled) {
      const { playlists } = this;

      playlists.map((item) => (item.checked = enabled));
      this.$store.dispatch('lmsClient/setPlaylists', playlists);
    },
    showUnitPlaylist(show) {
      if (show) {
        this.loadPlaylists();
      } else {
        Object.assign(this.$data, initialState());
      }
    },
  },
};
</script>

<style scoped lang="stylus">
ul li
  margin-bottom 10px
</style>
