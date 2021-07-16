<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label">
        <span class="kt-portlet__head-icon">
          <i class="far fa-calendar-star"></i>
        </span>
        <h3 class="kt-portlet__head-title">Webinars</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div class="kt-portlet__head-group">
          <v-btn
            color="blue darken-3"
            dark
            @click.stop="showWebinarForm = true"
            text
          >
            <i class="fal fa-plus mr-2"></i>
            New Event
          </v-btn>
        </div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <webinar-form
        v-model="showWebinarForm"
        :editEvent="selectedEvent"
        :toggleShowWebinarForm="toggleShowWebinarForm"
        :key="showWebinarFormCount"
      />
      <v-row class="fill-height">
        <v-col>
          <v-sheet height="64">
            <v-toolbar flat color="white">
              <v-btn outlined class="mr-4" @click="setToday"> Today </v-btn>
              <v-btn fab text small @click="prev">
                <i class="far fa-chevron-left"></i>
              </v-btn>
              <v-btn fab text small @click="next">
                <i class="far fa-chevron-right"></i>
              </v-btn>
              <v-toolbar-title>{{ title }}</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-menu bottom right>
                <template v-slot:activator="{ on }">
                  <v-btn outlined v-on="on">
                    <span>{{ typeToLabel[type] }}</span>
                    <v-spacer></v-spacer>
                    <i class="far fa-caret-down"></i>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item @click="type = 'day'">
                    <v-list-item-title>Day</v-list-item-title>
                  </v-list-item>
                  <v-list-item @click="type = 'week'">
                    <v-list-item-title>Week</v-list-item-title>
                  </v-list-item>
                  <v-list-item @click="type = 'month'">
                    <v-list-item-title>Month</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-toolbar>
          </v-sheet>
          <v-sheet class="calendar-height">
            <v-calendar
              ref="calendar"
              v-model="focus"
              color="primary"
              :events="events"
              :event-color="getEventColor"
              :event-margin-bottom="3"
              :now="today"
              :type="type"
              @click:event="showEvent"
              @click:more="viewDay"
              @click:date="viewDay"
              @change="updateRange"
            />
            <v-menu
              v-model="selectedOpen"
              :close-on-content-click="false"
              :activator="selectedElement"
              offset-x
            >
              <v-card color="grey lighten-4" min-width="350px" flat>
                <v-toolbar :color="selectedEvent.color" dark>
                  <v-btn icon @click="editEvent" v-show="showEditButton">
                    <i class="fal fa-edit" />
                  </v-btn>
                  <v-toolbar-title v-html="selectedEvent.name">
                  </v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                  <span v-html="selectedEvent.link" />
                </v-card-text>
                <v-card-actions>
                  <v-btn text color="secondary" @click="selectedOpen = false">
                    Cancel
                  </v-btn>
                  <v-spacer></v-spacer>
                  <v-btn @click="showSyncDialog = true">
                    Add to My Calendar
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-menu>
          </v-sheet>
        </v-col>
      </v-row>
    </div>
    <v-dialog max-width="300px" v-model="showSyncDialog">
      <v-card>
        <v-app-bar color="warning" dark>
          <v-toolbar-title>Add to My Calendar</v-toolbar-title>
        </v-app-bar>
        <v-card-text>
          <v-row>
            <v-col>
              <v-btn
                icon
                tile
                x-large
                color="red"
                @click="addToCalendar('gcal')"
                :loading="syncToCalendar"
              >
                <v-icon>fab fa-google</v-icon>
              </v-btn>
            </v-col>
            <v-col>
              <v-btn
                icon
                tile
                x-large
                color="blue"
                @click="addToCalendar('owa')"
                :loading="syncToCalendar"
              >
                <v-icon>fab fa-microsoft</v-icon>
              </v-btn>
            </v-col>
            <v-col>
              <v-btn
                icon
                tile
                x-large
                @click="addToCalendar('icloud')"
                :loading="syncToCalendar"
              >
                <v-icon>fab fa-apple</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { DateTime as LuxonDateTime } from 'luxon';
