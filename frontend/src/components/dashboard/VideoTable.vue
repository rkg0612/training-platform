<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="far fa-video"></i>
        </span>
        <h3 class="kt-portlet__head-title">Recent Video of the Day</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="videos"
        sort-by="name"
        class="elevation-1"
        :server-items-length="total"
        :loading="isFetching"
        :items-per-page="5"
        @update:page="changePage"
      >
        <template v-slot:top>
          <v-toolbar flat color="white" max-height="20">
            <v-dialog v-model="videoForm" max-width="500px" scrollable>
              <v-card>
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <form class="kt-form">
                      <div class="form-group">
                        <label>Name</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Title of the Video"
                          v-model="editedItem.name"
                          required
                          v-validate="'required'"
                          name="name"
                        />
                        <span class="kt-font-danger validate-error">
                          {{ errors.first('name') }}
                        </span>
                      </div>
                      <div class="form-group">
                        <label>Video URL</label>
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Enter Video URL"
                          v-model="editedItem.videoUrl"
                          required
                          v-validate="'required|url'"
                          name="url"
                        />
                        <span
                          class="kt-font-danger validate-error"
                          v-show="errors.has('url')"
                        >
                          {{ errors.first('url') }}
                        </span>
                      </div>
                      <div class="form-group">
                        <label>Start Date</label>
                        <VueCtkDateTimePicker
                          v-model="editedItem.startDate"
                          name="start_at"
                          no-clear-button
                          v-validate="'required'"
                        >
                        </VueCtkDateTimePicker>
                        <span class="kt-font-danger validate-error">
                          {{ errors.first('start_at') }}
                        </span>
                      </div>
                      <div class="form-group">
                        <label>End Date</label>
                        <VueCtkDateTimePicker
                          v-model="editedItem.endDate"
                          name="end_at"
                          no-clear-button
                          v-validate="'required'"
                        >
                        </VueCtkDateTimePicker>
                        <span class="kt-font-danger validate-error">
                          {{ errors.first('end_at') }}
                        </span>
                      </div>
                      <div class="form-group">
                        <multiselect
                          v-model="editedItem.relatedUnits"
                          :options="relatedUnits"
                          :multiple="true"
                          :loading="isSearchingUnits"
                          :searchable="true"
                          @search-change="getUnits"
                          track-by="id"
                          label="name"
                          searchable
                          placeholder="Select related units"
                        >
                          <template slot="singleLabel" slot-scope="{ option }">
                            <strong>{{ option.name }}</strong>
                          </template>
                          <span slot="noOptions	">Search related units...</span>
                          <span slot="noResult"
                            >Oops! No elements found. Consider changing the
                            search query.</span
                          >
                        </multiselect>
                      </div>
                      <div class="kt-form__actions text-center">
                        <v-btn
                          color="blue darken-2"
                          dark
                          :loading="isLoading"
                          @click="save"
                        >
                          Save
                        </v-btn>
                        &nbsp;
                        <v-btn color="grey lighten-1" dark @click="close">
                          Close
                        </v-btn>
                      </div>
                    </form>
                  </v-container>
                </v-card-text>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.end_at="{ item }">
          <span>{{ item.end_at | displayDateTime }}</span>
        </template>
        <template v-slot:item.start_at="{ item }">
          <span>{{ item.start_at | displayDateTime }}</span>
        </template>
        <template v-slot:item.related_units="{ item }">
          <span>{{ printRelatedUnits(item.related_units) }}</span>
        </template>
        <template v-slot:item.action="{ item }">
          <button
            type="button"
            class="btn btn-label-info btn-sm btn-icon"
            @click="editItem(item)"
          >
            <i class="fal fa-edit" />
          </button>
          &nbsp;
          <button
            class="btn btn-label-dark btn-sm btn-icon"
            @click="deleteItem(item)"
          >
            <i class="far fa-trash" />
          </button>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import dayjs from 'dayjs';
import { forEach as _forEach, head as _head, map as _map } from 'lodash-es';
import EventBus from '../../plugins/eventBus';

