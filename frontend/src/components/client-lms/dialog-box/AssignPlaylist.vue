<template>
  <v-dialog v-model="showAssignPlaylist" persistent max-width="600px">
    <v-card>
      <v-card-title>
        <span class="headline">
          <v-icon class="ml-1">fal fa-users-class</v-icon>Assign to Other Users
        </span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12" sm="6" md="4">
              <v-checkbox
                v-model="assignToAll"
                label="Assign to All"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" sm="6" md="8">
              <multiselect
                v-model="selectedUsers"
                name="users"
                :options="users"
                @search-change="fetchUsers"
                :hide-selected="true"
                label="name"
                placeholder="Find users..."
                multiple
                clearable
                @change="selectedUsersChanged"
                :disabled="assignToAll"
                v-validate="userValidate"
              >
                <template>
                  <span slot="noResult"
                    >No users found. Try changing the name.</span
                  >
                  <span slot="noOptions">Please input a name.</span>
                </template>
              </multiselect>
              <span
                class="kt-font-danger validate-error"
                v-show="errors.has('users')"
                autocomplete="off"
              >
                {{ errors.first('users') }}
              </span>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <v-autocomplete
                v-model="selectedGroups"
                :items="groups"
                item-text="name"
                item-value="id"
                :menu-props="{ closeOnClick: true }"
                label="Assign to Specific Group"
                multiple
              ></v-autocomplete>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <v-menu
                ref="dueDateMenu"
                v-model="dueDateMenu"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="dueDate"
                    label="Due Date"
                    persistent-hint
                    prepend-icon="fal fa-calendar"
                    v-on="on"
                    readonly
                    v-bind="attrs"
                    @blur="temporaryDate = parseDate(dueDate)"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="temporaryDate"
                  no-title
                  @input="dueDateMenu = false"
                ></v-date-picker>
              </v-menu>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <multiselect
                v-model="playlistSelect"
                :options="playlistItems"
                label="name"
                placeholder="Select Playlist"
              ></multiselect>
              <!--              <v-autocomplete-->
              <!--                v-model="playlistSelect"-->
              <!--                :items="playlistItems"-->
              <!--                item-value="id"-->
              <!--                item-text="name"-->
              <!--                :menu-props="{ closeOnClick: true }"-->
              <!--                label="Select Playlist"-->
              <!--              ></v-autocomplete>-->
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" :loading="isLoading" text @click="assign"
          >Save</v-btn
        >
        <v-btn color="blue-grey" text @click="showAssignPlaylist = false"
          >Close</v-btn
        >
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { union as lodashUnion, each as _each } from 'lodash-es';

export default {
  name: 'AssignPlaylist',

  props: {
    value: Boolean,
    playlists: Array,
  },

  data() {
    return {
      date: '',
      selectedGroups: [],
      groups: [],
      selectedUsers: [],
      playlistSelect: '',
      assignToAll: false,
      dueDate: this.formatDate(new Date().toISOString().substr(0, 10)),
      dueDateMenu: false,
      usersSearchResult: [],
      searchUsers: null,
      temporaryDate: new Date().toISOString().substr(0, 10),
      isLoading: false,
    };
  },

  computed: {
    showAssignPlaylist: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
    playlistItems() {
      return this.playlists;
    },
    showSelectedUsers() {
      return this.selectedUsers.length > 0;
    },
    users() {
      return lodashUnion(this.selectedUsers, this.usersSearchResult);
    },
    userValidate() {
      return this.selectedGroups.length !== 0 || this.assignToAll
        ? ''
        : 'required';
    },
  },

  created() {
    this.getGroups();
  },

  methods: {
    removeUser(item) {
      console.log(item);
    },
    getGroups() {
      this.$http
        .get('/groups')
        .then((response) => {
          _each(response.data, (group) => {
            this.groups.push(group);
          });
        })
        .catch((err) => {});
    },
    fetchUsers(filter) {
      const params = {};
      if (filter) {
        params.filter = filter;
      }

      this.$http
        .get('/playlist/assign', { params })
        .then(({ data }) => {
          this.usersSearchResult = data;
        })
        .catch(() => {});
    },
    assign() {
      const users = this.assignToAll ? 'all' : this.selectedUsers;
      this.$validator.validate('*').then((result) => {
        if (result) {
          this.isLoading = true;
          this.$http
            .post('/playlist/assign', {
              playlist_id: this.playlistSelect.id,
              users,
              due_date: this.dueDate,
              groups: this.selectedGroups,
            })
            .then(() => {
              this.showAssignPlaylist = false;
              this.$notify('success', 'Playlist successfully assigned!');
              // reset to default
              this.selectedUsers = [];
              this.selectedGroups = [];
              this.playlistSelect = '';
              this.assignToAll = false;
              this.dueDate = this.formatDate(
                new Date().toISOString().substr(0, 10)
              );
              this.value = false;
              this.isLoading = false;
            })
            .catch(() => {
              this.showAssignPlaylist = false;
              this.$notify('warning', 'Something went wrong!');
              this.value = false;
            });
        }
      });
    },
    selectedUsersChanged() {
      this.searchUsers = '';
    },

    formatDate(date) {
      if (!date) return null;
      const [year, month, day] = date.split('-');
      return `${month}/${day}/${year}`;
    },

    parseDate(date) {
      if (!date) return null;
      const [month, day, year] = date.split('/');
      return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    },
  },

  watch: {
    searchUsers(val) {
      if (val) {
        this.fetchUsers(val);
      }
    },

    temporaryDate(val) {
      this.dueDate = this.formatDate(this.temporaryDate);
    },
  },
};
</script>
