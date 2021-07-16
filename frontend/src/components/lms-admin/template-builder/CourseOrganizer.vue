<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-books"></i>
        </span>
        <h3 class="kt-portlet__head-title">Course Organizer</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="courseBuilds"
        :search="search"
        :items-per-page="pagination.per_page"
        :server-items-length="pagination.total"
        sort-by="name"
        @update:page="changePage"
        no-results-text="No results..."
        :loading="isFetching"
        class="elevation-1"
      >
        <template v-slot:top>
          <div class="pa-3">
            <div class="row no-gutters">
              <div class="col-xl-4">
                <v-select
                  :loading="isFetching"
                  :disabled="isFetching"
                  @change="fetchBuilds"
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
                    <v-divider class="mt-2"></v-divider>
                  </template>
                </v-select>
              </div>
              <div class="col-xl-1"></div>
              <div class="col-xl-7">
                <div class="d-flex flex-wrap flex-sm-nowrap">
                  <v-text-field
                    v-model="search"
                    @change="fetchBuilds"
                    append-icon="fal fa-search"
                    label="Search"
                    single-line
                    hide-details
                    class="mr-0 mr-sm-5 w-100"
                  />
                  <v-dialog
                    v-model="courseFormDialog"
                    hide-overlay
                    transition="dialog-bottom-transition"
                  >
                    <template v-slot:activator="{ on }">
                      <v-btn
                        color="primary"
                        dark
                        class="mx-auto mt-3 mt-sm-0"
                        v-on="on"
                      >
                        <i class="fal fa-plus"></i>
                        New Course Build
                      </v-btn>
                    </template>
                    <v-card>
                      <v-toolbar dark color="secondary">
                        <v-card-title>
                          <i class="fal fa-list-ol mr-2"></i>
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
                            <v-col cols="12" sm="12" md="12">
                              <v-text-field
                                v-model="editedItem.name"
                                label="Course Build Name"
                                name="name"
                                v-validate="'required'"
                              ></v-text-field>
                              <span
                                class="kt-font-danger validate-error"
                                v-show="errors.has('name')"
                                >{{ errors.first('name') }}</span
                              >
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-autocomplete
                                v-model="editedItem.course_id"
                                :items="courses"
                                item-value="id"
                                item-text="name"
                                @change="fetchCategoriesByDealer"
                                :menu-props="{ closeOnClick: true }"
                                label="Select a Course"
                                name="course_id"
                                v-validate="'required'"
                              ></v-autocomplete>
                              <span
                                class="kt-font-danger validate-error"
                                v-show="errors.has('course_id')"
                                >{{ errors.first('course_id') }}</span
                              >
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-autocomplete
                                v-model="editedItem.categories"
                                multiple
                                :items="categories"
                                item-value="id"
                                item-text="name"
                                :item-disabled="
                                  (item) =>
                                    item.course_build !== null &&
                                    editedItem.id !==
                                      item.course_build.course_build_id
                                "
                                :menu-props="{ closeOnClick: true }"
                                label="Select the Categories (arrange in order)"
                                name="categories"
                                v-validate="'required'"
                              ></v-autocomplete>
                              <span
                                class="kt-font-danger validate-error"
                                v-show="errors.has('categories')"
                                >{{ errors.first('categories') }}</span
                              >
                            </v-col>
                            <!-- <v-col cols="12" sm="12" md="12">
                            <v-switch
                              v-model="editedItem.show_new_module"
                              class="ma-2"
                              label="Show New Module First"
                            >
                            </v-switch>
                          </v-col>-->
                          </v-row>
                        </v-container>
                      </v-card-text>

                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue-grey darken-1" text @click="close"
                          >Cancel</v-btn
                        >
                        <v-btn color="blue darken-1" text @click="save"
                          >Save</v-btn
                        >
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template v-slot:item.action="{ item }">
          <button
            type="button"
            class="btn btn-label-info btn-sm btn-icon mr-1"
            @click="editItem(item)"
          >
            <i class="fal fa-edit"></i>
          </button>
          <button
            v-if="item.deleted_at === null"
            type="button"
            class="btn btn-label-dark btn-sm btn-icon units-btn"
            @click="deleteItem(item)"
          >
            <i class="far fa-trash" />
          </button>
          <button
            v-if="item.deleted_at !== null"
            type="button"
            class="btn btn-label-dark btn-sm btn-icon"
            @click="restoreItem(item)"
          >
            <i class="far fa-trash-restore"></i>
          </button>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="fetchBuilds">Reset</v-btn>
        </template>
        <template v-slot:item.created_at="{ item }">
          {{ item.created_at | displayDateTime }}
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
export default {
  name: 'CourseOrganizer',

  data() {
    return {
      courseFormDialog: false,
      dealers: [
        'Kelly Nissan',
        'Kelly Mitsubishi',
        'Friendship Automotive Group',
        'Hudson Automotive Group',
      ],
      categories: [],
      headers: [
        {
          text: 'Name of Course',
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
          text: 'Action',
          align: 'center',
          value: 'action',
          width: 200,
          sortable: false,
        },
      ],
      editedIndex: -1,
      editedItem: {
        name: '',
        course_id: '',
        categories: [],
      },
      defaultItem: {
        name: '',
        course_id: '',
        categories: [],
      },
      filters: [],
      search: '',
      isSaving: false,
      isFetching: false,
      pagination: {
        total: 1,
        per_page: 5,
        current_page: 1,
      },
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Course Build' : 'Edit Course Build';
    },
    courseBuilds() {
      return this.$store.state.courseBuilds.listCourseBuilds;
    },
    courses() {
      return this.$store.state.moduleBuilds.listCourses;
    },
  },

  watch: {
    courseFormDialog(val) {
      // eslint-disable-next-line
      val || this.close();
    },
  },

  created() {
    this.toggleAllFilter();
    this.fetchBuilds();
  },

  methods: {
    toggleAllFilter() {
      this.filters = ['Active', 'Inactive'];
    },

    fetchCategoriesByDealer() {
      this.$store
        .dispatch('courseBuilds/fetchCategoriesByDealer', {
          course_id: this.editedItem.course_id,
        })
        .then(({ data }) => {
          console.log(data);
          this.categories = data;
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => (this.isFetching = false));
    },

    fetchBuilds() {
      this.isFetching = true;
      const filter =
        this.filters.length >= 2
          ? 'all'
          : this.filters.toString().toLowerCase();
      const search = this.search !== null ? this.search : '';
      this.$store
        .dispatch('courseBuilds/getBuilds', {
          payload: this.pagination,
          filter,
          search,
        })
        .then(({ data }) => {
          this.$store.commit('courseBuilds/setBuild', data.data);
          this.pagination.total = data.total;
          this.pagination.current_page = data.current_page;
          this.pagination.per_page = Number(data.per_page);
        })
        .catch(() => {
          this.$notify(
            'info',
            'Ooops! Please add filter(s) to the category builds table.'
          );
          this.$store.commit('courseBuilds/setBuild', []);
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => (this.isFetching = false));
    },

    remove(item) {
      this.chips.splice(this.chips.indexOf(item), 1);
      this.chips = [...this.chips];
    },

    editItem(item) {
      this.editedIndex = this.courseBuilds.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.categories = item.category_builds.map(
        (cat) => cat.category_build_id
      );
      console.log(this.editedItem);
      this.fetchCategoriesByDealer();
      this.courseFormDialog = true;
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
          this.$store.dispatch('courseBuilds/deleteBuild', id).then(() => {
            this.$notify('success', 'Category Build removed!');
            this.fetchBuilds();
            EventBus.$emit('RELOAD_TABLE');
          });
        }
      });
    },

    restoreItem(item) {
      const { id } = item;
      this.$swal({
        title: 'Are you sure?',
        text: 'This action will put the course to active.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
      }).then((result) => {
        if (result.value) {
          this.$store.dispatch('courseBuilds/restoreBuild', id).then(() => {
            this.$notify('success', 'Category Build restored');
            this.fetchCourses();
            EventBus.$emit('RELOAD_TABLE');
          });
        }
      });
    },

    close() {
      this.courseFormDialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    changePage(page) {
      this.pagination.current_page = page;
      this.fetchBuilds();
    },

    save() {
      this.$validator.validate('*').then((result) => {
        if (result) {
          if (this.editedIndex > -1) {
            this.update(this.editedIndex);
          } else {
            this.store();
          }
        }
      });
      this.close();
    },

    store() {
      this.isSaving = true;
      this.$store
        .dispatch('courseBuilds/saveBuild', this.editedItem)
        .then(({ data }) => {
          this.$notify('success', 'Course Build Added!');
          this.fetchBuilds();
          this.close();
        })
        .catch((error) => {
          this.displayErrors(error);
          console.log('Oops! looks like we got some errors here!');
        })
        .finally(() => {
          this.isSaving = false;
        });
    },

    update(index) {
      this.isSaving = true;
      this.$store
        .dispatch('courseBuilds/updateBuild', this.editedItem)
        .then(({ data }) => {
          this.$notify('success', 'Course Build Updated!');
          this.fetchBuilds();
          this.close();
        })
        .catch((error) => {
          this.displayErrors(error);
          console.log('Oops! looks like we got some errors here!');
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => {
          this.isSaving = false;
        });
    },
  },
};
</script>
