<template>
  <div class="kt-grid__item">
    <component-loader :active="loading" />
    <div class="kt-portlet">
      <div class="kt-portlet__head kt-portlet__head--noborder">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title"></h3>
        </div>
      </div>
      <div class="kt-portlet__body kt-portlet__body--fit-y">
        <div class="kt-widget kt-widget--user-profile-1">
          <div class="kt-widget__head">
            <div class="kt-widget__media"></div>
            <div class="kt-widget__content pl-0" style="width: 100%">
              <div class="kt-widget__section">
                <h2
                  class="kt-widget__username"
                  v-show="!edit.name"
                  @click="toggleInlineEdit('name')"
                >
                  {{ playlist.name }}
                  <i class="fa fa-pencil float-right" v-show="isTheOwner"></i>
                </h2>
                <edit-text-field
                  :value="playlist.name"
                  :show="edit.name"
                  :toggleInlineEdit="toggleInlineEdit"
                  v-show="edit.name"
                />
                <span class="kt-widget__subtitle">
                  <i class="fa fa-user mr-1" /> {{ playlist.user.name }}
                </span>
                <p
                  class="kt-widget__subtitle"
                  v-show="!edit.description"
                  @click="toggleInlineEdit('description')"
                >
                  {{ description }}
                  <i class="fa fa-pencil float-right" v-show="isTheOwner"></i>
                </p>
                <edit-text-field
                  :value="playlist.description"
                  :show="edit.description"
                  :toggleInlineEdit="toggleInlineEdit"
                  v-show="edit.description"
                  textarea
                />
              </div>

              <div class="kt-widget__action">
                <v-btn
                  small
                  color="info"
                  class="my-1 mr-1"
                  @click="toggleAssignDialog"
                  text
                >
                  <v-icon left>fa-user-check</v-icon>
                  Assign
                </v-btn>
                <v-btn
                  v-if="this.$auth.user().id == playlist.user_id"
                  small
                  color="error"
                  class="my-1"
                  @click="toggleDeleteConfirmation"
                  :loading="deleteLoader"
                  :disabled="deleteLoader"
                  text
                >
                  <v-icon left>fa-trash</v-icon>
                  Delete
                </v-btn>
              </div>

              <div
                class="kt-separator kt-separator--space-lg kt-separator--border-dashed"
                v-show="showProgress"
              ></div>
              <h6 v-show="showProgress">Your progress</h6>
              <v-progress-linear
                height="25"
                v-show="showProgress"
                :value="progress"
                striped
              >
                <strong>{{ Math.ceil(progress) }}%</strong>
              </v-progress-linear>
            </div>
          </div>
        </div>
      </div>
    </div>
    <delete-confirm
      :toggleDeleteConfirmation="toggleDeleteConfirmation"
      :deleteConfirmDialog="deleteConfirmDialog"
      :deletePlaylist="deletePlaylist"
    />
    <assign-playlist
      :dialog="assignDialog"
      :toggleAssignDialog="toggleAssignDialog"
      :playlist="playlist"
    />
  </div>
</template>

<script>
import EditTextField from './EditTextField.vue';
import DeleteConfirm from './DeleteConfirm.vue';
import AssignPlaylist from './AssignPlaylist.vue';

export default {
  name: 'Aside',
  data() {
    return {
      loading: true,
      edit: {
        name: false,
        description: false,
      },
      deleteLoader: false,
      deleteConfirmDialog: false,
      assignDialog: false,
    };
  },
  components: {
    EditTextField,
    DeleteConfirm,
    AssignPlaylist,
  },
  computed: {
    playlist() {
      return this.$store.state.playlist.playlist;
    },
    user() {
      return this.$auth.user();
    },
    progress() {
      const { progress } = this.playlist;

      return Number(progress);
    },
    isTheOwner() {
      return this.playlist.user_id === this.$auth.user().id;
    },
    description() {
      return this.playlist.description !== ''
        ? 'Description'
        : this.playlist.description;
    },
    showProgress() {
      return this.playlist.user_id !== this.user.id;
    },
  },
  methods: {
    toggleInlineEdit(type) {
      if (!this.isTheOwner) {
        return;
      }
      this.edit[type] = !this.edit[type];
    },
    deletePlaylist() {
      this.deleteLoader = true;
      this.$store
        .dispatch('playlist/deletePlaylist', Number(this.$route.params.id))
        .then(() => {
          this.deleteLoader = false;
          this.$swal.fire({
            title: 'Successfully Deleted',
            icon: 'success',
          });
          window.location.href = '/';
        })
        .catch(() => {
          this.deleteLoader = false;
          this.$notify(
            'error',
            'Encountered an error while deleting this playlist. Please try again'
          );
        });
    },
    toggleDeleteConfirmation() {
      this.deleteConfirmDialog = !this.deleteConfirmDialog;
    },
    toggleAssignDialog() {
      this.assignDialog = !this.assignDialog;
    },
  },
  // eslint-disable-next-line consistent-return
  created() {
    if (this.$route.params.id === undefined) {
      return this.$router.push('404');
    }

    this.$http
      .get(`/playlist/${Number(this.$route.params.id)}`)
      .then(({ data }) => {
        this.loading = false;

        const list = data;
        list.units.map((item) => (item.checked = false));
        this.$store.dispatch('playlist/setPlaylist', list);
      })
      .catch(() => {
        this.loading = false;
        // window.location.href = '/404';
      });
  },
};
</script>

<style scoped></style>
