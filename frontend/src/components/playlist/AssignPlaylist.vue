<template>
  <v-dialog v-model="dialog" width="400">
    <v-card>
      <v-card-title class="headline"> Assign playlist </v-card-title>

      <v-card-text>
        <vue-select
          :options="users"
          v-model="selectedUsers"
          @search="fetchUsers"
          :reduce="(user) => user.id"
          label="name"
          multiple
        >
          <template #header>
            <div>Users</div>
          </template>
        </vue-select>
        <vue-select
          v-model="groupSelect"
          :options="groups"
          label="name"
          :reduce="(group) => group.id"
          multiple
        >
          <template #header>
            <div>Specific Groups</div>
          </template>
        </vue-select>
        <!--        <v-autocomplete-->
        <!--          v-model="groupSelect"-->
        <!--          :items="groups"-->
        <!--          :menu-props="{ closeOnClick: true }"-->
        <!--          label="Assign to Specific Group"-->
        <!--          multiple-->
        <!--        ></v-autocomplete>-->
        <v-menu
          ref="dueDateMenu"
          v-model="dueDateMenu"
          :close-on-content-click="false"
          :return-value.sync="dueDate"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="dueDate"
              label="Due Date"
              prepend-icon="fal fa-calendar"
              readonly
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="dueDate" no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="dueDateMenu = false">
              Cancel
            </v-btn>
            <v-btn
              text
              color="primary"
              @click="$refs.dueDateMenu.save(dueDate)"
            >
              OK
            </v-btn>
          </v-date-picker>
        </v-menu>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text @click="toggleAssignDialog"> Close </v-btn>
        <v-btn
          color="primary"
          text
          @click="assign"
          :loading="assignLoader"
          :disabled="assignLoader"
        >
          Assign
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import {
  union as _union,
  each as _each,
  isEmpty as _isEmpty,
  map as _map,
} from 'lodash-es';
import dayjs from 'dayjs';

export default {
  name: 'AssignPlaylist',
  props: {
    dialog: Boolean,
    toggleAssignDialog: Function,
    playlist: Object,
  },
  data() {
    return {
      groupSelect: [],
      groups: [],
      assignToAll: false,
      loading: true,
      dueDate: new Date().toISOString().substr(0, 10),
      dueDateMenu: false,
      selectedUsers: [],
      assignLoader: false,
      usersSearchResult: [],
      searchUsers: null,
    };
  },
  computed: {
    dateFormatted() {
      if (this.dueDate === null) {
        return '';
      }

      return dayjs(this.dueDate).format('MM/DD/YYYY');
    },
    showSelectedUsers() {
      return this.selectedUsers.length > 0;
    },
    users() {
      return _union(this.selectedUsers, this.usersSearchResult);
    },
  },
  created() {
    this.getGroups();
  },
  methods: {
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
      const groups = _isEmpty(this.groupSelect) ? null : this.groupSelect;
      if (users.length < 1 && groups.length < 1) return;
      this.$http
        .post('/playlist/assign', {
          playlist_id: this.playlist.id,
          users,
          groups,
          due_date: this.dueDate,
        })
        .then(() => {
          this.toggleAssignDialog();
          this.$swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Playlist successfully assigned',
          });
          // reset to default
          this.selectedUsers = [];
          this.groupSelect = [];
          this.assignToAll = false;
          this.dueDate = new Date().toISOString().substr(0, 10);
          this.value = false;
        })
        .catch(() => {
          this.toggleAssignDialog();
          this.$swal.fire({
            icon: 'error',
            title: 'Problem',
            text: 'Error occured',
          });
          this.value = false;
        });
    },
    selectedUsersChanged() {
      this.searchUsers = '';
    },
    saveDueDate() {},
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

<style scoped></style>
