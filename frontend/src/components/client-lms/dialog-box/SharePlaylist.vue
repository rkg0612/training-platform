<template>
  <v-dialog v-model="showSharePlaylist" persistent max-width="600px">
    <v-card>
      <v-card-title>
        <span class="headline">
          <v-icon class="ml-1">fal fa-users-class</v-icon>
          Assign to Other Users
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
                :options="users"
                @search-change="fetchUsers"
                :hide-selected="true"
                label="name"
                placeholder="Find users..."
                multiple
                clearable
                @change="selectedUsersChanged"
                :disabled="assignToAll"
              >
                <template>
                  <span slot="noResult">
                    No users found. Try changing the name.
                  </span>
                  <span slot="noOptions"> Please input a name. </span>
                </template>
              </multiselect>
            </v-col>
          </v-row>
          <v-row>
            <!-- <v-col cols="12" sm="12" md="12">
              <v-autocomplete
                v-model="groupSelect"
                :items="groups"
                :menu-props="{ closeOnClick: true }"
                label="Assign to Specific Group"
                multiple
              ></v-autocomplete>
            </v-col> -->
            <v-col cols="12" sm="12" md="12">
              <v-autocomplete
                v-model="playlistSelect"
                :items="playlistItems"
                item-value="id"
                item-text="name"
                :menu-props="{ closeOnClick: true }"
                label="Select Playlist"
              ></v-autocomplete>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="assign" :loading="isLoading">
          Save
        </v-btn>
        <v-btn
          color="blue-grey"
          text
          @click="showSharePlaylist = false"
          :loading="isLoading"
        >
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { union as lodashUnion } from 'lodash-es';

export default {
  name: 'SharePlaylist',

  props: {
    value: Boolean,
    playlists: Array,
  },

  data() {
    return {
      selectedUsers: [],
      playlistSelect: '',
      assignToAll: false,
      dueDate: new Date().toISOString().substr(0, 10),
      dueDateMenu: false,
      usersSearchResult: [],
      searchUsers: null,
      isLoading: false,
    };
  },

  computed: {
    showSharePlaylist: {
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
  },

  methods: {
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
      this.isLoading = true;
      const users = this.assignToAll ? 'all' : this.selectedUsers;
      if (users.length < 1) return;
      this.$http
        .post('/playlist/assign', {
          playlist_id: this.playlistSelect,
          users,
        })
        .then(() => {
          this.showSharePlaylist = false;
          this.$notify('success', 'Playlist successfully shared!');
          // reset to default
          this.selectedUsers = [];
          this.playlistSelect = '';
          this.assignToAll = false;
          this.dueDate = new Date().toISOString().substr(0, 10);
          this.value = false;
          this.isLoading = false;
        })
        .catch(() => {
          this.showSharePlaylist = false;
          this.$notify('warning', 'Something went wrong!');
          this.value = false;
        });
    },
    selectedUsersChanged() {
      this.searchUsers = '';
    },
  },

  watch: {
    searchUsers(val) {
      if (val) {
        this.fetchUsers(val);
      }
    },
  },
};
</script>
