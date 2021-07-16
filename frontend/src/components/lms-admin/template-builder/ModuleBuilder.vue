<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-list-ol"></i>
        </span>
        <h3 class="kt-portlet__head-title">Module Organizer</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="moduleBuildList"
        :items-per-page="pagination.per_page"
        :server-items-length="pagination.total"
        :search="moduleSearch"
        @update:page="changePage"
        no-results-text="No results..."
        :loading="isFetching"
        sort-by="name"
        class="elevation-1"
      >
        <template v-slot:top>
          <div class="pa-3">
            <div class="row no-gutters">
              <div class="col-xl-4">
                <v-select
                  :loading="isFetching"
                  :disabled="isFetching"
                  @change="initialize"
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
                    v-model="moduleSearch"
                    append-icon="fal fa-search"
                    label="Search"
                    single-line
                    @change="initialize"
                    hide-details
                    class="mr-0 mr-sm-5 w-100"
                  />
                  <v-dialog
                    v-model="moduleFormDialog"
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
                        New Module Build
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
                                label="Module Build Name"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="6">
                              <v-autocomplete
                                v-model="editedItem.course_id"
                                :items="courses"
                                item-value="id"
                                item-text="name"
                                @change="getModulesOfSelectedCourse"
                                :menu-props="{ closeOnClick: true }"
                                label="Select a Course"
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="12" md="6">
                              <v-autocomplete
                                v-model="editedItem.module_id"
                                :items="modules"
                                item-value="id"
                                item-text="name"
                                @change="getUnitsOfSelectedModule"
                                :menu-props="{ closeOnClick: true }"
                                label="Select the Module to Build"
                              ></v-autocomplete>
                            </v-col>
                            <!-- <v-col cols="12" sm="12" md="6">
                            <v-autocomplete
                              v-model="editedItem.assigned_dealer"
                              multiple
                              :items="dealers"
                              :menu-props="{ closeOnClick: true }"
                              label="Assign to Company"
                            >
                            </v-autocomplete>
                          </v-col>
                          <v-col cols="12" sm="12" md="6">
                            <v-autocomplete
                              v-model="editedItem.specific_dealer"
                              multiple
                              :items="specific_dealers"
                              :menu-props="{ closeOnClick: true }"
                              label="Assign to Specific Dealer"
                            ></v-autocomplete>
                          </v-col>
                          <v-col cols="12" sm="12" md="6">
                            <v-autocomplete
                              v-model="editedItem.user"
                              multiple
                              :items="users"
                              :menu-props="{ closeOnClick: true }"
                              label="Only Assign to Specific User/s"
                            ></v-autocomplete>
                          </v-col>
                          <v-col cols="12" sm="12" md="6">
                            <v-autocomplete
                              v-model="editedItem.group"
                              multiple
                              :items="groups"
                              :menu-props="{ closeOnClick: true }"
                              label="Only Assign to Specific Group/s"
                            ></v-autocomplete>
                          </v-col>-->
                          </v-row>
                          <hr />
                          <h5 class="mt-5">BUILD SERIES SECTION</h5>
                          <template v-for="(series, index) in unitSection">
                            <module-series
                              :series="series"
                              :key="index"
                              :index="index"
                              :unitSection="unitSection"
                            />
                          </template>
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
            type="button"
            class="btn btn-label-dark btn-sm btn-icon mr-1"
            @click="deleteItem(item)"
          >
            <i class="far fa-trash"></i>
          </button>
        </template>
        <template v-slot:item.created_at="{ item }">
          {{ item.created_at | displayDateTime }}
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import { map as lodashMap, each as lodashEach } from 'lodash-es';
import ModuleSeries from './ModuleSeries.vue';

