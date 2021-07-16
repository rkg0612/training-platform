<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-cabinet-filing" />
        </span>
        <h3 class="kt-portlet__head-title">Category Manager</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :loading="loading.fetching"
        :headers="headers"
        :items="categories"
        :search="search"
        :server-items-length="pagination.total"
        :items-per-page="pagination.per_page"
        @update:page="changePage"
        sort-by="name"
        class="elevation-1"
      >
        <template v-slot:item.created_at="{ item }">{{
          item.created_at | displayDateTime
        }}</template>
        <template v-slot:top>
          <div class="row no-gutters px-3 mt-3">
            <div class="col-xl-4">
              <v-select
                :loading="loading.fetching"
                :disabled="loading.fetching"
                dense
                @change="fetchCategories"
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
            <div class="col-xl-7 mt-1">
              <div class="d-flex flex-wrap flex-sm-nowrap">
                <v-text-field
                  class="mt-0 pt-1 mr-0 mr-sm-5 w-100"
                  clearable
                  v-model.trim="search"
                  @change="fetchCategories"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                />
                <v-btn
                  class="mx-auto mt-3 mt-sm-0"
                  color="primary"
                  @click="categoryFormDialog = true"
                  text
                >
                  <v-icon class="mr-2" small>fal fa-plus</v-icon>
                  New Category
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
          <v-btn color="primary" @click="fetchCategories">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
    <v-dialog
      v-model="categoryFormDialog"
      hide-overlay
      transition="dialog-bottom-transition"
      :scrollable="true"
    >
      <v-card>
        <v-toolbar dark color="secondary" max-height="64px">
          <v-card-title>
            <i class="fal fa-cabinet-filing mr-2" />
            <span class="headline">{{ formTitle }}</span>
          </v-card-title>
          <v-spacer />
          <button
            type="button"
            class="btn btn-light btn-elevate-hover btn-circle btn-icon btn-sm"
            @click="close"
          >
            <i class="fal fa-times" />
          </button>
        </v-toolbar>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="6">
                <v-text-field
                  v-model="editedItem.name"
                  label="Category Name"
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
                  name="thumbnail"
                  v-model="editedItem.thumbnail"
                  label="Image"
                  accept="image/*"
                >
                </v-file-input>
                <a
                  v-if="editedIndex !== -1"
                  :href="editedItem.uploadedImageLabel"
                  target="_blank"
                  >Current Image</a
                >
                <span
                  class="kt-font-danger validate-error"
                  v-show="errors.has('thumbnail')"
                  >{{ errors.first('thumbnail') }}</span
                >
              </v-col>
              <v-col cols="12" sm="12" md="12">
                <label>Course</label>
                <vue-select
                  v-model="editedItem.course"
                  :options="courses"
                  v-validate="'required'"
                  label="name"
                  name="course"
                >
                </vue-select>
                <span
                  class="kt-font-danger validate-error"
                  v-show="errors.has('course')"
                  >{{ errors.first('course') }}</span
                >
              </v-col>
            </v-row>
          </v-container> </v-card-text
        >s
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
import { map as _map } from 'lodash-es';
import EventBus from '../../plugins/eventBus';

export default {
  name: 'CategorySection',
  components: {},
  data() {
    return {
      source: '',
      search: '',
      loading: {
        fetching: false,
        saving: false,
        courses: false,
      },
      courseList: [],
      categoryFormDialog: false,
      pagination: {
        total: 1,
        current_page: 1,
        per_page: 5,
      },
      headers: [
        {
          text: 'Category Name',
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
          text: 'Assigned in Course',
          align: 'center',
          sortable: true,
          value: 'course.name',
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
        course: '',
        thumbnail: [],
        uploadedImageLabel: '',
      },
      defaultItem: {
        id: '',
        name: '',
        course: '',
        thumbnail: [],
        uploadedImageLabel: '',
      },
      filters: [],
      defaultCourses: [],
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Category' : 'Edit Category';
    },
    courses() {
      return this.$store.state.courses.listCourses;
    },
    categories() {
      return this.$store.state.categories.listCategories;
    },
    users() {
      return this.$store.state.salespersonsAndManagers
        .listSalespersonAndManager;
    },
  },

  watch: {
    categoryFormDialog(val) {
      // eslint-disable-next-line
      val || this.close();
    },
  },

  created() {
    this.toggleAllFilter();
    this.fetchCategories();
    EventBus.$on('RELOAD_TABLE', () => {
      this.fetchCategories();
    });
  },

  methods: {
    toggleAllFilter() {
      this.filters = ['Active', 'Inactive'];
    },

    fetchCategories() {
      this.loading.fetching = true;
      const filter =
        this.filters.length >= 2
          ? 'all'
          : this.filters.toString().toLowerCase();
      const search = this.search !== null ? this.search : '';
      this.$store
        .dispatch('categories/getCategories', {
          pagination: this.pagination,
          filters: {
            status: filter,
            search: search,
          },
        })
        .then(({ data }) => {
          this.$store.commit('categories/setCategories', data.data);
          this.pagination.total = data.total;
          this.pagination.current_page = data.current_page;
          this.pagination.per_page = Number(data.per_page);
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => (this.loading.fetching = false));
    },

    changePage(page) {
      this.pagination.current_page = page;
      this.fetchCategories();
    },

    editItem(item) {
      this.editedIndex = this.categories.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.id = item.id;
      this.editedItem.thumbnail = [];
      this.editedItem.uploadedImageLabel = item.thumbnail;
      // eslint-disable-next-line no-shadow
      this.editedItem.course = item.course;
      this.categoryFormDialog = true;
    },

    deleteItem(item) {
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
            .dispatch('categories/deleteCategory', item.id)
            .then(() => {
              this.fetchCategories();
              this.$notify('success', 'Category removed!');
            });
        }
      });
    },

    restoreItem(item) {
      const { id } = item;
      this.$swal({
        title: 'Are you sure?',
        text: 'This action will put the category to active.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.value) {
          this.$store.dispatch('categories/restoreCategory', id).then(() => {
            this.$notify('success', 'Category restored');
            this.fetchCategories();
            EventBus.$emit('RELOAD_TABLE');
          });
        }
      });
    },

    close() {
      this.$validator.reset();
      this.categoryFormDialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    formData() {
      const form = new FormData();
      form.append('name', this.editedItem.name);
      form.append('course', this.editedItem.course.id);
      form.append('thumbnail', this.editedItem.thumbnail);
      return form;
    },

    store() {
      this.loading.saving = true;
      this.$store
        .dispatch('categories/saveCategory', this.formData())
        .then(() => {
          this.$notify('success', 'Category saved!');
          this.fetchCategories();
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
      const formData = this.formData();
      this.$store
        .dispatch('categories/editCategory', {
          payload: formData,
          id: this.editedItem.id,
        })
        .then(() => {
          this.$notify('success', 'Category updated!');
          this.fetchCategories();
          this.close();
        })
        .catch((error) => {
          this.interactWithErrorBag(error, this.errors);
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => (this.loading.saving = false));
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
        this.validate(this.update);
      } else {
        this.validate(this.store);
      }
    },
  },
};
</script>
<style lang="stylus">
.multiselect__tags
  border-radius 0px !important
  border 0px !important
  border-bottom 1px solid #777777 !important
.category-btn
  margin 0px 4px
@media screen and (max-width: 678px)
  .category-btn
    display block!important
    margin 4px auto
@media screen and (max-width: 599px)
  .category-btn
    display inline-block!important
    margin 0px 4px
</style>
