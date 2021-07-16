<template>
  <div>
    <v-skeleton-loader type="article" v-if="loading" />
    <v-card v-else>
      <v-list-item two-line>
        <v-list-item-content>
          <v-list-item-title class="headline" v-show="sections.mostCompleted">
            Most Units Completed
          </v-list-item-title>
          <v-list-item-subtitle
            class="mb-3 subtitle-1"
            v-show="sections.mostCompleted"
          >
            {{ mostCompleted() }}
          </v-list-item-subtitle>
          <v-list-item-title
            class="headline"
            v-show="sections.mostWatchedVideoOfTheDay"
          >
            Most Watched Video of the Day
          </v-list-item-title>
          <v-list-item-subtitle
            class="mb-3 subtitle-1"
            v-show="sections.mostWatchedVideoOfTheDay"
          >
            {{ mostWatchedVideoOfTheDay() }}
          </v-list-item-subtitle>
          <v-list-item-title class="headline" v-show="sections.mostDaysVisited">
            Most Days Visited for the Past 30 Days
          </v-list-item-title>
          <v-list-item-subtitle
            class="mb-3 subtitle-1"
            v-show="sections.mostDaysVisited"
          >
            {{ mostDaysVisited() }}
          </v-list-item-subtitle>
          <v-list-item-title
            class="headline"
            v-show="sections.mostVideoWatched"
          >
            Most Completed Video
          </v-list-item-title>
          <v-list-item-subtitle
            class="mb-3 subtitle-1"
            v-show="sections.mostVideoWatched"
          >
            {{ mostVideoWatched() }}
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-card>
  </div>
</template>
<script>
import { map as _map, filter as _filter } from 'lodash-es';

export default {
  name: 'TopLearner',
  props: ['model', 'loading'],
  data() {
    return {
      sections: {
        mostCompleted: true,
        mostWatchedVideoOfTheDay: true,
        mostDaysVisited: true,
        mostVideoWatched: true,
      },
    };
  },
  methods: {
    mostCompleted() {
      if (this.model.mostUnitsCompleted.length < 1) {
        this.sections.mostCompleted = false;
        return '';
      }

      const users = this.getTopUsers(this.model.mostUnitsCompleted);

      return _map(users, (item) => item.name).join(', ');
    },
    mostWatchedVideoOfTheDay() {
      if (this.model.mostWatchedVideoOfTheDay.length < 1) {
        this.sections.mostWatchedVideoOfTheDay = false;
        return '';
      }

      const users = this.getTopUsers(this.model.mostWatchedVideoOfTheDay);

      return _map(users, (item) => item.name).join(', ');
    },
    mostDaysVisited() {
      if (this.model.mostDaysVisited.length < 1) {
        this.sections.mostDaysVisited = false;
        return '';
      }

      const users = this.getTopUsers(this.model.mostDaysVisited);

      return _map(users, (item) => item.name).join(', ');
    },
    mostVideoWatched() {
      if (this.model.mostVideoWatched.length < 1) {
        this.sections.mostVideoWatched = false;
        return '';
      }

      const users = this.getTopUsers(this.model.mostVideoWatched);

      return _map(users, (item) => item.name).join(', ');
    },
    // this will determine users who has the highest count
    getTopUsers(val) {
      let highestCount = 0;

      return _filter(val, (user) => {
        if (highestCount === 0) {
          highestCount = user.count;
        }

        return highestCount <= user.count;
      });
    },
  },
};
</script>