export default {
  name: 'ModuleBuilder',

  components: {
    ModuleSeries,
  },

  data() {
    return {
      moduleSearch: '',
      isFetching: false,
      moduleFormDialog: false,
      dealers: [
        'Kelly Nissan',
        'Kelly Mitsubishi',
        'Friendship Automotive Group',
        'Hudson Automotive Group',
      ],
      specific_dealers: ['All', 'Dealer A', 'Dealer B', 'Dealer C', 'Dealer D'],
      users: ['All', 'User A', 'User B', 'User C', 'User D'],
      groups: ['All', 'Group A', 'Group B', 'Group C', 'Group D'],
      modules: [],
      unitSection: [
        {
          name: '',
          highlight_unit: null,
          units: [],
          is_banner: false,
        },
      ],
      defUnitSection: [
        {
          name: '',
          highlight_unit: null,
          units: [],
          is_banner: false,
        },
      ],
      unitRender: true,
      headers: [
        {
          text: 'Name of Module Build',
          align: 'left',
          sortable: false,
          value: 'name',
        },
        {
          text: 'Name of Module',
          align: 'center',
          sortable: true,
          value: 'module.name',
        },
        {
          text: 'Name of Course',
          align: 'center',
          sortable: true,
          value: 'module.category.course.name',
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
        category_id: '',
        course_id: '',
        module_id: '',
      },
      defaultItem: {
        name: '',
        category_id: '',
        course_id: '',
        module_id: '',
      },
      pagination: {
        total: 1,
        per_page: 5,
        current_page: 1,
      },
      filters: [],
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Module Build' : 'Edit Module Build';
    },
    moduleBuildList() {
      return this.$store.state.moduleBuilds.listModuleBuilds;
    },
    courses() {
      return this.$store.state.moduleBuilds.listCourses;
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
    this.initialize();
  },

  methods: {
    toggleAllFilter() {
      this.filters = ['Active', 'Inactive'];
    },

    initialize() {
      this.isFetching = true;
      const filter =
        this.filters.length >= 2
          ? 'all'
          : this.filters.toString().toLowerCase();
      this.$store
        .dispatch('moduleBuilds/getBuilds', {
          payload: this.pagination,
          filter: filter,
          search: this.moduleSearch,
        })
        .then(({ data }) => {
          this.$store.commit('moduleBuilds/setBuild', data.data);
          this.pagination.total = data.total;
          this.pagination.current_page = data.current_page;
          this.pagination.per_page = Number(data.per_page);
        })
        .catch((error) => {
          console.error(error);
        });
      this.isFetching = false;
    },
    getModulesOfSelectedCourse() {
      this.modules = [];
      const payload = {
        course_id: this.editedItem.course_id,
      };
      this.$store
        .dispatch('moduleBuilds/fetchModulesByCourse', payload)
        .then(({ data }) => {
          for (let i = 0; i < data.length; i += 1) {
            this.modules.push(data[i]);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getUnitsOfSelectedModule() {
      const payload = {
        course_id: this.editedItem.course_id,
        module_id: this.editedItem.module_id,
      };
      this.$store
        .dispatch('moduleBuilds/fetchUnitsByModules', payload)
        .then(({ data }) => {
          this.$store.commit('moduleBuilds/setUnit', data);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    changePage(page) {
      this.pagination.current_page = page;
      this.initialize();
    },

    remove(item) {
      this.chips.splice(this.chips.indexOf(item), 1);
      this.chips = [...this.chips];
    },
    editItem(item) {
      console.log(item);
      const { series } = item;
      const tempUnits = [];
      const unitSection = [];
      for (let k = 0; k < series.length; k += 1) {
        lodashEach(series[k].units, (unit) => {
          tempUnits.push(unit.unit);
        });
        unitSection.push({
          name: series[k].name,
          units: lodashMap(series[k].units, 'unit_id'),
          is_banner: series[k].is_banner,
        });
      }
      this.unitSection = unitSection;
      this.unitSection.push(this.defUnitSection);
      // eslint-disable-next-line no-param-reassign
      item.course_id = item.module.category.course.id;
      this.editedIndex = this.moduleBuildList.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.getModulesOfSelectedCourse();
      this.modules.push(item.module);
      this.getUnitsOfSelectedModule();
      setTimeout(() => {
        lodashEach(tempUnits, (unit) => {
          this.$store.commit('moduleBuilds/addUnit', unit);
        });
      }, 3000);
      this.moduleFormDialog = true;
    },

    deleteItem(item) {
      const index = this.moduleBuildList.indexOf(item);
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
            .dispatch('moduleBuilds/deleteBuild', item.id)
            .then((response) => {
              console.log(response);
              this.$store.commit('moduleBuilds/deleteBuild', index);
            })
            .catch((err) => {
              console.log(err);
            });
        }
      });
    },

    close() {
      this.unitRender = false;
      this.$nextTick(() => {
        // Add the component back in
        this.unitRender = true;
        this.unitSection = [
          {
            name: '',
            highlight_unit: null,
            units: [],
            is_banner: false,
          },
        ];
      });
      this.editedItem = this.defaultItem;
      this.moduleFormDialog = false;
      this.editedIndex = -1;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
      }, 300);
    },

    save() {
      console.log(this.editedIndex);
      if (this.editedIndex > -1) {
        // Object.assign(this.moduleBuildList[this.editedIndex], this.editedItem);
        const formData = this.editedItem;
        formData.module_id = this.editedItem.module_id;
        formData.course_id = this.editedItem.course_id;
        formData.unitSection = this.unitSection; // weird assignment by vue-repeater
        this.$store
          .dispatch('moduleBuilds/updateBuild', formData)
          .then(({ data }) => {
            // if success commit the module build
            // clear the models
            console.log(data);
            this.$store.commit(
              'moduleBuilds/editBuild',
              data,
              this.editedIndex
            );
            this.close();
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        // can be reuse for update if time comes.
        const formData = this.editedItem;
        formData.module_id = this.editedItem.module_id;
        formData.course_id = this.editedItem.course_id;
        formData.unitSection = this.unitSection; // weird assignment by vue-repeater

        this.$store
          .dispatch('moduleBuilds/saveBuild', formData)
          .then(({ data }) => {
            // if success commit the module build
            // clear the models
            console.log(data);
            this.$store.commit('moduleBuilds/addBuild', data);
            this.close();
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
};
</script>
