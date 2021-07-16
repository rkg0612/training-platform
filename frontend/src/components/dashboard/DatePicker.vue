<template>
  <v-dialog v-model="dialogModel" max-width="550">
    <v-card>
      <v-card-text>
        <VueCtkDateTimePicker
          v-model="value"
          v-validate="'required'"
          no-clear-button
          name="start_at"
          inline
        />
      </v-card-text>
      <v-card-actions>
        <v-btn text color="secondary" @click="dialogModel = false">
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DatePicker',
  props: [
    'dialog',
    'datePickerType',
    'toggleDatePickerDialog',
    'saveDatePicker',
    'startDate',
    'endDate',
  ],
  computed: {
    dialogModel: {
      get() {
        return this.dialog;
      },
      set() {
        this.toggleDatePickerDialog();
      },
    },
    value: {
      get() {
        return this[this.datePickerType];
      },
      set(value) {
        this.saveDatePicker(value);
      },
    },
  },
};
</script>

<style scoped></style>
