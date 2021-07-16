<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-books"></i>
        </span>
        <h3 class="kt-portlet__head-title">Module Manager</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="modules"
        sort-by="name"
        class="elevation-1"
        :items-per-page="pagination.per_page"
        :server-items-length="pagination.total"
        :loading="loading.fetching"
        :disabled="loading.fetching"
        @update:page="changePage"
        @update:sort-by="sort"
      >
        <template v-slot:top>
          <div class="row no-gutters px-3 mt-3">
            <div class="col-xl-4">
              <v-select
                :loading="loading.fetching"
                :disabled="loading.fetching"
                @change="fetchModules"
                dense
                v-model="filters"
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
                  <v-divider class="mt-2" />
                </template>
              </v-select>
            </div>
            <div class="col-xl-1"></div>
            <div class="col-xl-7">
              <div class="d-flex flex-wrap flex-sm-nowrap">
                <v-text-field
                  class="mt-0 pt-1 mr-0 mr-sm-5 w-100"
                  clearable
                  v-model.trim="search"
                  @change="fetchModules"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                />
                <v-btn
                  color="primary"
                  dark
                  class="mx-auto mt-3 mt-sm-0"
                  @click="moduleFormDialog = true"
                  text
                >
                  <v-icon class="mr-2" small>fal fa-plus </v-icon>
                  New Module
                </v-btn>
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
          <v-btn color="primary" @click="fetchModules">Reset</v-btn>
        </template>
        <template v-slot:item.created_at="{ item }">{{
          item.created_at | displayDateTime
        }}</template>
      </v-data-table>
    </div>
    <v-dialog
      v-model="moduleFormDialog"
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <v-card>
        <v-toolbar dark color="secondary">
          <v-card-title>
            <i class="fal fa-books mr-2"></i>
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
                  label="Module Name"
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
                <v-row>
                  <v-col cols="4">
                    <v-switch
                      v-model="fileUploadOrLink"
                      label="File Upload Or Link"
                    ></v-switch>
                  </v-col>
                  <v-col v-if="fileUploadOrLink">
                    <v-text-field
                      v-model="editedItem.link"
                      messages="Image or Video"
                      label="Module Banner Link"
                    ></v-text-field>
                    <span
                      class="kt-font-danger validate-error"
                      v-show="errors.has('link')"
                      >{{ errors.first('link') }}</span
                    >
                  </v-col>
                  <v-col v-if="!fileUploadOrLink">
                    <v-file-input accept="image/*" v-model="editedItem.image">
                    </v-file-input>
                    <span
                      class="kt-font-danger validate-error"
                      v-show="errors.has('image')"
                      >{{ errors.first('image') }}</span
                    >
                  </v-col>
                </v-row>
                <span v-if="editedIndex != -1">
                  <a :href="thumbnailLink" target="_blank">Current Image</a>
                </span>
              </v-col>
              <v-col cols="12" sm="12" md="6">
                <v-text-field
                  v-model="editedItem.call_guide_link"
                  messages="Image or Video"
                  label="Call Guide Link"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="6">
                <v-autocomplete
                  v-model="editedItem.category_id"
                  :items="categories"
                  :item-text="(item) => item.name"
                  :item-value="(item) => item.id"
                  :menu-props="{ closeOnClick: true }"
                  name="category_id"
                  label="Assign to Category"
                  v-validate="'required'"
                ></v-autocomplete>
                <span
                  class="kt-font-danger validate-error"
                  v-show="errors.has('category_id')"
                  >{{ errors.first('category_id') }}</span
                >
              </v-col>
              <v-col cols="12" sm="12" md="12">
                <div class="form-group">
                  <label>Module Description</label>
                  <word-editor
                    :getMyData="editedItem.description"
                    :secondValue.sync="editedItem.description"
                    v-model="editedItem.description"
                    v-validate="'required'"
                    name="description"
                  />
                  <span
                    class="kt-font-danger validate-error"
                    v-show="errors.has('description')"
                    >{{ errors.first('description') }}</span
                  >
                </div>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn color="blue-grey darken-1" text @click="close">Cancel</v-btn>
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
</template>
<script>
import {
  forEach as _forEach,
  map as _map,
  head as _head,
  sortBy as _sortBy,
} from 'lodash-es';
import WordEditor from '../WordEditor.vue';

