<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-chalkboard-teacher"></i>
        </span>
        <h3 class="kt-portlet__head-title">LMS Course Manager</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :search="search"
        :headers="headers"
        :disabled="isFetching"
        :items="filteredCourses"
        :items-per-page="pagination.per_page"
        :server-items-length="pagination.total"
        @update:page="changePage"
        class="elevation-1"
        @update:sort-by="sort"
        no-results-text="No results..."
        :loading="isFetching"
      >
        <template v-slot:item.dealers="{ item }">
          <span>
            {{
              item.dealers
                .map(function (dealer) {
                  return dealer.name;
                })
                .toString()
            }}
          </span>
        </template>
        <template v-slot:top>
          <div flat color="white" class="w-100 pa-3">
            <div class="row no-gutters mt-3">
              <div class="col-xl-4">
                <v-select
                  :loading="isFetching"
                  :disabled="isFetching"
                  @change="fetchCourses"
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
                    <v-divider class="mt-2"></v-divider>
                  </template>
                </v-select>
              </div>
              <div class="col-xl-1"></div>
              <div class="col-xl-7">
                <div class="d-flex flex-wrap flex-sm-nowrap">
                  <div class="w-100">
                    <v-text-field
                      :clearable="true"
                      v-model.trim="search"
                      @change="fetchCourses"
                      append-icon="fal fa-search"
                      label="Search"
                      single-line
                      hide-details
                      class="mt-0 pt-0 mr-2"
                    />
                  </div>
                  <div
                    class="d-flex flex-wrap flex-sm-nowrap justify-content-center"
                  >
                    <v-btn
                      dark
                      color="blue-grey 3"
                      class="mr-1 mt-3 mt-sm-0"
                      to="/lms-admin/lms-template-builder"
                      text
                    >
                      <v-icon class="mr-2" small>fal fa-house-day</v-icon>LMS
                      Template Builder
                    </v-btn>
                    <v-btn
                      class="mt-3 mt-sm-0"
                      color="primary"
                      @click="dialog = true"
                      text
                    >
                      <v-icon class="mr-2" small>fal fa-plus</v-icon>New Course
                    </v-btn>
                  </div>
                </div>
              </div>
            </div>
            <v-dialog
              v-model="dialog"
              max-width="720px"
              :scrollable="true"
              @click:outside="close"
            >
              <v-card height="450px">
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col cols="12" sm="12" md="12">
                        <v-text-field
                          v-model="editedItem.name"
                          v-validate="'required'"
                          label="Course Name"
                          name="name"
                        />
                        <span
                          class="kt-font-danger validate-error"
                          v-show="errors.has('name')"
                          >{{ errors.first('name') }}</span
                        >
                      </v-col>
                      <v-col cols="12" sm="12" md="12">
                        <label>Assigned Dealer(s):</label>
                        <vue-select
                          v-model="editedItem.assignedDealer"
                          :options="dealers"
                          multiple
                          label="name"
                          v-validate="'required'"
                          name="assigned_dealers"
                        >
                        </vue-select>
                        <span
                          class="kt-font-danger validate-error"
                          v-show="errors.has('assigned_dealers')"
                          >{{ errors.first('assigned_dealers') }}</span
                        >
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue-grey darken-1" text @click="close"
                    >Cancel</v-btn
                  >
                  <v-btn
                    color="blue darken-1"
                    text
                    @click="save"
                    :loading="isSaving"
                    >Save</v-btn
                  >
                </v-card-actions>
              </v-card>
            </v-dialog>
          </div>
        </template>
        <template v-slot:item.created_at="{ item }">{{
          item.created_at | displayDateTime
        }}</template>
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
          <v-btn color="primary" @click="fetchCourses">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import {
  map as _map,
  head as _head,
  sortBy as _sortBy,
  findIndex as _findIndex,
} from 'lodash-es';
import EventBus from '../../plugins/eventBus';

