<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-book"></i>
        </span>
        <h3 class="kt-portlet__head-title">Units Manager</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="units"
        class="elevation-1"
        :items-per-page="pagination.per_page"
        :server-items-length="pagination.total"
        :loading="loading.fetching"
        :disabled="loading.fetching"
        @update:page="changePage"
        :sort-by.sync="filters.sortBy"
        :sort-desc.sync="filters.sortDesc"
        @update:sort-desc="fetchUnits"
      >
        <template v-slot:top>
          <div class="row no-gutters px-3 mt-3">
            <div class="col-xl-4">
              <v-select
                :loading="loading.fetching"
                :disabled="loading.fetching"
                @change="fetchUnits"
                dense
                v-model="filters.status"
                multiple
                prepend-inner-icon="fas fa-filter"
                :items="['Active', 'Inactive']"
              >
                <template v-slot:prepend-item>
                  <v-list-item ripple @click="toggleAllFilter">
                    <v-list-item-content>
                      <v-list-item-title>All</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                  <v-divider class="mt-2"></v-divider>
                </template>
              </v-select>
            </div>
            <div class="col-xl-1"></div>
            <div class="col-xl-7">
              <div class="d-flex flex-wrap flex-sm-nowrap">
                <v-text-field
                  class="mt-0 pt-1 mr-0 mr-sm-5 w-100"
                  clearable
                  v-model.trim="filters.search"
                  @change="fetchUnits"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                />
                <v-dialog
                  v-model="unitFormDialog"
                  hide-overlay
                  transition="dialog-bottom-transition"
                >
                  <template v-slot:activator="{ on }">
                    <v-btn
                      color="primary"
                      dark
                      class="mx-auto mt-3 mt-sm-0"
                      v-on="on"
                      text
                    >
                      <v-icon class="mr-2" small>fal fa-plus</v-icon>
                      New unit
                    </v-btn>
                  </template>
                  <v-card>
                    <v-toolbar dark color="secondary">
                      <v-card-title>
                        <i class="fal fa-book mr-2"></i>
                        <span class="headline">{{ formTitle }}</span>
                      </v-card-title>
                      <v-spacer></v-spacer>
                      <button
                        type="button"
                        class="btn btn-light btn-elevate-hover btn-circle btn-icon btn-sm"
                        @click="close"
                      >
                        <i class="fal fa-times"></i>
                      </button>
                    </v-toolbar>

                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="12" sm="12" md="6">
                            <v-text-field
                              v-model="editedItem.name"
                              label="Unit Name"
                              name="name"
                              v-validate="'required'"
                            ></v-text-field>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('name')"
                              >{{ errors.first('name') }}</span
                            >
                          </v-col>
                          <v-col cols="12" sm="12" md="6">
                            <v-file-input
                              v-model="editedItem.thumbnail"
                              label="Thumbnail URL"
                              name="thumbnail"
                              accept="image/*"
                            ></v-file-input>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('thumbnail')"
                              >{{ errors.first('thumbnail') }}</span
                            >
                            <span v-if="editedIndex !== -1">
                              <a
                                :href="editedItem.uploadedImageLabel"
                                target="_blank"
                                >Current Image</a
                              >
                            </span>
                          </v-col>
                          <v-col cols="12" sm="12" md="4">
                            <multiselect
                              v-model="editedItem.quiz_id"
                              :options="quizzes"
                              label="title"
                              track-by="id"
                              searchable
                              placeholder="Select a Quiz"
                              :loading="quizPagination.loading"
                            >
                              <template
                                slot="singleLabel"
                                slot-scope="{ option }"
                              >
                                <strong>{{ option.title }}</strong>
                              </template>
                              <span slot="noOptions	">Search Quiz...</span>
                              <span slot="noResult">
                                Oops! No elements found. Consider changing the
                                search query.
                              </span>
                              <template slot="afterList">
                                <div
                                  v-observe-visibility="nextQuizPage"
                                  v-if="
                                    quizPagination.current !==
                                    quizPagination.last_page
                                  "
                                />
                              </template>
                            </multiselect>
                          </v-col>
                          <v-col cols="12" sm="12" md="12">
                            <v-textarea
                              v-model="editedItem.description"
                              label="Unit Description"
                              name="description"
                              v-validate="'required'"
                            ></v-textarea>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('description')"
                              >{{ errors.first('description') }}</span
                            >
                          </v-col>
                          <v-col cols="12" sm="12" md="6">
                            <v-select
                              v-model="editedItem.module_id"
                              name="module_id"
                              label="Assign to Module"
                              :items="modules"
                              :item-text="(item) => item.name"
                              :item-value="(item) => item.id"
                              v-validate="'required'"
                            ></v-select>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('module_id')"
                              >{{ errors.first('module_id') }}</span
                            >
                          </v-col>
                          <v-col cols="12" sm="12" md="6">
                            <v-text-field
                              v-model="editedItem.video_duration"
                              label="Video Duration"
                              name="video_duration"
                              v-validate="'required'"
                            ></v-text-field>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('video_duration')"
                              >{{ errors.first('video_duration') }}</span
                            >
                          </v-col>
                          <v-col cols="12" sm="12" md="6">
                            <v-text-field
                              v-model="editedItem.call_guide_link"
                              label="Call Guide Link"
                              name="call_guide_link"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="12" sm="12" md="6">
                            <v-combobox
                              v-model="editedItem.tags"
                              name="tags"
                              :items="tags"
                              :item-text="(item) => item.name"
                              :item-value="(item) => item.id"
                              multiple
                              chips
                              prepend-icon="fal fa-tags"
                              v-validate="'required'"
                              :menu-props="{ closeOnClick: true }"
                            ></v-combobox>
                            <span
                              class="kt-font-danger validate-error"
                              v-show="errors.has('tags')"
                              >{{ errors.first('tags') }}</span
                            >
                          </v-col>
                          <v-col cols="12" sm="12" md="12">
                            <div class="form-group">
                              <label>Unit Content</label>
                              <jodit-vue
                                v-model="editedItem.content"
                              ></jodit-vue>
                            </div>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>

                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn
                        color="blue-grey darken-1"
                        text
                        @click="close"
                        :disabled="loading.saving"
                        >Cancel</v-btn
                      >
                      <v-btn
                        color="blue darken-1"
                        text
                        @click="save"
                        :loading="loading.saving"
                        >Save</v-btn
                      >
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </div>
            </div>
          </div>
        </template>
        <template v-slot:item.action="{ item }">
          <template v-if="item.deleted_at === null">
            <v-btn text @click="editItem(item)" color="grey darken-3" small>
              <v-icon small>fal fa-edit</v-icon>
            </v-btn>
            <v-btn text @click="deleteItem(item)" color="red darken-3" small>
              <v-icon small>fal fa-trash</v-icon>
            </v-btn>
          </template>
          <template v-else>
            <v-btn text @click="restoreItem(item)" color="blue darken-3" small>
              <v-icon small class="mr-2">fal fa-trash-restore</v-icon>
              Restore
            </v-btn>
          </template>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="fetchUnits">Reset</v-btn>
        </template>
        <template v-slot:item.created_at="{ item }">{{
          item.created_at | displayDateTime
        }}</template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect';
