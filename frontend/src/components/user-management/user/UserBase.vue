<template>
  <div class="kt-portlet kt-portlet--height-fluid" v-show="!isSalesperson">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-users"></i>
        </span>
        <h3 class="kt-portlet__head-title">Users</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group">
          <v-dialog
            v-model="newUserForm"
            max-width="1200px"
            transition="dialog-bottom-transition"
          >
            <template v-slot:activator="{ on }">
              <v-btn color="blue darken-3" dark class="mb-2" v-on="on" text>
                <i class="fal fa-plus mr-2"></i>
                New User
              </v-btn>
            </template>
            <v-card>
              <v-toolbar dark color="secondary">
                <v-card-title>
                  <i class="fal fa-users"></i>
                  &nbsp;
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
                <v-container class="kt-form">
                  <!-- User's Info -->
                  <h4 class="mt-5">
                    <span>
                      <i class="fal fa-info-circle"></i>
                    </span>
                    User's Info
                  </h4>
                  <v-row>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.name"
                        label="Name"
                        v-validate="'required'"
                        name="name"
                      ></v-text-field>
                      <span
                        class="kt-font-danger validate-error"
                        v-show="errors.has('name')"
                        >{{ errors.first('name') }}</span
                      >
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.job_title"
                        label="Job Title"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.email"
                        label="Email Address"
                        v-validate="'required|email'"
                        name="email"
                      ></v-text-field>
                      <span
                        class="kt-font-danger validate-error"
                        v-show="errors.has('email')"
                        >{{ errors.first('email') }}</span
                      >
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.phone_number"
                        label="Phone Number"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-select
                        v-model="editedItem.dealer_id"
                        :items="dealerList"
                        item-text="name"
                        item-value="id"
                        @change="removeSelectedSpecificDealer"
                        label="Select a Company"
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      sm="6"
                      md="6"
                      v-show="isAutomotive || isAdmin"
                    >
                      <multiselect
                        v-model="editedItem.specific_dealer_id"
                        tag-placeholder="Add this as the dealer you are part of."
                        placeholder="Name of Your Dealership"
                        label="name"
                        :options="specificDealerList"
                        :multiple="false"
                        :taggable="true"
                        :limit="2"
                        @search-change="searchSpecificDealer"
                        @tag="addNewSpecificDealer"
                        track-by="id"
                        id="specificDealerSelect"
                      >
                        <template slot="noOptions">Select Dealer</template>
                      </multiselect>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.password"
                        :append-icon="
                          showPassword ? 'fal fa-eye' : 'fal fa-eye-slash'
                        "
                        :type="showPassword ? 'text' : 'password'"
                        @click:append="showPassword = !showPassword"
                        label="Password"
                        v-validate="'required'"
                        name="password"
                        ref="password"
                      ></v-text-field>
                      <span
                        class="kt-font-danger validate-error"
                        v-show="errors.has('password')"
                        >{{ errors.first('password') }}</span
                      >
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-text-field
                        v-model="editedItem.password_confirmation"
                        :append-icon="
                          showConfirmPassword
                            ? 'fal fa-eye'
                            : 'fal fa-eye-slash'
                        "
                        :type="showConfirmPassword ? 'text' : 'password'"
                        @click:append="
                          showConfirmPassword = !showConfirmPassword
                        "
                        label="Confirm Password"
                        name="password_confirmation"
                        v-validate="'required|confirmed:password'"
                      ></v-text-field>
                      <span
                        class="kt-font-danger validate-error"
                        v-show="errors.has('password_confirmation')"
                        >{{ errors.first('password_confirmation') }}</span
                      >
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-file-input
                        label="Upload a Profile Picture (optional)"
                      ></v-file-input>
                    </v-col>
                    <v-col cols="12" sm="6" md="6">
                      <v-select
                        v-model="editedItem.roles"
                        :items="roleList"
                        item-text="friendly_name"
                        item-value="id"
                        label="Select a Role"
                        multiple
                        v-validate="'required'"
                        name="role"
                      ></v-select>
                      <span
                        class="kt-font-danger validate-error"
                        v-show="errors.has('role')"
                        >{{ errors.first('role') }}</span
                      >
                    </v-col>
                  </v-row>
                  <v-row v-if="editedIndex !== -1">
                    <v-col><lms-summary-report :userId="userId" /></v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-grey darken-1" text @click="close"
                  >Cancel</v-btn
                >
                <v-btn color="blue darken-1" text @click="save">Save</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <v-data-table
        :headers="headers"
        :items="users"
        :items-per-page="pagination.per_page"
        :server-items-length="pagination.total"
        :search="userSearch"
        @update:page="changePage"
        no-results-text="No results..."
        :loading="isFetching"
        :options.sync="options"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat color="white">
            <v-row>
              <v-spacer></v-spacer>
              <v-col cols="12" xl="4">
                <v-text-field
                  class="mt-0 pt-1 mr-0 mr-sm-5 w-100"
                  clearable
                  v-model.trim="pagination.search"
                  @change="getUsers"
                  append-icon="fal fa-search"
                  label="Search"
                  single-line
                  hide-details
                />
              </v-col>
            </v-row>
          </v-toolbar>
        </template>
        <template class="user-btn-wrapper" v-slot:item.action="{ item }">
          <v-btn text @click="editItem(item)" color="grey darken-3" small>
            <v-icon small>fal fa-edit</v-icon>
          </v-btn>
          <v-dialog
            v-model="userApprovalDialog"
            width="500"
            v-if="isCurrentUserSuperAdmin"
          >
            <template v-slot:activator="{ on }">
              <v-btn
                v-on="on"
                @click="manageApprovalForUser(item)"
                text
                small
                color="light-blue darken-3"
              >
                <v-icon small>fal fa-check</v-icon>
              </v-btn>
            </template>

            <v-card>
              <v-toolbar dark color="secondary">
                <v-card-title>
                  <i class="fal fa-list"></i>
                  &nbsp;
                  <span class="headline">Manage Approval</span>
                </v-card-title>
                <v-spacer></v-spacer>
                <button
                  type="button"
                  class="btn btn-light btn-elevate-hover btn-circle btn-icon btn-sm"
                  @click="userApprovalDialog = false"
                >
                  <i class="fal fa-times"></i>
                </button>
              </v-toolbar>

              <v-card-text>
                <v-container class="kt-form">
                  <!-- User's Info -->
                  <h4 class="mt-5">
                    <span>
                      <i class="fal fa-info-circle"></i>
                    </span>
                    Manage Approval for: {{ currentUserInApprovalWindow.name }}
                  </h4>
                  <v-row>
                    <v-col>
                      <v-btn
                        class="ma-2"
                        tile
                        color="primary"
                        @click="updateUserStatus('ACTIVE')"
                        >Approve</v-btn
                      >
                      <v-btn
                        class="ma-2"
                        tile
                        color="danger"
                        @click="updateUserStatus('REJECTED')"
                        >Reject</v-btn
                      >
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-divider></v-divider>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="warning" text @click="userApprovalDialog = false"
                  >Cancel</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-btn text @click="deleteItem(item)" color="red darken-3" small>
            <v-icon small>fal fa-trash</v-icon>
          </v-btn>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
        <template v-slot:item.roles="{ item }">
          {{ getRoleFriendlyName(item.roles) }}
        </template>
        <template v-slot:item.dealer="{ item }">
          {{ getDealerName(item.dealer_id) }}
        </template>
        <template v-slot:item.specificDealer="{ item }">
          {{ getSpecificDealerName(item.specific_dealer_id) }}
        </template>
      </v-data-table>
    </div>
  </div>
