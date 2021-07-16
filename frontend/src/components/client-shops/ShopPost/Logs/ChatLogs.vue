<template>
  <v-card flat>
    <v-expansion-panels v-if="internetShop.chat_screenshots.length">
      <v-expansion-panel
        v-for="(chat, index) in internetShop.chat_screenshots"
        :key="chat.id"
      >
        <v-expansion-panel-header>
          #{{ index + 1 }} Chat
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-img
            :src="`https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/${internetShop.id}/chat/${chat.value}`"
          >
          </v-img>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
    <p v-else class="lead ma-3 pa-2">No Chat Logs Data</p>
  </v-card>
</template>
<script>
export default {
  name: 'ChatLogs',
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