import {
  forEach as _forEach,
  map as _map,
  head as _head,
  sortBy as _sortBy,
} from 'lodash-es';
import Summernote from '../Summernote';

export default {
  name: 'unitSection',

  components: {
    Multiselect,
  },

  data() {
    return {
      unitFormDialog: false,
      loading: {
        fetching: false,
        saving: false,
      },
      modules: [],
      salespersons: ['All', 'Salesperson A', 'Salesperson B', 'Salesperson C'],
      tags: [],
      headers: [
        {
          text: 'Unit Name',
          align: 'left',
          sortable: false,
          value: 'name',
        },
        {
          text: 'Date Created',
          align: 'center',
          sortable: true,
          value: 'created_at',
        },
        {
          text: 'Assigned Module',
          align: 'center',
          sortable: true,
          value: 'module.name',
        },
        {
          text: 'Action',
          align: 'center',
          value: 'action',
          width: 200,
          sortable: false,
        },
      ],
      editedIndex: -1,
      editedItem: {
        quiz_id: '',
        id: '',
        name: '',
        thumbnail: [],
        description: '',
        video_duration: '',
        call_guide_link: '',
        content: '',
        uploadedImage: [],
        tags: [],
        uploadedImageLabel: '',
      },
      defaultItem: {
        quiz_id: '',
        id: '',
        name: '',
        thumbnail: [],
        description: '',
        video_duration: '',
        call_guide_link: '',
        content: '',
        tags: [],
        uploadedImageLabel: '',
      },
      pagination: {
        total: 1,
        per_page: 5,
        current_page: 1,
      },
      search: '',
      filters: {
        status: '',
        sortBy: [''],
        sortDesc: [false],
        search: '',
      },
      quizzes: [],
      quizPagination: {
        current: 1,
        last_page: 10,
        loading: false,
      },
      fileUploadToggle: false,
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New unit' : 'Edit Unit';
    },
    units() {
      if (this.filters.sortBy) {
        return _sortBy(this.$store.state.units.unitsList);
      }
      return this.$store.state.units.unitsList;
    },
  },

  watch: {
    unitFormDialog(val) {
      // eslint-disable-next-line
      val || this.close();
    },
  },

  created() {
    this.toggleAllFilter();
    this.fetchUnits();
    this.$store.dispatch('units/fetchTags').then(({ data }) => {
      this.tags = data;
    });
    this.$store.dispatch('modules/fetchBasicModules').then(({ data }) => {
      this.modules = data;
    });
    this.fetchQuizzes();
  },

  methods: {
    changePage(page) {
      this.pagination.current_page = page;
      this.fetchUnits();
    },

    fetchQuizzes() {
      this.quizPagination.loading = true;
      this.axios
        .get(
          `lms-manager/builder/quizzes?page=${this.quizPagination.current}&status=Active&itemsPerPage=5`
        )
        .then(({ data }) => {
          _forEach(data.data, (quiz) => {
            this.quizzes.push(quiz);
          });
          this.quizPagination.current = data.current_page;
          this.quizPagination.last_page = data.last_page;
          this.quizPagination.loading = false;
        })
        .catch((error) => {
          this.quizPagination.loading = false;
        });
    },

    nextQuizPage() {
      this.quizPagination.current++;
      this.fetchQuizzes();
    },

    toggleAllFilter() {
      this.filters.status = ['Actve', 'Inactive'];
    },

    remove(item) {
      this.chips.splice(this.chips.indexOf(item), 1);
      this.chips = [...this.chips];
    },

    fetchUnits() {
      this.loading.fetching = true;
      let sortBy = this.filters.sortBy.length > 0 ? this.filters.sortBy[0] : '';
      let sortDesc =
        this.filters.sortDesc.length > 0 ? this.filters.sortDesc[0] : false;
      let status =
        this.filters.status.length >= 2
          ? 'all'
          : this.filters.status.toString().toLowerCase();
      const search = this.filters.search !== null ? this.filters.search : '';
      this.$store
        .dispatch('units/getUnits', {
          pagination: this.pagination,
          filter: {
            sortBy: sortBy,
            sortDesc: sortDesc,
            search: search,
            status: status,
          },
        })
        .then(({ data }) => {
          this.$store.commit('units/setUnits', data.data);
          this.pagination.total = data.total;
          this.pagination.current_page = data.current_page;
          this.pagination.per_page = Number(data.per_page);
        })
        .finally(() => {
          this.loading.fetching = false;
        });
    },

    editItem(item) {
      this.editedIndex = this.units.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.uploadedImageLabel = item.thumbnail;
      this.editedItem.thumbnail = [];
      this.editedItem.quiz_id = item.quiz;
      setTimeout(() => {
        this.unitFormDialog = true;
      }, 100);
    },

    deleteItem(item) {
      const { id } = item;
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
          this.$store.dispatch('units/deleteUnit', id).then(() => {
            this.$notify('success', 'Unit removed!');
            this.fetchUnits();
            EventBus.$emit('RELOAD_TABLE');
          });
        }
      });
    },

    restoreItem(item) {
      const { id } = item;
      this.$swal({
        title: 'Are you sure?',
        text: 'This action will put the module to active.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.value) {
          this.$store.dispatch('units/restoreUnit', id).then(() => {
            this.$notify('success', 'Unit restored');
            this.fetchUnits();
            EventBus.$emit('RELOAD_TABLE');
          });
        }
      });
    },

    close() {
      this.unitFormDialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 100);
    },

    validate(callback) {
      this.$validator.validate('*').then((result) => {
        if (result) {
          callback();
        }
      });
    },

    save() {
      if (this.editedIndex > -1) {
        // Object.assign(this.s[this.editedIndex], this.editedItem);
        this.validate(this.update);
      } else {
        this.validate(this.store);
      }
    },

    setForm() {
      const form = new FormData();
      form.append('name', this.editedItem.name);
      form.append('description', this.editedItem.description);
      form.append('video_duration', this.editedItem.video_duration);
      form.append('call_guide_link', this.editedItem.call_guide_link);
      form.append('content', this.editedItem.content);
      form.append('module_id', this.editedItem.module_id);
      if (this.editedItem.quiz_id) {
        form.append('quiz_id', this.editedItem.quiz_id.id);
      }
      _forEach(this.editedItem.tags, (tag) => {
        typeof tag === 'object'
          ? form.append('tags[]', tag.id)
          : form.append('tags[]', tag);
      });
      form.append('thumbnail', this.editedItem.thumbnail);

      console.log(form);

      return form;
    },

    store() {
      this.loading.saving = true;
      this.$store
        .dispatch('units/saveUnit', this.setForm())
        .then(() => {
          this.$notify('success', 'Unit saved!');
          this.fetchUnits();
          this.close();
        })
        .catch((error) => {
          this.interactWithErrorBag(error, this.errors);
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => (this.loading.saving = false));
    },

    update() {
      this.loading.saving = true;
      this.$store
        .dispatch('units/editUnit', {
          payload: this.setForm(),
          id: this.editedItem.id,
        })
        .then(() => {
          this.$notify('success', 'Unit updated!');
          this.fetchUnits();
          this.close();
        })
        .catch((error) => {
          this.interactWithErrorBag(error, this.errors);
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => (this.loading.saving = false));
    },
  },
};
</script>
<style lang="stylus">
.units-btn {
  margin: 0px 4px;
}

@media screen and (max-width: 634px) {
  .units-btn {
    display: block !important;
    margin: 4px auto;
  }
}

@media screen and (max-width: 599px) {
  .units-btn {
    display: inline-block !important;
    margin: 0px 4px;
  }
}
</style>
