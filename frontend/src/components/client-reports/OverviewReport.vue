<template>
  <div class="kt-portlet mt-5 mt-md-3 mt-lg-0">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm flex-wrap"
    >
      <div class="kt-portlet__head-label pt-3 pt-md-0">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-chart-area"></i>
        </span>
        <h3 class="kt-portlet__head-title">Overview Report</h3>
      </div>
      <div class="kt-portlet__head-toolbar">
        <div
          class="kt-portlet__head-group d-flex flex-wrap justify-center"
        ></div>
      </div>
    </div>
    <div class="kt-portlet__body">
      <div class="row">
        <div class="col-lg-4">
          <top-learner :model="statistics" :loading="loading" />
        </div>
        <div class="col-lg-8">
          <v-skeleton-loader type="article" v-if="loading" />
          <top-performers v-else :model="statistics" ref="topPerformers" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import TopLearner from './widgets/TopLearner.vue';
import TopPerformers from './widgets/TopPerformers.vue';

export default {
  name: 'OverviewReport',
  components: {
    TopLearner,
    TopPerformers,
  },
  data() {
    return {
      statistics: {
        mostUnitsCompleted: [],
        mostWatchedVideoOfTheDay: [],
        mostDaysVisited: [],
        mostVideoWatched: [],
      },
      loading: true,
    };
  },
  methods: {
    getData() {
      this.$http
        .get('/client/reports/lms/overview')
        .then(({ data }) => {
          this.loading = false;

          this.statistics.mostUnitsCompleted = data.mostUnitsCompleted;
          this.statistics.mostWatchedVideoOfTheDay =
            data.mostWatchedVideoOfTheDay;
          this.statistics.mostDaysVisited = data.mostDaysVisited;
          this.statistics.mostVideoWatched = data.mostVideoWatched;

          this.$nextTick(() => this.$refs.topPerformers.initializePerformers());
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  mounted() {
    this.getData();
  },
};
</script>
<style scoped lang="stylus">
.overview-table
  table-layout fixed
</style>
