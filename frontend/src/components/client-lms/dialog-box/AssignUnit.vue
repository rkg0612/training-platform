<template>
  <v-dialog v-model="showAssignUnit" persistent max-width="600px">
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
                v-model="assign.assignToAll"
                label="Assign to All"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" sm="6" md="8">
              <v-autocomplete
                v-model="assign.user_ids"
                :items="salesPersons"
                item-value="id"
                item-text="name"
                :disabled="assign.assignToAll"
                :menu-props="{ closeOnClick: true }"
                label="Assign to Specific Salesperson"
                multiple
                :search-input.sync="searchUsers"
                @change="changedUser"
              ></v-autocomplete>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <v-menu
                ref="dueDateMenu"
                v-model="dueDateMenu"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    v-model="assign.due_date"
                    label="Due Date"
                    prepend-icon="fal fa-calendar"
                    readonly
                    @blur="date = parseDate(assign.due_date)"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="date"
                  no-title
                  @input="dueDateMenu = false"
                ></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          :disabled="loading"
          :loading="loading"
          text
          @click="assignUnit"
        >
          Save
        </v-btn>
        <v-btn
          color="blue-grey"
          :disabled="loading"
          :loading="loading"
          text
          @click="close"
        >
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: 'AssignUnit',

  props: {
    value: Boolean,
    unit_id: Number,
  },

  data() {
    return {
      date: new Date().toISOString().substr(0, 10),
      assign: {
        unit_id: this.unit_id,
        user_ids: [],
        assignToAll: false,
        due_date: this.formatDate(new Date().toISOString().substr(0, 10)),
      },
      dueDateMenu: false,
      moduleItems: ['BDC Playlist', 'Showroom Playlist', 'Service Playlist'],
      loading: false,
      searchUsers: '',
    };
  },

  computed: {
    showAssignUnit: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },

    salesPersons() {
      return this.$store.state.lmsClient.listUsers;
    },
  },

  watch: {
    date(val) {
      this.assign.due_date = this.formatDate(this.date);
    },
  },

  methods: {
    assignUnit() {
      this.loading = true;
      this.$store
        .dispatch('lmsClient/assignUnit', this.assign)
        .then(({ data }) => {
          console.log(data);
          this.$notify('success', 'Unit Assigned to User(s) successfully');
        })
        .catch(() => {
          this.$notify(
            'error',
            'Encountered an error while loading your course library. Please try again'
          );
        })
        .finally(() => {
          this.loading = false;
          this.close();
        });
    },

    changedUser() {
      this.searchUsers = '';
    },

    close() {
      const def = {
        unit_id: this.unit_id,
        user_ids: [],
        assignToAll: false,
        due_date: new Date().toISOString().substr(0, 10),
      };
      this.assign = def;
      this.showAssignUnit = false;
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
};
</script>