export default {
  name: 'CourseSection',
  data() {
    return {
      filters: [],
      search: '',
      dealers: [],
      sortBy: [],
      isSaving: false,
      isFetching: false,
      pagination: {
        total: 1,
        per_page: 5,
        current_page: 1,
      },
      dialog: false,
      headers: [
        {
          text: 'Course Name',
          align: 'left',
          value: 'name',
          sortable: true,
        },
        {
          text: 'Date Created',
          align: 'center',
          value: 'created_at',
          sortable: true,
        },
        {
          text: 'Assigned Company',
          align: 'center',
          value: 'dealers',
          sortable: true,
        },
        // {
        //   text: "Status",
        //   align: "center",
        //   value: "status",
        //   sortable: true
        // },
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
        assignedDealer: '',
      },
      defaultItem: {
        name: '',
        assignedDealer: '',
      },
    };
  },

  created() {
    this.$validator.localize('en', {
      attributes: {
        course_name: 'course name',
        assignedDealer: 'assigned dealer(s)',
      },
    });
    this.filters = ['Active', 'Inactive'];
    this.$store.dispatch('dealers/getDealers').finally(() => {
      this.dealers = this.$store.state.dealers.dealers;
    });
    this.fetchCourses();
  },

  mounted() {
    this.$validator.localize('en', {
      attributes: {
        name: 'name',
        assigned_dealers: 'assigned dealer(s)',
      },
    });
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Course' : 'Edit Course';
    },
    filteredCourses() {
      if (this.sortBy) {
        return _sortBy(this.courses);
      }
      return this.courses;
    },
    courses() {
      return this.$store.state.courses.listCourses;
    },
  },

  watch: {
    dialog(val) {
      // eslint-disable-next-line
      val || this.close();
    },
  },

  methods: {
    toggleAllFilter() {
      this.filters = ['Active', 'Inactive'];
    },

    sort(val) {
      this.sortBy = _head(val);
    },

    fetchCourses() {
      this.isFetching = true;
      const filter =
        this.filters.length >= 2
          ? 'all'
          : this.filters.toString().toLowerCase();
      const search = this.search !== null ? this.search : '';
      this.$store
        .dispatch('courses/getCourses', {
          pagination: this.pagination,
          filters: {
            status: filter,
            search: search,
          },
        })
        .then(({ data }) => {
          this.$store.commit('courses/setCourses', data.data);
          this.pagination.total = data.total;
          this.pagination.current_page = data.current_page;
          this.pagination.per_page = Number(data.per_page);
        })
        .catch(() => {
          this.$notify(
            'info',
            'Ooops! Please add filter(s) to the course manager table.'
          );
          this.$store.commit('courses/setCourses', []);
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => (this.isFetching = false));
    },

    changePage(page) {
      this.pagination.current_page = page;
      this.fetchCourses();
    },

    editItem(item) {
      this.editedIndex = this.courses.indexOf(item);
      this.editedItem.name = item.name;
      const { dealers } = item;
      this.editedItem.assignedDealer = item.dealers;
      this.dialog = true;
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
          this.$store.dispatch('courses/deleteCourse', id).then(() => {
            this.$notify('success', 'Course removed!');
            this.fetchCourses();
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
          this.$store.dispatch('courses/restoreCourse', id).then(() => {
            this.$notify('success', 'Course restored');
            this.fetchCourses();
            EventBus.$emit('RELOAD_TABLE');
          });
        }
      });
    },

    close() {
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedIndex = -1;
      this.$validator.reset();
      this.dialog = false;
    },

    store() {
      const assignedDealers = _map(
        this.editedItem.assignedDealer,
        (assignedDealer) => {
          return assignedDealer.id;
        }
      );
      this.isSaving = true;
      this.$http
        .post('/lms-manager/courses', {
          name: this.editedItem.name,
          assigned_dealers: assignedDealers,
        })
        .then(({ data }) => {
          this.$store.dispatch('courses/addCourse', _head(data));
          this.$notify('success', 'Course added!');
          this.close();
        })
        .catch((errors) => {
          this.interactWithErrorBag(errors.response.data.errors, this.errors);
        })
        .finally(() => {
          this.isSaving = false;
          this.fetchCourses();
        });
    },

    update(index) {
      const { id } = this.courses[index];
      const payload = {
        id,
        name: this.editedItem.name,
        assigned_dealers: _map(
          this.editedItem.assignedDealer,
          (assignedDealer) => {
            return assignedDealer.id;
          }
        ),
      };
      this.isSaving = true;
      this.$http
        .patch(`/lms-manager/courses/${id}`, payload)
        .then(({ data }) => {
          const result = _head(data);
          this.$store.commit('courses/editCourse', {
            index,
            result,
          });
          this.$notify('success', 'Course updated!');
          this.close();
        })
        .catch((errors) => {
          this.interactWithErrorBag(errors, this.errors);
        })
        // eslint-disable-next-line no-return-assign
        .finally(() => {
          this.isSaving = false;
          this.fetchCourses();
        });
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
    },
  },
};
</script>
<style lang="stylus">
.multiselect__tags {
  border-radius: 0px !important;
  border: 0px !important;
  border-bottom: 1px solid #777777 !important;
}
</style>
