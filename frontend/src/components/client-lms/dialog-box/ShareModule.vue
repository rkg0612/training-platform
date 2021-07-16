<template>
  <v-dialog v-model="showShareModule" persistent max-width="600px">
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
            <v-col cols="12" sm="6" md="4">
              <v-checkbox v-model="shareAll" label="Share to All"></v-checkbox>
            </v-col>
            <v-col cols="12" sm="6" md="8">
              <v-autocomplete
                v-model="salespersonSelect"
                :items="salesPersons"
                item-value="id"
                item-text="name"
                :menu-props="{ closeOnClick: true }"
                label="Share to Specific Salesperson"
                multiple
              ></v-autocomplete>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="shareModule" :loading="isLoading">
          Save
        </v-btn>
        <v-btn
          color="blue-grey"
          text
          @click="showShareModule = false"
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
  name: 'ShareModule',

  props: {
    value: Boolean,
    module_id: Number,
  },

  data() {
    return {
      salespersonSelect: [],
      shareAll: false,
      isLoading: false,
    };
  },

  computed: {
    showShareModule: {
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
    shareModule() {
      this.isLoading = true;
      this.$store
        .dispatch('share/shareModule', {
          share_all: this.shareAll,
          user_ids: this.salespersonSelect,
          module_id: this.module_id,
        })
        .then(({ data }) => {
          console.log(data);
          this.$notify('success', 'Module Shared!', true);
          (this.salespersonSelect = []),
            (this.shareAll = false),
            (this.showShareModule = false);
          this.isLoading = false;
        })
        .catch(() => {
          this.$notify('error', 'Please select users!', true);
          this.isLoading = false;
        });
    },
  },
};
</script>
