<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-layer-group"></i>
        </span>
        <h3 class="kt-portlet__head-title">Sidebar Builder</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="sideBars"
        :search="sideBarBuilds"
        sort-by="name"
        class="elevation-1"
      >
        <template v-slot:top>
          <div class="pa-3">
            <div class="row no-gutters">
              <div class="col-xl-4">
                <v-select
                  :loading="isFetchingRecords"
                  :disabled="isFetchingRecords"
                  @change="fetchData"
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
                    v-model="sideBarSearch"
                    append-icon="fal fa-search"
                    label="Search"
                    single-line
                    hide-details
                    class="mr-0 mr-sm-5 w-100"
                  />
                  <v-dialog
                    v-model="sideBarFormDialog"
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
                        New Side Bar
                      </v-btn>
                    </template>
                    <v-card>
                      <v-toolbar dark color="secondary">
                        <v-card-title>
                          <i class="fad fa-layer-group mr-2"></i>
                          <span class="headline">
                            {{ formTitle }}
                          </span>
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
                                label="Side Bar Name"
                              >
                              </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-autocomplete
                                v-model="editedItem.course"
                                :items="courses"
                                :menu-props="{ closeOnClick: true }"
                                label="Select a Course"
                              >
                              </v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-autocomplete
                                v-model="editedItem.assigned_dealer"
                                multiple
                                :items="dealers"
                                :menu-props="{ closeOnClick: true }"
                                label="Assign to Company"
                              >
                              </v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-autocomplete
                                v-model="editedItem.specific_dealer"
                                multiple
                                :items="specific_dealers"
                                :menu-props="{ closeOnClick: true }"
                                label="Assign to Specific Dealer"
                              >
                              </v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-autocomplete
                                v-model="editedItem.user"
                                multiple
                                :items="users"
                                :menu-props="{ closeOnClick: true }"
                                label="Only Assign to Specific User/s"
                              >
                              </v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-autocomplete
                                v-model="editedItem.user"
                                multiple
                                :items="users"
                                :menu-props="{ closeOnClick: true }"
                                label="Only Assign to Specific Group/s"
                              >
                              </v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-autocomplete
                                v-model="editedItem.specific_dealer"
                                multiple
                                :items="categories"
                                :menu-props="{ closeOnClick: true }"
                                label="Select the Course Categories (arrange in order)"
                              >
                              </v-autocomplete>
                            </v-col>
                          </v-row>
                        </v-container>
                      </v-card-text>

                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue-grey darken-1" text @click="close">
                          Cancel
                        </v-btn>
                        <v-btn color="blue darken-1" text @click="save">
                          Save
                        </v-btn>
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
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
export default {
  name: 'SidebarBuilder',

  data() {
    return {
      sideBarFormDialog: false,
      dealers: [
        'Kelly Nissan',
        'Kelly Mitsubishi',
        'Friendship Automotive Group',
        'Hudson Automotive Group',
      ],
      specific_dealers: ['All', 'Dealer A', 'Dealer B', 'Dealer C', 'Dealer D'],
      users: ['All', 'User A', 'User B', 'User C', 'User D'],
      groups: ['All', 'Group A', 'Group B', 'Group C', 'Group D'],
      courses: [
        'Hudson Academy',
        'True Car Learning System',
        'Learning Management for Salespeople',
      ],
      categories: ['Internet', 'Phone', 'Unsold Showroom'],
      headers: [
        {
          text: 'Name of Side Bar',
          align: 'left',
          sortable: false,
          value: 'name',
        },
        {
          text: 'Assigned Company',
          align: 'center',
          sortable: true,
          value: 'assigned_dealer',
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
      sideBars: [],
      editedIndex: -1,
      editedItem: {
        name: '',
      },
      defaultItem: {
        name: '',
      },
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Side Bar' : 'Edit Side Bar';
    },
  },

  watch: {
    sideBarFormDialog(val) {
      // eslint-disable-next-line
      val || this.close();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    remove(item) {
      this.chips.splice(this.chips.indexOf(item), 1);
      this.chips = [...this.chips];
    },
    initialize() {
      this.sideBars = [
        {
          name: 'Friendship Automotive Group General Sidebar',
          assigned_dealer: 'Friendship Automotive Group',
          created_at: '12/12/2019',
        },
        {
          name: 'Friendship Automotive Group Service Group',
          assigned_dealer: 'Friendship Automotive Group',
          created_at: '12/12/2019',
        },
        {
          name: 'Friendship Manager SideBar',
          assigned_dealer: 'Friendship Automotive Group',
          created_at: '12/12/2019',
        },
      ];
    },

    editItem(item) {
      this.editedIndex = this.sideBars.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.sideBarFormDialog = true;
    },

    deleteItem(item) {
      const index = this.sideBars.indexOf(item);
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
          this.sideBars.splice(index, 1);
        }
      });
    },

    close() {
      this.sideBarFormDialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.sideBars[this.editedIndex], this.editedItem);
      } else {
        this.sideBars.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>