import WebinarForm from './WebinarForm.vue';

export default {
  name: 'WebinarCal',
  components: {
    WebinarForm,
  },
  data() {
    return {
      showSyncDialog: false,
      showWebinarForm: false,
      today: LuxonDateTime.local().toISODate(),
      focus: LuxonDateTime.local().toISODate(),
      syncToCalendar: false,
      type: 'month',
      typeToLabel: {
        month: 'Month',
        week: 'Week',
        day: 'Day',
      },
      start: null,
      end: null,
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
      loading: false,
      showWebinarFormCount: 0,
    };
  },
  computed: {
    title() {
      const { start, end } = this;
      if (!start || !end) {
        return '';
      }
      const startMonth = this.monthFormatter(start);
      const endMonth = this.monthFormatter(end);
      const suffixMonth = startMonth === endMonth ? '' : endMonth;

      const startYear = start.year;
      const endYear = end.year;
      const suffixYear = startYear === endYear ? '' : endYear;

      const startDay = start.day + this.nth(start.day);
      const endDay = end.day + this.nth(end.day);
      if (this.type === 'month') {
        return `${startMonth} ${startYear}`;
      }
      if (this.type === 'week') {
        return '';
      }
      if (this.type === '4day') {
        return `${startMonth} ${startDay} ${startYear} - ${suffixMonth} ${endDay} ${suffixYear}`;
      }
      if (this.type === 'day') {
        return `${startMonth} ${startDay} ${startYear}`;
      }
      return '';
    },
    monthFormatter() {
      return this.$refs.calendar.getFormatter({
        timeZone: 'UTC',
        month: 'long',
      });
    },
    events() {
      return this.$store.state.events.events;
    },
    user() {
      return this.$auth.user();
    },
    showEditButton() {
      return this.user.id === this.selectedEvent.user_id;
    },
  },
  mounted() {
    this.$refs.calendar.checkChange();
  },

  methods: {
    editEvent() {
      this.showWebinarForm = true;
    },
    viewDay({ date }) {
      this.focus = date;
      this.type = 'day';
    },
    getEventColor(event) {
      return event.color;
    },
    setToday() {
      this.focus = this.today;
    },
    prev() {
      this.$refs.calendar.prev();
    },
    next() {
      this.$refs.calendar.next();
    },
    showEvent({ nativeEvent, event }) {
      const open = () => {
        this.selectedEvent = event;
        this.selectedElement = nativeEvent.target;
        setTimeout(() => (this.selectedOpen = true), 10);
      };
      if (this.selectedOpen) {
        this.selectedOpen = false;
        setTimeout(open, 10);
      } else {
        open();
      }
      nativeEvent.stopPropagation();
    },
    updateRange({ start, end }) {
      this.start = start;
      this.end = end;
      this.$store.dispatch('events/getEvents', {
        month: start.month,
        year: start.year,
      });
    },
    nth(d) {
      return d > 3 && d < 21
        ? 'th'
        : ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'][d % 10];
    },
    addToCalendar(type) {
      this.syncToCalendar = true;
      this.axios
        .post(`/event/${this.selectedEvent.id}/sync-to-calendar/`, {
          type: type,
        })
        .then(({ data }) => {
          window.open(data.url, '_blank');
          this.syncToCalendar = false;
        });
    },
    toggleShowWebinarForm() {
      this.showWebinarForm = !this.showWebinarForm;
      this.showWebinarFormCount = this.showWebinarFormCount + 1;
    },
  },
};
</script>
<style lang="stylus">
.calendar-height
    height 600px

@media screen and (max-width: 1365px)
    .calendar-height
        height 500px
@media screen and (max-width: 665px)
    .calendar-height
        height 400px
@media screen and (max-width: 435px)
    .calendar-height
        height 280px
        font-size 10px
    .calendar-height
          .v-size--small
              height 32px
              width 32px
              min-width 32px
</style>
