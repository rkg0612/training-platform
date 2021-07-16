<template>
  <v-dialog v-model="showWebinarForm" persistent max-width="600px">
    <v-card>
      <v-card-title>
        <span class="headline">Create New Event</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <form class="kt-form">
            <div class="form-group">
              <label>Name</label>
              <input
                type="text"
                class="form-control"
                placeholder="Title of the Event"
                v-model="name"
                v-validate="'required'"
                name="name"
              />
              <span class="kt-font-danger validate-error">
                {{ errors.first('name') }}
              </span>
            </div>
            <div class="form-group">
              <label>Registration URL</label>
              <input
                type="text"
                class="form-control"
                placeholder="Enter Registration URL"
                v-model="regUrl"
                v-validate="'required'"
                name="url"
              />
              <span class="kt-font-danger validate-error">
                {{ errors.first('url') }}
              </span>
            </div>
            <div class="form-group">
              <label>Start Date and Time</label>
              <input
                type="text"
                class="form-control"
                placeholder="Select date"
                :value="startDateFormatted"
                v-validate="'required'"
                name="startDate"
                @click="openDatePicker('startDate')"
              />
              <span class="kt-font-danger validate-error">
                {{ errors.first('startDate') }}
              </span>
            </div>
            <div class="form-group">
              <label>End Date and Time</label>
              <input
                type="text"
                class="form-control"
                placeholder="Select date"
                :value="endDateFormatted"
                v-validate="'required'"
                name="endDate"
                @click="openDatePicker('endDate')"
              />
              <span class="kt-font-danger validate-error">
                {{ errors.first('endDate') }}
              </span>
            </div>
            <div class="kt-form__actions text-center">
              <v-btn
                color="blue darken-2"
                dark
                :loading="isLoading"
                @click="submit"
              >
                Submit
              </v-btn>
              &nbsp;
              <v-btn
                color="grey lighten-1"
                dark
                @click="showWebinarForm = false"
              >
                Close
              </v-btn>
            </div>
          </form>
        </v-container>
      </v-card-text>
    </v-card>
    <date-picker
      :dialog="datePickerDialog"
      :datePickerType="datePickerType"
      :toggleDatePickerDialog="toggleDatePickerDialog"
      :saveDatePicker="saveDatePicker"
      :startDate="startDate"
      :endDate="endDate"
    />
  </v-dialog>
</template>
<script>
import { forEach as _forEach, head as _head } from 'lodash-es';
import moment from 'moment';
import DatePicker from '@/components/dashboard/DatePicker.vue';

export default {
  name: 'WebinarForm',
  components: {
    DatePicker,
  },
  props: {
    value: Boolean,
    editEvent: {},
    toggleShowWebinarForm: Function,
  },
  data() {
    return {
      id: 0,
      name: '',
      regUrl: '',
      startDate: null,
      endDate: null,
      color: 'blue',
      isLoading: false,
      datePickerDialog: false,
      datePickerType: '',
    };
  },
  computed: {
    showWebinarForm: {
      get() {
        return this.value;
      },
      set() {
        this.toggleShowWebinarForm();
      },
    },
    startDateFormatted() {
      if (this.startDate === null) {
        return null;
      }

      return moment(this.startDate).format('ddd, MMM D, YYYY H:mm A');
    },
    endDateFormatted() {
      if (this.endDate === null) {
        return null;
      }

      return moment(this.endDate).format('ddd, MMM D, YYYY H:mm A');
    },
  },
  mounted() {
    this.$validator.localize('en', {
      attributes: {
        name: 'name',
        regUrl: 'registration url',
      },
    });
  },
  watch: {
    // eslint-disable-next-line no-unused-vars
    value(show) {
      if (!show) {
        this.clear();
      }

      if (show && this.editEvent) {
        this.id = this.editEvent.id;
        this.name = this.editEvent.name;
        this.regUrl = this.editEvent.link;
      }
    },
  },
  methods: {
    submit() {
      const data = {
        id: this.id,
        name: this.name,
        url: this.regUrl,
        start_at: this.startDate,
        end_at: this.endDate,
        color: this.color,
      };
      this.$validator.validate('*').then((isValid) => {
        if (isValid) {
          // eslint-disable-next-line eqeqeq,no-unused-expressions
          this.isLoading = true;
          if (this.value && Object.keys(this.editEvent).length === 0) {
            return this.store(data);
          }
          return this.patch(data);
        }
      });
    },
    patch(payload) {
      this.$http
        .patch(`/events/${payload.id}`, payload)
        .then(({ data }) => {
          this.$store.dispatch('events/updateEvent', data);
          this.close();
          this.$notify('success', 'Event updated');
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    store(val) {
      this.$http
        .post('/events', val)
        .then(({ data }) => {
          this.$store.dispatch('events/addEvent', data);

          this.close();
          this.$notify('success', 'Event added');
        })
        .catch((errors) => {
          const messages = errors.response.data.errors;
          _forEach(messages, (value, key) => {
            this.errors.add({
              field: key,
              // eslint-disable-next-line no-undef
              msg: _head(value),
            });
          });
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    close() {
      this.clear();
      this.toggleShowWebinarForm();
    },
    clear() {
      this.id = 0;
      this.name = '';
      this.regUrl = '';
      this.startDate = null;
      this.endDate = null;
    },
    openDatePicker(type) {
      this.toggleDatePickerDialog();
      this.datePickerType = type;
    },
    toggleDatePickerDialog() {
      this.datePickerDialog = !this.datePickerDialog;
    },
    saveDatePicker(val) {
      this[this.datePickerType] = val;
    },
  },
};
</script>
