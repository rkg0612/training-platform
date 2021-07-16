<template>
  <v-dialog v-model="showShareUnit" persistent max-width="600px">
    <v-card>
      <v-card-title>
        <span class="headline">
          <v-icon class="ml-1">fal fa-share</v-icon>
          Share to Other Users
        </span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <!-- <v-col cols="12" sm="6" md="4">
              <v-checkbox v-model="shareAll" label="Share to All"></v-checkbox>
            </v-col> -->
            <v-col cols="12" sm="12" md="12">
              <v-autocomplete
                v-model="salespersonSelect"
                :items="salesPersons"
                item-text="name"
                item-value="id"
                :menu-props="{ closeOnClick: true }"
                label="Share to Specific Salesperson"
                multiple
                :search-input.sync="searchUsers"
                @change="changedUser"
              ></v-autocomplete>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="shareUnit" :loading="isLoading">
          Save
        </v-btn>
        <v-btn
          color="blue-grey"
          text
          @click="showShareUnit = false"
          :loading="isLoading"
        >
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: 'ShareUnit',

  props: {
    value: Boolean,
    unit_id: Number,
  },

  data() {
    return {
      salespersonSelect: [],
      shareAll: false,
      searchUsers: '',
      isLoading: false,
    };
  },

  computed: {
    showShareUnit: {
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

  methods: {
    shareUnit() {
      this.isLoading = true;
      this.$store
        .dispatch('share/shareUnit', {
          share_all: this.shareAll,
          user_ids: this.salespersonSelect,
          unit_id: this.unit_id,
        })
        .then(({ data }) => {
          this.$notify('success', 'Unit Shared!', true);
          (this.salespersonSelect = []),
            (this.shareAll = false),
            (this.showShareUnit = false);
          this.isLoading = false;
        })
        .catch(() => {
          this.$notify('error', 'Please select users!', true);
          this.isLoading = false;
        });
    },

    changedUser() {
      this.searchUsers = '';
    },
  },
};
</script>