export default {
  name: 'ModuleSection',
  components: {
    WordEditor,
  },
  data() {
    return {
      fileUploadOrLink: false,
      loading: {
        fetching: false,
        saving: false,
      },
      filters: '',
      search: '',
      moduleFormDialog: false,
      thumbnailLink: '',
      categories: [],
      headers: [
        {
          text: 'Module Name',
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
          text: 'Assigned Category',
          align: 'center',
          sortable: true,
          value: 'category.name',
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
        id: '',
        name: '',
        category_id: '',
        link: '',
        image: [],
        description: '',
        call_guide_link: '',
      },
      defaultItem: {
        id: '',
        name: '',
        category_id: '',
        link: '',
        image: [],
        description: '',
        call_guide_link: '',
      },
      pagination: {
        total: 1,
        per_page: 5,
        current_page: 1,
      },
      sortBy: [],
      noFileUpload: {
        display: false,
        message: '',
      },
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Module' : 'Edit Module';
    },
    modules() {
      if (this.sortBy) {
        return _sortBy(this.$store.state.modules.modulesList);
      }
      return this.$store.state.modules.modulesList;
    },
  },

  watch: {
    moduleFormDialog(val) {
      // eslint-disable-next-line
      val || this.close();
    },
  },

  created() {
    this.toggleAllFilter();
    this.fetchModules();

    this.$store.dispatch('categories/fetchBasicCategories').then(({ data }) => {
      this.categories = data;
    });
  },

  mounted() {
    this.$validator.localize('en', {
      attributes: {
        category_id: 'category',
      },
    });
  },

  methods: {
    sort(val) {
      this.sortBy = _head(val);
    },

    changePage(page) {
      this.pagination.current_page = page;
      this.fetchModules();
    },

    toggleAllFilter() {
      this.filters = ['Active', 'Inactive'];
    },

    fetchModules() {
      this.loading.fetching = true;
      const filter =
        this.filters.length >= 2
          ? 'all'
          : this.filters.toString().toLowerCase();
      const search = this.search !== null ? this.search : '';
      this.$store
        .dispatch('modules/getModules', {
          pagination: this.pagination,
          filters: {
            status: filter,
            search: search,
          },
        })
        .then(({ data }) => {
          this.$store.commit('modules/setModules', data.data);
          this.pagination.total = data.total;
          this.pagination.current_page = data.current_page;
          this.pagination.per_page = Number(data.per_page);
        })
        .finally(() => {
          this.loading.fetching = false;
        });
    },

    editItem(item) {
      this.editedIndex = this.modules.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.thumbnailLink = item.thumbnail;
      this.moduleFormDialog = true;
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
          this.$store.dispatch('modules/deleteModule', id).then(() => {
            this.$notify('success', 'Module removed!');
            this.fetchModules();
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
          this.$store.dispatch('modules/restoreModule', id).then(() => {
            this.$notify('success', 'Module restored');
            this.fetchModules();
            EventBus.$emit('RELOAD_TABLE');
          });
        }
      });
    },

    close() {
      this.$validator.reset();
      this.moduleFormDialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
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

    setFormData() {
      const formData = new FormData();
      formData.append('name', this.editedItem.name);
      formData.append('description', this.editedItem.description);
      formData.append('call_guide_link', this.editedItem.call_guide_link);
      formData.append('category_id', this.editedItem.category_id);

      if (this.fileUploadOrLink) {
        formData.append('link', this.editedItem.link);
      }

      if (!this.fileUploadOrLink) {
        formData.append('thumbnail', this.editedItem.image);
      }

      return formData;
    },

    store() {
      this.loading.saving = true;
      this.$store
        .dispatch('modules/saveModule', this.setFormData())
        .then(() => {
          this.$notify('success', 'Module saved!');
          this.fetchModules();
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
      const payload = this.setFormData();
      this.$store
        .dispatch('modules/editModule', {
          payload,
          id: this.editedItem.id,
        })
        .then(() => {
          this.$notify('success', 'Module updated!');
          this.fetchModules();
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
.module-btn
  margin 0px 4px
@media screen and (max-width: 604px)
  .module-btn
    display block!important
    margin 4px auto
@media screen and (max-width: 599px)
  .module-btn
    display inline-block!important
    margin 0px 4px
</style>
