<template>
  <v-dialog v-model="deleteConfirmDialog" max-width="290" persistent>
    <v-card>
      <v-card-title class="headline">Confirm</v-card-title>

      <v-card-text>
        Are you sure you want to delete this playlist?
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>

        <v-btn text @click="toggleDeleteConfirmation"> Cancel </v-btn>

        <v-btn
          color="primary"
          text
          @click="deletePlaylist"
          :loading="isLoading"
        >
          Proceed
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DeleteConfirm',
  props: {
    toggleDeleteConfirmation: Function,
    isLoading: Boolean,
    deleteConfirmDialog: Boolean,
    deletePlaylist: Function,
  },
};
</script>

<style scoped></style>
