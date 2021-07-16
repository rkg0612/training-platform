<template>
  <v-card flat>
    <v-expansion-panels v-if="internetShop.email_screenshots.length">
      <v-expansion-panel
        v-for="(email, index) in internetShop.email_screenshots"
        :key="email.id"
      >
        <v-expansion-panel-header>
          #{{ index + 1 }} Email
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-img
            :src="`https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/${internetShop.id}/email/${email.value}`"
          >
          </v-img>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
    <p v-else class="lead ma-3 pa-2">No Email Logs Data</p>
  </v-card>
</template>
<script>
export default {
  name: 'EmailLogs',
  props: {
    shop: {
      required: false,
      type: Object,
    },
  },

  computed: {
    internetShop() {
      return this.shop;
    },
  },
};
</script>
