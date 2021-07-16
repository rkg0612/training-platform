<template>
  <div class="kt-portlet kt-portlet--mobile kt-portletk-portlet--height-fluid">
    <component-loader :active="loading" />
    <div class="kt-portlet__head kt-portlet__head--noborder">
      <div class="kt-portlet__head-label"></div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-actions">
          <v-btn
            color=" light-blue accent-4"
            class="ma-2 white--text"
            v-on="on"
            @click.stop="showSharePlaylist = true"
            text
          >
            <v-icon right dark class="m-1"> fal fa-share-alt </v-icon>
            Share Playlist
          </v-btn>
          <v-btn
            color="light-blue darken-3"
            class="ma-2 white--text"
            @click.stop="showAssignPlaylist = true"
            text
          >
            <v-icon right dark class="m-1"> fal fa-users </v-icon>
            Assign Playlist
          </v-btn>

          <v-btn
            color="blue-grey"
            class="ma-2 white--text"
            @click.stop="showCreatePlaylist = true"
            text
          >
            <v-icon right dark class="m-1"> fal fa-plus </v-icon>
            Create New Playlist
          </v-btn>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body text-center">
      <assign-playlist v-model="showAssignPlaylist" :playlists="playlists" />
      <share-playlist v-model="showSharePlaylist" :playlists="playlists" />
      <create-playlist v-model="showCreatePlaylist" />
      <h3 class="playlist-heading">
        <i class="fal fa-play-circle"></i>
        Your Playlists
      </h3>
      <p class="text-center">
        Units you added to a playlist and playlists shared to you.
      </p>
      <v-divider></v-divider>
      <h5 v-if="playlists.length < 1">
        You currently don't have any playlist created.
      </h5>
      <div class="row" v-else>
        <div
          class="col-llg-4 col-md-6"
          v-for="(playlist, index) in playlists"
          :key="index"
        >
          <playlist-card :playlist="playlist" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { filter as _filter } from 'lodash-es';
import PlaylistCard from './PlaylistCard.vue';
import SharePlaylist from './dialog-box/SharePlaylist.vue';
import AssignPlaylist from './dialog-box/AssignPlaylist.vue';
import CreatePlaylist from './dialog-box/CreatePlaylist.vue';

export default {
  name: 'PlayList',
  components: {
    PlaylistCard,
    SharePlaylist,
    AssignPlaylist,
    CreatePlaylist,
  },
  data() {
    return {
      showAssignPlaylist: false,
      showSharePlaylist: false,
      showCreatePlaylist: false,
      on: '',
      loading: true,
    };
  },
  computed: {
    playlists() {
      return this.$store.state.lmsClient.playlists;
    },
  },
  methods: {
    loadPlaylists() {
      this.$store
        .dispatch('playlist/getPlaylists')
        .then(({ data }) => {
          this.loading = false;
          this.$store.dispatch('lmsClient/setPlaylists', data);
        })
        .catch(() => {
          this.loading = false;
          console.log(
            'Encountered an error while loading the playlists. Please try again'
          );
        });
    },
  },
  created() {
    this.loadPlaylists();
  },
};
</script>
<style lang="stylus">
.playlist-heading
  font-family Oswald
</style>
