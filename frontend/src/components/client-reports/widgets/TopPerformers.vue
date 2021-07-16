<template>
  <div>
    <v-card class="p-2">
      <GChart
        type="ColumnChart"
        :data="topPerformers"
        :options="topPerformersOptions"
        :settings="{ packages: ['bar'] }"
        :createChart="(el, google) => new google.charts.Bar(el)"
        class="mr-5"
      />
    </v-card>
  </div>
</template>
<script>
import {
  findIndex as _findIndex,
  orderBy as _orderBy,
  take as _take,
} from 'lodash-es';

export default {
  name: 'TopPerformers',
  props: ['loading', 'model'],
  data() {
    return {
      // Array will be automatically processed with visualization.arrayToDataTable function
      topPerformers: [
        [
          'Salesperson',
          'Units Completed',
          'VOTD Completed',
          'Days Visited',
          'Video Completed',
        ],
      ],
      topPerformersOptions: {
        chart: {
          title: 'Top Performers',
        },
        bars: 'vertical',
        hAxis: { format: 'decimal' },
        height: 400,
        colors: ['#0277BD'],
      },
    };
  },
  methods: {
    initializePerformers() {
      const users = [];

      this.model.mostUnitsCompleted.forEach((item) => {
        users.push({
          id: item.id,
          name: item.name,
          mostUnitsCompleted: item.count,
        });
      });

      this.model.mostWatchedVideoOfTheDay.forEach((item) => {
        const userIndex = _findIndex(users, { id: item.id });

        if (userIndex !== -1) {
          users[userIndex].mostWatchedVideoOfTheDay = item.count;
        } else {
          users.push({
            id: item.id,
            name: item.name,
            mostWatchedVideoOfTheDay: item.count,
          });
        }
      });

      this.model.mostDaysVisited.forEach((item) => {
        const userIndex = _findIndex(users, { id: item.id });

        if (userIndex !== -1) {
          users[userIndex].mostDaysVisited = item.count;
        } else {
          users.push({
            id: item.id,
            name: item.name,
            mostDaysVisited: item.count,
          });
        }
      });

      this.model.mostVideoWatched.forEach((item) => {
        const userIndex = _findIndex(users, { id: item.id });

        if (userIndex !== -1) {
          users[userIndex].mostVideoWatched = item.count;
        } else {
          users.push({
            id: item.id,
            name: item.name,
            mostVideoWatched: item.count,
          });
        }
      });

      this.processUsers(users);
    },
    processUsers(users) {
      let sorted = _orderBy(
        users,
        (item) => {
          let total = 0;

          if (item.mostUnitsCompleted !== undefined) {
            total += item.mostUnitsCompleted;
          }

          if (item.mostWatchedVideoOfTheDay !== undefined) {
            total += item.mostWatchedVideoOfTheDay;
          }

          if (item.mostDaysVisited !== undefined) {
            total += item.mostDaysVisited;
          }

          if (item.mostVideoWatched !== undefined) {
            total += item.mostVideoWatched;
          }

          return total;
        },
        'desc'
      );

      sorted = _take(sorted, 5);

      sorted.forEach((item) => {
        const user = [];
        user.push(item.name);
        user.push(
          item.mostUnitsCompleted !== undefined ? item.mostUnitsCompleted : 0
        );
        user.push(
          item.mostWatchedVideoOfTheDay !== undefined
            ? item.mostWatchedVideoOfTheDay
            : 0
        );
        user.push(
          item.mostDaysVisited !== undefined ? item.mostDaysVisited : 0
        );
        user.push(
          item.mostVideoWatched !== undefined ? item.mostVideoWatched : 0
        );

        this.topPerformers.push(user);
      });

      this.removeLegends();
    },
    removeLegends() {
      const totals = {
        mostUnitsCompleted: 0,
        mostWatchedVideoOfTheDay: 0,
        mostDaysVisited: 0,
        mostVideoWatched: 0,
      };

      this.topPerformers.slice(1).forEach((item) => {
        totals.mostUnitsCompleted += item[1];
        totals.mostWatchedVideoOfTheDay += item[2];
        totals.mostDaysVisited += item[3];
        totals.mostVideoWatched += item[4];
      });

      const result = [];
      this.topPerformers.forEach((item) => {
        const user = [item[0]];

        if (totals.mostUnitsCompleted >= 5) {
          user.push(item[1]);
        }

        if (totals.mostWatchedVideoOfTheDay >= 5) {
          user.push(item[2]);
        }

        if (totals.mostDaysVisited >= 5) {
          user.push(item[3]);
        }

        if (totals.mostVideoWatched >= 5) {
          user.push(item[4]);
        }

        result.push(user);
      });

      this.topPerformers = result;
    },
  },
};
</script>
