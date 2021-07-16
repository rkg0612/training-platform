<template>
  <v-dialog v-model="showNoteDialog" max-width="500">
    <v-card>
      <div v-for="(note, index) in availNotes" :key="index">
        <h5 class="m-3 p-3 text-left">Date: {{ note.created_at }}</h5>
        <p class="text-center subtitle-1" v-html="note.value"></p>
        <v-divider></v-divider>
      </div>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red lighten-1" text @click="showNoteDialog = false">
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: 'NoteDialog',

  props: {
    value: Boolean,
    notes: Array,
  },

  computed: {
    availNotes() {
      return this.notes;
    },
    showNoteDialog: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
  },
};
</script>
