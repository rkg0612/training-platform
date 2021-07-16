<template>
  <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
    <div class="row">
      <div class="col-xl-12">
        <div class="kt-portlet">
          <div class="kt-portlet__head px-3">
            <div class="kt-portlet__head-label">
              <v-btn
                small
                color="info"
                class="mr-1"
                @click="toggleAddUnit"
                text
              >
                <v-icon left>fa-plus</v-icon>
                Add Unit
              </v-btn>
              <v-btn small class="ml-1" @click="toggleEditMode" text>
                <v-icon left>
                  {{ !editMode ? 'fa-pencil' : 'fa-times' }}
                </v-icon>
                {{ !editMode ? 'Edit' : 'Close Edit' }}
              </v-btn>
              <v-btn
                color="error"
                small
                class="ml-2"
                @click="toggleDeleteConfirmation"
                v-show="editMode && unitsToDelete.length > 0"
                :loading="deleteUnitsLoader"
                :disabled="deleteUnitsLoader"
              >
                <v-icon left>fa-minus</v-icon>
                Delete
              </v-btn>
            </div>
            <div class="kt-portlet__head-toolbar">
              <div class="kt-portlet__head-actions">
                <button
                  type="button"
                  class="btn btn-icon"
                  @click="changeDisplayType('list')"
                  :class="displayType === 'list' ? 'btn-primary' : ''"
                >
                  <i class="fa fa-list" />
                </button>
                <button
                  type="button"
                  class="btn btn-outline btn-icon"
                  @click="changeDisplayType('grid')"
                  :class="displayType === 'grid' ? 'btn-primary' : ''"
                >
                  <i class="fa fa-th" />
                </button>
              </div>
            </div>
          </div>

          <form class="kt-form kt-form--label-right">
            <div class="kt-portlet__body">
              <div class="kt-section kt-section--first">
                <p class="grey lighten-3 p-3" dark>
                  If you're the creator of the playlist you can organize the
                  videos by clicking on the edit button at the top then drag and
                  drop the videos.
                </p>
                <div class="kt-section__body">
                  <draggable
                    :list="playlistOrder"
                    :disabled="isDraggable"
                    :move="checkMove"
                    @start="dragging = true"
                    @end="dragging = false"
                    v-if="displayType === 'list'"
                  >
                    <div
                      v-for="(unit, index) in units"
                      class="row position-relative"
                      :key="index"
                      id="list-view"
                    >
                      <div class="col-md-3">
                        <router-link
                          :to="{
                            name: 'UnitPage',
                            params: { id: unit.id, playlistId: playlist.id },
                          }"
                          class="position-relative"
                        >
                          <v-chip
                            class="ma-2 position-absolute"
                            color="yellow darken-3"
                            dark
                            v-show="showCompletedUnit(unit)"
                          >
                            <v-avatar left class="yellow darken-4">
                              <i class="fas fa-badge-check m-1"></i>
                            </v-avatar>
                            Completed
                          </v-chip>
                          <img
                            :src="unit.thumbnail"
                            class="img-fluid unit-done"
                            alt="thumbnail"
                          />
                        </router-link>
                      </div>
                      <div class="col-md-9">
                        <router-link
                          :to="{
                            name: 'UnitPage',
                            params: { id: unit.id, playlistId: playlist.id },
                          }"
                        >
                          <h5>{{ unit.name }}</h5>
                          <p>{{ unit.module.name }}</p>
                        </router-link>
                        <p class="mfo">
                          <i class="far fa-clock" />
                          {{ unit.video_duration }}
                        </p>
                        <p>{{ unit.description }}</p>
                      </div>
                      <i
                        class="btn-font-primary delete-checkbox position-absolute"
                        :class="
                          unit.checked ? 'fa fa-check-circle' : 'far fa-circle'
                        "
                        @click="toggleDelete(index)"
                        v-show="editMode"
                      />
                    </div>
                  </draggable>
                  <template v-else>
                    <draggable
                      @start="dragging = true"
                      @end="dragging = false"
                      :list="playlistOrder"
                      :disabled="isDraggable"
                      :move="checkMove"
                      class="row position-relative"
                      id="grid-view"
                    >
                      <div
                        v-for="(unit, index) in units"
                        class="col-4"
                        :key="index"
                      >
                        <router-link
                          :to="{
                            name: 'UnitPage',
                            params: { id: unit.id, playlistId: playlist.id },
                          }"
                        >
                          <v-chip
                            class="ma-2 position-absolute completed-chip"
                            color="yellow darken-3"
                            dark
                            v-show="showCompletedUnit(unit)"
                          >
                            <v-avatar left class="yellow darken-4">
                              <i class="fas fa-badge-check m-1"></i>
                            </v-avatar>
                            Completed
                          </v-chip>
                          <div class="col-md-12 position-relative">
                            <img
                              :src="unit.thumbnail"
                              class="img-fluid unit-done"
                              alt="thumbnail"
                            />
                          </div>
                          <div class="col-md-12">
                            <h5 class="text-center">{{ unit.name }}</h5>
                            <p class="text-center">
                              <i class="far fa-clock" />
                              {{ unit.video_duration }}
                            </p>
                          </div>
                        </router-link>
                        <i
                          class="btn-font-primary delete-checkbox position-absolute"
                          :class="
                            unit.checked
                              ? 'fa fa-check-circle'
                              : 'far fa-circle'
                          "
                          @click="toggleDelete(index)"
                          v-show="editMode"
                        />
                      </div>
                    </draggable>
                  </template>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <add-unit-dialog
      :addUnitDialog="addUnitDialog"
      :toggleAddUnit="toggleAddUnit"
    />
    <delete-units-confirm
      :toggleDeleteConfirmation="toggleDeleteConfirmation"
      :deleteConfirmDialog="deleteConfirmDialog"
      :deleteUnits="deleteUnits"
      :unitsToDelete="unitsToDelete"
    />
  </div>
