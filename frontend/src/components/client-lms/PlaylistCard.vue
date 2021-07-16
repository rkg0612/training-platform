<template>
  <v-card class="mx-auto text-center">
    <v-img
      :src="playlistThumbnail"
      min-height="200px"
      max-height="350px"
      v-if="playlistThumbnail !== ''"
      gradient="to top right, rgba(0,0,0,.70), rgba(25,32,72,.7)"
    />
    <div
      v-else
      class="empty-playlist d-flex flex-wrap justify-center align-center"
    >
      <h4>Empty playlist..</h4>
    </div>
    <v-card-subtitle>
      <router-link :to="{ name: 'Playlist', params: { id: playlist.id } }">
        <h4 class="mt-3 text-uppercase">{{ playlist.name }}</h4>
      </router-link>
      <div v-if="showAssignedInfo">
        <strong>Assigned by: {{ assignedPlaylist.user.name }}</strong>
        <p v-show="assignedPlaylist.due_date !== null">
          Due Date: {{ dueDate }}
        </p>
      </div>
    </v-card-subtitle>
    <router-link :to="{ name: 'Playlist', params: { id: playlist.id } }">
      <v-btn text small color="light-blue darken-4" class="mt-1 mb-5">
        <v-icon class="m-2"> fal fa-play </v-icon>
        Open
      </v-btn>
    </router-link>
    <v-btn
      v-if="this.$auth.user().id == playlist.user_id"
      dark
      small
      text
      color="red lighten-1"
      class="mt-1 ml-1 mb-5"
      @click="toggleDeleteConfirmation"
      :loading="deleteLoader"
      :disabled="deleteLoader"
    >
      <v-icon class="m-2"> fal fa-trash </v-icon>
      Delete
    </v-btn>
    <delete-confirm
      :toggleDeleteConfirmation="toggleDeleteConfirmation"
      :deleteConfirmDialog="deleteConfirmDialog"
      :deletePlaylist="deletePlaylist"
      :isLoading="deleteLoader"
    />
  </v-card>
</template>
<script>
import dayjs from 'dayjs';
import DeleteConfirm from '../playlist/DeleteConfirm.vue';

export default {
  props: {
    playlist: Object,
  },
  data: () => ({
    deleteLoader: false,
    deleteConfirmDialog: false,
  }),
  components: {
    DeleteConfirm,
  },
  computed: {
    playlistThumbnail() {
      const { units } = this.playlist;

      if (units === undefined || units.length < 1) {
        return '';
      }

      // fetch random unit
      const unit = units[Math.floor(Math.random() * units.length)];

      return unit.thumbnail;
    },
    user() {
      return this.$auth.user();
    },
    showAssignedInfo() {
      return this.playlist.user_id !== this.user.id;
    },
    assignedPlaylist() {
      return this.playlist.assigned_playlist[0];
    },
    dueDate() {
      return dayjs(this.assignedPlaylist.due_date).format('MM/DD/YYYY');
    },
  },
  methods: {
    deletePlaylist() {
      this.deleteLoader = true;
      this.$store
        .dispatch('playlist/deletePlaylist', this.playlist.id)
        .then(() => {
          this.toggleDeleteConfirmation();
          this.deleteLoader = false;
          this.$notify('success', 'Playlist removed!');
          this.$store.dispatch('lmsClient/deletePlaylist', this.playlist.id);
        })
        .catch(() => {
          this.deleteLoader = false;
          console.log(
            'Encountered an error while loading the playlists. Please try again'
          );
        });
    },
    toggleDeleteConfirmation() {
      this.deleteConfirmDialog = !this.deleteConfirmDialog;
    },
  },
};
</script>

<style scoped lang="stylus">
.empty-playlist
  width 100%
  height 200px
  background #fff
</style>
