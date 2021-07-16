<template>
  <v-dialog v-model="dialog" persistent max-width="500">
    <v-card>
      <v-card-title class="headline"> No Access! </v-card-title>
      <v-card-text>
        <h5>You have no access to {{ type }} service.</h5>
        <h5>Please email sales@webinarinc.com to upgrade your product.</h5>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="green darken-1" text @click="emailSales">
          Email Sales
        </v-btn>
        <v-btn color="#f9b418" text @click="goToHome"> Go to home </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'NoAccessDialog',
  computed: {
    type() {
      const lmsType = this.$store.state.app.noAccessType === 'LMS';

      return lmsType ? 'LMS' : 'Secret Shop';
    },
    dialog: {
      get() {
        return this.$store.state.app.noAccessDialog;
      },
      set() {
        return this.$store.dispatch('app/toggleNoAccessDialog');
      },
    },
  },
  methods: {
    emailSales() {
      window.location.href = 'mailto:sales@webinarinc.com';
    },
    goToHome() {
      this.$router.push({
        name: 'Home',
      });
      this.$store.dispatch('app/toggleNoAccessDialog');
    },
  },
};
</script>

<style scoped></style>