</template>
<script>
import {
  each as lodashEach,
  head as lodashHead,
  filter as lodashFilter,
  map as lodashMap,
  pick as lodashPick,
  startCase as lodashStartCase,
  toLower as lodashToLower,
  some as _some,
  remove as _remove,
} from 'lodash-es';
import dayjs from 'dayjs';
import LmsSummaryReport from '../../client-reports/LmsSummaryReport';

export default {
  name: 'UserBase',
  components: {
    LmsSummaryReport,
  },
  data: () => ({
    options: {},
    isFetching: false,
    userSearch: '',
    newUserForm: false,
    showPassword: false,
    showConfirmPassword: false,
    dealerList: [],
    roleList: [],
    specificDealerListSource: [],
    specificDealerList: [],
    allSpecificDealerList: [],
    userApprovalDialog: false,
    currentUserInApprovalWindow: '',
    sortBy: 'name',
    sortDesc: true,
    userId: '',
    headers: [
      {
        text: 'Name',
        align: 'left',
        value: 'name',
        sortable: true,
      },
      {
        text: 'Date Created',
        value: 'created_at',
        sortable: true,
      },
      {
        text: 'Company',
        value: 'dealer',
      },
      {
        text: 'Dealer',
        value: 'specificDealer',
      },
      {
        text: 'Role',
        value: 'roles',
      },
      {
        text: 'Status',
        value: 'is_active',
      },
      {
        text: 'Actions',
        value: 'action',
        align: 'center',
        width: 200,
        sortable: false,
      },
    ],
    users: [],
    editedIndex: -1,
    editedItem: {
      id: 0,
      name: '',
      job_title: '',
      email: '',
      phone_number: '',
      dealer_id: '',
      specific_dealer_id: '',
      password: '',
      password_confirmation: '',
      profilePic: '',
      roles: '',
      status: '',
      lmsAccess: false,
      secretShopAccess: false,
    },
    defaultItem: {
      name: '',
      job_title: '',
      email: '',
      phone_number: '',
      dealer: '',
      specific_dealer: '',
      password: '',
      password_confirmation: '',
      profilePic: '',
      roles: '',
      status: '',
      lmsAccess: false,
      secretShopAccess: false,
    },
    pagination: {
      total: 1,
      per_page: 5,
      current_page: 1,
      search: '',
      sortBy: '',
      sortDesc: false,
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New User' : 'Edit User';
    },
    currentUser() {
      return this.$auth.user();
    },
    roles() {
      return this.currentUser.roles;
    },
    isSalesperson() {
      return _some(this.roles, { name: 'salesperson' });
    },
    isAutomotive() {
      return this.currentUser.dealer !== null
        ? this.currentUser.dealer.is_automotive
        : false;
    },
    isSpecificDealerManager() {
      return _some(this.roles, { name: 'specific-dealer-manager' });
    },
    isAdmin() {
      return _some(this.roles, { name: 'super-administrator' });
    },
    isCurrentUserSuperAdmin() {
      return _some(this.$auth.user().roles, { name: 'super-administrator' });
    },
  },

  watch: {
    newUserForm(val) {
      val || this.close();
    },
    options: {
      handler() {
        this.getUsers();
      },
      deep: true,
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    customSort(items, index, isDesc) {
      console.log(items);
      console.log(index);
      console.log(isDesc);
    },

    searchSpecificDealer(query) {
      if (query == null || query.length <= 2) {
        this.specificDealerList = [];
        return;
      }

      const dealerId = this.editedItem.dealer_id;
      this.specificDealerList = lodashFilter(
        this.specificDealerListSource,
        (specificDealer) =>
          specificDealer.dealerId === dealerId &&
          specificDealer.name.indexOf(query) !== 1
      );
    },
    addNewSpecificDealer(newDealer) {
      const dealer = {
        id: newDealer,
        name: newDealer,
      };

      this.specificDealerListSource.push(dealer);
      this.specificDealerList.push(dealer);
      this.editedItem.specific_dealer_id = dealer;
    },
    initialize() {
      this.getSpecificDealers();
      this.getRoles();
      this.getUsers();
      this.getDealers();
    },

    getSpecificDealers() {
      this.$http.get('/dealers/specific').then((response) => {
        lodashEach(response.data, (dealer) => {
          this.allSpecificDealerList.push({
            name: dealer.name,
            id: dealer.id,
          });
        });
      });
    },
    getDealers() {
      this.$http.get('/dealers').then((response) => {
        lodashEach(response.data, (dealer) => {
          this.dealerList.push({
            name: dealer.name,
            id: dealer.id,
          });
          lodashEach(dealer.specific_dealers, (specificDealer) => {
            this.specificDealerListSource.push({
              dealerId: dealer.id,
              name: specificDealer.name,
              id: specificDealer.id,
            });
          });
        });
      });
    },
    getUsers() {
      this.pagination.sortBy =
        this.options.sortBy !== undefined ? this.options.sortBy[0] : '';
      this.pagination.sortDesc =
        this.options.sortDesc !== undefined ? this.options.sortDesc[0] : '';

      this.$store
        .dispatch('users/getUsers', {
          payload: this.pagination,
        })
        .then(({ data }) => {
          this.users = [];
          this.pagination.total = data.total;
          this.pagination.current_page = data.current_page;
          this.pagination.per_page = Number(data.per_page);
          lodashEach(data.data, (user) => {
            this.users.push({
              id: user.id,
              name: user.name,
              email: user.email,
              created_at: dayjs(user.created_at).format('MM/DD/YYYY hh:mm A'),
              dealer_id: user.dealer_id,
              specific_dealer_id: user.specific_dealer_id,
              is_active: lodashStartCase(
                lodashToLower(user.status.replace('_', ' '))
              ),
              roles: lodashMap(user.roles, 'id'),
              lmsAccess:
                lodashMap(user.roles, 'name').join(' ').indexOf('lms') !== -1,
              secretShopAccess:
                lodashMap(user.roles, 'name').join(' ').indexOf('shop') !== -1,
              phone_number: user.phone_number,
              password: '',
              // no record or column yet in database
              profilePic: '',
            });
          });
        });
    },
    getRoles() {
      this.$http.get('/roles').then((response) => {
        lodashEach(response.data, (role) => {
          if (
            _some(this.roles, { name: 'account-manager' }) ||
            _some(this.roles, { name: 'specific-dealer-manager' })
          ) {
            if (this.isAutomotive && role.name === 'specific-dealer-manager') {
              this.roleList.push(lodashPick(role, ['id', 'friendly_name']));
            }

            if (
              role.name === 'account-manager' ||
              role.name === 'salesperson'
            ) {
              this.roleList.push(lodashPick(role, ['id', 'friendly_name']));
            }
          } else if (_some(this.roles, { name: 'super-administrator' })) {
            this.roleList.push(lodashPick(role, ['id', 'friendly_name']));
          }
        });
        if (this.isSpecificDealerManager) {
          this.roleList = _remove(this.roleList, (role) => {
            return role.friendly_name !== 'Account Manager';
          });
        }
      });
    },
    getRoleFriendlyName(roles) {
      const { roleList } = this;
      const friendlyNames = [];
      lodashEach(roles, (roleId) => {
        friendlyNames.push(lodashHead(lodashFilter(roleList, ['id', roleId])));
      });

      return lodashMap(friendlyNames, 'friendly_name').join(', ');
    },
    getDealerName(dealerId) {
      const { dealerList } = this;
      const dealer = lodashHead(lodashFilter(dealerList, ['id', dealerId]));
      return dealer === undefined ? '' : dealer.name;
    },
    getSpecificDealerName(specificDealerId) {
      const { allSpecificDealerList } = this;
      const dealer = lodashHead(
        lodashFilter(allSpecificDealerList, ['id', specificDealerId])
      );
      return dealer === undefined ? '' : dealer.name;
    },
    manageApprovalForUser(item) {
      this.editedIndex = this.users.indexOf(item);
      this.currentUserInApprovalWindow = item;
      this.userApprovalDialog = true;
    },
    removeSelectedSpecificDealer() {
      this.editedItem.specific_dealer_id = null;
    },
    updateUserStatus(status) {
      this.$http
        .patch(`users/status/${this.currentUserInApprovalWindow.id}`, {
          status,
        })
        .then((response) => {
          this.users[this.editedIndex].is_active = lodashStartCase(
            lodashToLower(response.data.status.replace('_', ' '))
          );
          this.$notify('success', "User's successfully updated.");
        })
        .catch((err) => {
          this.$notify(
            'danger',
            "Error occured when updating the user's status."
          );
        });
      this.userApprovalDialog = false;
    },
    editItem(item) {
      this.userId = item.id;
      this.editedIndex = this.users.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.dealer_id = item.dealer_id;
      if (item.specific_dealer_id) {
        this.editedItem.specific_dealer_id = lodashFilter(
          this.allSpecificDealerList,
          { id: item.specific_dealer_id }
        );
      }
      this.newUserForm = true;
    },

    deleteItem(item) {
      const index = this.users.indexOf(item);
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
          const deleteUserUrl = `/users/${this.users[index].id}`;
          this.$http
            .delete(deleteUserUrl)
            .then(() => {
              this.$notify('success', 'User successfully deleted.');
              this.users.splice(index, 1);
            })
            .catch(() => {
              this.$notify('danger', 'Error occured when deleting the user.');
            });
        }
      });
    },
    changePage(page) {
      this.pagination.current_page = page;
      this.getUsers();
    },

    close() {
      this.newUserForm = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.users[this.editedIndex], this.editedItem);
        if (!this.editedItem.password) {
          delete this.users[this.editedIndex]['password'];
        }
        const editUserUrl = `/users/${this.users[this.editedIndex].id}`;
        this.$http
          .patch(editUserUrl, this.users[this.editedIndex])
          .then((response) => {
            const { user, specificDealer } = response.data;
            if (specificDealer) {
              this.allSpecificDealerList.push({
                name: specificDealer.name,
                id: specificDealer.id,
              });
            }
            this.users.splice(this.editedIndex, 1, {
              id: user.id,
              name: user.name,
              email: user.email,
              created_at: dayjs(user.created_at).format('MM/DD/YYYY hh:mm A'),
              dealer_id: user.dealer_id,
              specific_dealer_id: user.specific_dealer_id,
              is_active: lodashStartCase(
                lodashToLower(user.status.replace('_', ' '))
              ),
              roles: lodashMap(user.roles, 'id'),
              lmsAccess: lodashMap(user.roles, 'name').indexOf('lms') !== -1,
              secretShopAccess:
                lodashMap(user.roles, 'name').join(' ').indexOf('shop') !== -1,
              phone_number: user.phone_number,
              password: '',
              // no record or column yet in database
              profilePic: '',
            });
            this.$notify('success', 'User successfully updated.');
          })
          .catch((errors) => {
            const messages = errors.response.data.errors;
            lodashEach(messages, (value, key) => {
              this.errors.add({
                field: key,
                msg: lodashHead(value),
              });
            });
          });
        this.close();
      } else {
        const storeNewUserUrl = '/users/';
        this.$http
          .post(storeNewUserUrl, this.editedItem)
          .then((response) => {
            // eslint-disable-next-line camelcase
            const { user, specificDealer } = response.data;
            if (specificDealer) {
              this.allSpecificDealerList.push({
                name: specificDealer.name,
                id: specificDealer.id,
              });
            }
            this.users.push({
              id: user.id,
              name: user.name,
              email: user.email,
              created_at: dayjs(user.created_at).format('MM/DD/YYYY hh:mm A'),
              dealer_id: user.dealer_id,
              specific_dealer_id: user.specific_dealer_id,
              is_active: lodashStartCase(
                lodashToLower(user.status.replace('_', ' '))
              ),
              roles: lodashMap(user.roles, 'id'),
              lmsAccess: lodashMap(user.roles, 'name').indexOf('lms') !== -1,
              secretShopAccess:
                lodashMap(user.roles, 'name').join(' ').indexOf('shop') !== -1,
              phone_number: user.phone_number,
              password: '',
              // no record or column yet in database
              profilePic: '',
            });
            this.$notify('success', 'New user successfully created.');
            this.close();
          })
          .catch((errors) => {
            const messages = errors.response.data.errors;
            lodashEach(messages, (value, key) => {
              this.errors.add({
                field: key,
                msg: lodashHead(value),
              });
            });
          });
      }
    },
  },
};
</script>
<style lang="stylus" scoped>
.multiselect {
  margin-top: 9px;
}

.v-icon.v-icon {
  font-size: 16px;
}

.user-btn {
  margin: 0px 4px;
}

@media screen and (max-width: 1388px) {
  .user-btn {
    display: block !important;
    margin: 4px auto;
  }
}

@media screen and (max-width: 599px) {
  .user-btn {
    display: inline-block !important;
    margin: 0px 4px;
  }
}
</style>