export default {
  name: 'VideoTable',
  data() {
    return {
      isSearchingUnits: false,
      isFetching: false,
      videoForm: false,
      options: ['unit 1', 'unit 2', 'unit 3'],
      headers: [
        {
          text: 'Title',
          sortable: true,
          align: 'left',
          value: 'title',
          width: 200,
        },
        {
          text: 'URL',
          align: 'center',
          value: 'url',
          width: 300,
        },
        {
          text: 'Start Date',
          sortable: true,
          align: 'center',
          value: 'start_at',
          width: 200,
        },
        {
          text: 'End Date',
          align: 'center',
          value: 'end_at',
          width: 200,
        },
        {
          text: 'Related Units',
          align: 'center',
          value: 'related_units',
          width: 300,
        },
        {
          text: 'Actions',
          align: 'center',
          value: 'action',
          width: 150,
        },
      ],
      editedIndex: -1,
      editedItem: {
        id: '',
        name: '',
        videoUrl: '',
        startDate: this.$currentDate,
        endDate: this.$currentDate,
        relatedUnits: [],
      },
      relatedUnits: [],
      defaultItem: {
        title: '',
        videoUrl: '',
        start_at: '',
        end_at: '',
      },
      isLoading: false,
      current_page: 1,
      source: '',
      selectedRelatedUnits: [],
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New' : 'Edit';
    },
    videos() {
      return this.$store.state.videosDaily.videos;
    },
    total() {
      return this.$store.state.videosDaily.total
        ? this.$store.state.videosDaily.total
        : 0;
    },
  },

  watch: {
    videoForm(val) {
      this.$validator.reset();
      // eslint-disable-next-line no-unused-expressions
      val || this.close();
    },
  },

  mounted() {
    this.$validator.localize('en', {
      attributes: {
        name: 'name',
        videoUrl: 'video url',
      },
    });
    EventBus.$on('refreshVideoTable', () => {
      this.initialize();
    });
  },

  created() {
    this.initialize();
  },

  methods: {
    getUnits(search) {
      this.isSearchingUnits = true;
      if (this.source) {
        this.source.cancel();
      }
      this.source = this.axios.CancelToken.source();
      try {
        this.axios
          .get(`featured-video/related-units`, {
            params: {
              search,
            },
            cancelToken: this.source.token,
          })
          .then(({ data }) => {
            this.relatedUnits = data;
            this.isSearchingUnits = false;
          });
      } catch (e) {
        this.relatedUnits = this.selectedRelatedUnits;
        this.isSearchingUnits = false;
      }
    },
    initialize() {
      this.isFetching = true;
      this.axios
        .get('/featured-videos/', {
          params: {
            page: this.current_page,
          },
        })
        .then(({ data }) => {
          this.$store.dispatch('videosDaily/setList', data);
        })
        .finally(() => {
          this.isFetching = false;
        });
    },
    changePage(el) {
      this.isFetching = true;
      this.current_page = el;
      this.axios
        .get('/featured-videos/', {
          params: {
            page: this.current_page,
          },
        })
        .then(({ data }) => {
          this.$store.dispatch('videosDaily/setList', data);
        })
        .finally(() => (this.isFetching = false));
    },
    assignItem(item) {
      this.editedItem.id = item.id;
      this.editedItem.name = item.title;
      this.editedItem.videoUrl = item.url;
      this.editedItem.endDate = item.end_at;
      this.editedItem.startDate = item.start_at;
      this.selectedRelatedUnits = item.related_units;
      this.relatedUnits = this.selectedRelatedUnits;
      this.editedItem.relatedUnits = item.related_units;
    },
    editItem(item) {
      this.videoForm = true;
      this.assignItem(this.videos[this.videos.indexOf(item)]);
    },
    deleteItem(item) {
      const index = this.videos.indexOf(item);
      this.$swal({
        title: 'Are you sure?',
        text: "You can't revert this action.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it.',
        showCloseButton: true,
      }).then((result) => {
        if (result.value) {
          this.$store
            .dispatch('videosDaily/deleteVideo', this.videos[index].id)
            .then(() => {
              this.$store.commit('videosDaily/removeVideo', index);
              this.$notify('success', 'Video removed successfully!');
              this.initialize();
            })
            .catch((error) => console.log(error));
        }
      });
    },
    close() {
      this.videoForm = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
    save() {
      this.$validator.validate('*').then((result) => {
        // eslint-disable-next-line no-unused-expressions
        result ? this.send() : this.checkForErrors();
      });
    },
    send() {
      const { relatedUnits } = this.editedItem;
      const data = {
        id: this.editedItem.id,
        name: this.editedItem.name,
        videoUrl: this.editedItem.videoUrl,
        startDate: this.editedItem.startDate,
        endDate: this.editedItem.endDate,
        relatedUnits: _map(relatedUnits, (unit) => {
          return unit.id;
        }),
      };
      this.isLoading = true;
      this.axios
        .patch(`/featured-videos/${data.id}`, data)
        .then(() => {
          this.initialize();
          this.$notify('success', 'Daily video updated!');
          this.close();
        })
        .catch((errors) => {
          this.interactWithErrorBag((errors, this.errors));
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    printRelatedUnits(units) {
      let unitsTitle = '';
      if (Array.isArray(units)) {
        let unitsLength = units.length;
        while (unitsLength--) {
          unitsLength === 0
            ? (unitsTitle += units[unitsLength].name)
            : (unitsTitle += units[unitsLength].name + ', ');
        }
      }
      return unitsTitle;
    },
  },
};
</script>
<style lang="stylus">
.v-btn--icon.v-size--default .v-icon, .v-btn--fab.v-size--default .v-icon
  font-size 14px

.v-data-table td
  font-size 12px

.validate-error
   font-size 12px

 .v-data-footer__select
   display none
</style>