</template>

<script>
import draggable from 'vuedraggable';
import {
  filter as _filter,
  includes as _includes,
  find as _find,
} from 'lodash-es';
import AddUnitDialog from './AddUnitDialog.vue';
import DeleteUnitsConfirm from './DeleteUnitsConfirm.vue';

export default {
  name: 'Body',
  props: {
    playlistType: String,
  },
  components: {
    AddUnitDialog,
    DeleteUnitsConfirm,
  },
  data: () => ({
    playlistOrder: [],
    displayType: 'list',
    addUnitDialog: false,
    editMode: false,
    isDraggable: true,
    dragging: false,
    deleteConfirmDialog: false,
    deleteUnitsLoader: false,
  }),
  computed: {
    playlist() {
      return this.$store.state.playlist.playlist;
    },
    units() {
      return this.playlist.units;
    },
    unitsToDelete() {
      return _filter(this.units, (item) => item.checked);
    },
    assignedPlaylist() {
      if (
        this.playlist.assigned_playlist === undefined ||
        this.playlist.assigned_playlist.length < 1
      ) {
        return [];
      }

      return this.playlist.assigned_playlist[0];
    },
  },
  methods: {
    checkMove(e) {
      window.console.log('Future index: ' + e.draggedContext.futureIndex);
    },
    changeDisplayType(type) {
      this.displayType = type;
    },
    toggleAddUnit() {
      this.addUnitDialog = !this.addUnitDialog;
    },
    toggleDelete(index) {
      this.$store.dispatch('playlist/toggleUnitDelete', index);
    },
    toggleEditMode() {
      this.playlistOrder = this.units;

      this.editMode = !this.editMode;

      if (this.isOwner()) {
        this.isDraggable = !this.isDraggable;
      }

      if (!this.editMode && this.playlist.user_id === this.$auth.user().id) {
        this.isDraggable = true;
        this.axios
          .patch(
            `playlist/update-order/${this.playlist.id}`,
            this.playlistOrder
          )
          .then(({ data }) => {
            if (data) {
              this.$notify('success', 'Playlist updated');
            }
          });
      }
    },
    isOwner() {
      return this.playlist.user_id === this.$auth.user().id;
    },
    toggleDeleteConfirmation() {
      this.deleteConfirmDialog = !this.deleteConfirmDialog;
    },
    deleteUnits() {
      if (this.unitsToDelete.length < 1) {
        return;
      }

      this.deleteUnitsLoader = true;
      this.toggleDeleteConfirmation();
      this.$http
        .post('/playlist/delete/unit', {
          playlist_id: this.playlist.id,
          units: this.unitsToDelete,
        })
        .then(() => {
          this.deleteUnitsLoader = true;
          this.$store.dispatch('playlist/deleteUnits', this.unitsToDelete);
          this.$notify('success', 'Successfully deleted');
        })
        .catch(() => {
          this.deleteUnitsLoader = true;
          console.log('Oops! looks like we got some errors here!');
        });
    },
    showCompletedUnit(unit) {
      if (this.assignedPlaylist.length < 1) {
        return false;
      }

      const find = _find(this.assignedPlaylist.assigned_playlist_unit, {
        unit_id: unit.id,
      });
      return find !== undefined;
    },
  },
};
</script>

<style scoped lang="stylus">
i.delete-checkbox
  cursor pointer

#list-view i.delete-checkbox
  right 0
  font-size 25px
  top 45px

#grid-view i.delete-checkbox
  right 15px
  font-size 20px
  bottom 35px

.completed-chip
  z-index 2

#grid-view .completed-chip
  left 20px
  top 25px
</style>
