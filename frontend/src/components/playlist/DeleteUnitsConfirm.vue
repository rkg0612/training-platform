<template>
  <v-dialog v-model="deleteConfirmDialog" max-width="290" persistent>
    <v-card>
      <v-card-title class="headline">Confirm</v-card-title>

      <v-card-text>
        Are you sure you want to delete {{ deleteWord }}?
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>

        <v-btn text @click="toggleDeleteConfirmation"> Cancel </v-btn>

        <v-btn color="primary" text @click="deleteUnits"> Proceed </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DeleteUnitsConfirm',
  props: {
    toggleDeleteConfirmation: Function,
    deleteConfirmDialog: Boolean,
    deleteUnits: Function,
    unitsToDelete: Array,
  },
  computed: {
    deleteWord() {
      return this.unitsToDelete.length > 1 ? 'these units' : 'this unit';
    },
  },
};
</script>

<style scoped></style>
