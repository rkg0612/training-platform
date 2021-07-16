<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-user-friends"></i>
        </span>
        <h3 class="kt-portlet__head-title">Group User Management</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="groups"
        sort-by="name"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat color="white">
            <v-spacer></v-spacer>
            <v-dialog
              v-model="groupFormDialog"
              hide-overlay
              max-width="800px"
              transition="dialog-bottom-transition"
            >
              <template v-slot:activator="{ on }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-show="!isSalesperson"
                  v-on="on"
                  @click="newGroup"
                  text
                >
                  <i class="fal fa-plus mr-2"></i>
                  New Group
                </v-btn>
              </template>
              <v-card>
                <v-toolbar dark color="secondary">
                  <v-card-title>
                    <i class="fal fa-user-friends mr-2"></i>
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
                          label="Group Name"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="12" md="12" v-show="isAdmin">
                        <v-autocomplete
                          v-model="editedItem.dealer_id"
                          :items="dealers"
                          item-text="name"
                          item-value="id"
                          :menu-props="{ closeOnClick: true }"
                          label="Select Dealer"
                          @change="changedDealer"
                        ></v-autocomplete>
                      </v-col>
                      <v-col cols="12" sm="12" md="12">
                        <v-autocomplete
                          v-model="editedItem.users"
                          multiple
                          :items="users"
                          item-text="name"
                          item-value="id"
                          :menu-props="{ closeOnClick: true }"
                          label="Select Members"
                          :search-input.sync="searchUsers"
                          @change="changedUser"
                        ></v-autocomplete>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue-grey darken-1" text @click="close"
                    >Cancel</v-btn
                  >
                  <v-btn color="blue darken-1" text @click="submit">Save</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.action="{ item }">
          <v-btn text @click="editItem(item)" color="grey darken-3" small>
            <v-icon small>fal fa-edit</v-icon>
          </v-btn>
          <v-btn text @click="deleteItem(item)" color="red darken-3" small>
            <v-icon small>fal fa-trash</v-icon>
          </v-btn>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import {
  each as _each,
  find as _find,
  head as _head,
  union as _union,
  map as _map,
  some as _some,
} from 'lodash-es';
import dayjs from 'dayjs';

export default {
  name: 'groupUser',

  data() {
    return {
      groupFormDialog: false,
      searchUsers: '',
      users: [],
      groups: [],
      dealers: [],
      specificDealers: [],
      pagination: {
        total: 1,
        per_page: 5,
        current_page: 1,
        search: '',
      },
      headers: [
        {
          text: 'Group Name',
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
        id: null,
        name: '',
        users: [],
        dealer_id: null,
        specific_dealer_id: null,
      },
      defaultItem: {
        id: null,
        name: '',
        users: [],
        dealer_id: null,
        specific_dealer_id: null,
      },
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Group' : 'Edit Group';
    },
    currentUser() {
      return this.$auth.user();
    },
    roles() {
      return this.currentUser.roles;
    },
    isAdmin() {
      return _some(this.roles, { name: 'super-administrator' });
    },
    isAccountManager() {
      return _some(this.roles, { name: 'account-manager' });
    },
    isSpecificDealerManager() {
      return _some(this.roles, { name: 'specific-dealer-manager' });
    },
    isSalesperson() {
      return _some(this.roles, { name: 'salesperson' });
    },
    isAutomotive() {
      return this.currentUser.dealer !== null
        ? this.currentUser.dealer.is_automotive
        : false;
    },
  },

  watch: {
    groupFormDialog(val) {
      // eslint-disable-next-line
      val || this.close();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      this.getGroups();
      this.getDealers();

      if (this.isAccountManager) {
        if (this.isAutomotive) {
          this.editedItem.dealer_id = this.currentUser.dealer_id;
          this.editedItem.specific_dealer_id = this.currentUser.specific_dealer_id;
        } else {
          this.editedItem.dealer_id = this.currentUser.dealer_id;
        }
      }

      if (this.isSpecificDealerManager) {
        this.editedItem.dealer_id = this.currentUser.dealer_id;
        this.editedItem.specific_dealer_id = this.currentUser.specific_dealer_id;
      }
    },
    newGroup() {
      if (this.isAdmin || this.isAccountManager) {
        this.changedDealer();
      }

      if (this.isSpecificDealerManager) {
        this.changedSpecificDealer();
      }
    },
    submit() {
      if (this.editedIndex == -1) {
        this.save();
      } else {
        this.update();
      }
    },
    save() {
      this.$http
        .post('groups', this.editedItem)
        .then((response) => {
          this.$notify('success', 'New group successfully created.');
          const group = response.data;
          const usersId = _map(group.users, 'id');
          Object.assign(group, { users: usersId });
          this.groups.push(group);
          this.close();
        })
        .catch((err) => {
          this.$notify('error', err);
        });
    },
    update() {
      this.$http
        .put(`groups/${this.editedItem.id}`, this.editedItem)
        .then((response) => {
          this.$notify('success', 'The group successfully updated.');
          const group = response.data;
          const usersId = _map(group.users, 'id');
          Object.assign(group, { users: usersId });
          this.groups.splice(this.editedIndex, 1, group);
          this.close();
        })
        .catch((err) => {
          this.$notify('error', err);
        });
    },
    getDealers() {
      this.$http
        .get('/dealers')
        .then((response) => {
          _each(response.data, (dealer) => {
            this.dealers.push(dealer);
          });
        })
        .catch((e) => {
          console.log(e);
        });
    },
    getGroups() {
      this.$http
        .get('/groups')
        .then((response) => {
          _each(response.data, (group) => {
            const usersId = _map(group.users, 'id');
            Object.assign(group, { users: usersId });
            this.groups.push(group);
          });
        })
        .catch((err) => {});
    },
    editItem(item) {
      this.editedIndex = this.groups.indexOf(item);
      this.editedItem = Object.assign({}, item);

      if (this.isAdmin || this.isAccountManager) {
        this.changedDealer();
      }

      if (this.isSpecificDealerManager) {
        this.changedSpecificDealer();
      }

      Object.assign((this.editedItem.users = item.users));

      this.groupFormDialog = true;
    },

    deleteItem(item) {
      const index = this.groups.indexOf(item);
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
          this.$http
            .delete(`/groups/${item.id}`)
            .then((response) => {
              this.$notify(
                'success',
                'The group has been deleted successfully.'
              );
              this.groups.splice(index, 1);
            })
            .catch((err) => {
              this.$notify('error', err);
            });
        }
      });
    },
    changedUser() {
      this.searchUsers = '';
    },
    changedDealer() {
      if (this.editedItem.dealer_id) {
        this.users = [];
        this.specificDealers = [];
        // this.editedItem.specific_dealer_id = null;
        this.editedItem.users = [];

        const currentDealer = _find(this.dealers, {
          id: this.editedItem.dealer_id,
        });

        if (currentDealer.is_automotive) {
          this.specificDealers = currentDealer.specific_dealers;
        }

        this.$http
          .get(`/dealer/${this.editedItem.dealer_id}/users`)
          .then((response) => {
            this.users = response.data.users;
          });
      }
    },
    changedSpecificDealer() {
      if (this.editedItem.specific_dealer_id) {
        this.users = [];
        this.editedItem.users = [];

        const specificCurrentDealer = _find(this.specificDealers, {
          id: this.editedItem.specific_dealer_id,
        });

        this.$http
          .get(`/specific-dealer/${this.editedItem.specific_dealer_id}/users`)
          .then((response) => {
            this.users = response.data.users;
          });
      }
    },
    close() {
      this.groupFormDialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
  },
};
</script>
