<template>
  <v-dialog v-model="dialog" persistent max-width="500">
    <v-card>
      <v-card-title class="headline"> No Access! </v-card-title>
      <v-card-text>
        <h5>You don't have permission to access this page.</h5>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="#f9b418" text @click="goToHome"> Go to home </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'NoAccessPermissionDialog',
  computed: {
    dialog: {
      get() {
        return this.$store.state.app.noAccessPermissionDialog;
      },
      set() {
        return this.$store.dispatch('app/toggleNoAccessPermissionDialog');
      },
    },
  },
  methods: {
    goToHome() {
      this.$router.push({
        name: 'Home',
      });
      this.$store.dispatch('app/toggleNoAccessPermissionDialog');
    },
  },
};
</script>

<style scoped></style>
