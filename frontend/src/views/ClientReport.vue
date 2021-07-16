<template>
  <div>
    <overview-report />
    <module-progress
      :users="users"
      :spec_dealers="specificDealers"
      :modules="modules"
      :outstanding_modules="outstandingModules"
      :outstanding_playlists="outstandingPlaylists"
    ></module-progress>
    <team-progress
      :users="users"
      :spec_dealers="specificDealers"
      :modules="modules"
      :outstanding_modules="outstandingModules"
      :outstanding_playlists="outstandingPlaylists"
    ></team-progress>
  </div>
</template>
<script>
import ModuleProgress from '../components/client-reports/ModuleProgress.vue';
import TeamProgress from '../components/client-reports/TeamProgress.vue';
import OverviewReport from '../components/client-reports/OverviewReport.vue';

export default {
  name: 'ClientReport',

  components: {
    ModuleProgress,
    TeamProgress,
    OverviewReport,
  },

  data: () => ({
    users: [],
    specificDealers: [],
    modules: [],
    outstandingModules: [],
    outstandingPlaylists: [],
  }),

  created() {
    this.init();
  },

  methods: {
    init() {
      this.fetchUsers();
      this.fetchDefModules();
      this.fetchspecificdealers();
      this.fetchOutstandingModules();
      this.fetchOutstandingPlaylists();
    },

    fetchUsers() {
      this.axios
        .post('/client/reports/fetchusers')
        .then(({ data }) => {
          this.users = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    fetchspecificdealers() {
      this.axios
        .get('/client/reports/fetchspecificdealers')
        .then(({ data }) => {
          this.specificDealers = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    fetchDefModules() {
      this.axios
        .get('/client/reports/fetchmodules')
        .then(({ data }) => {
          this.modules = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    fetchOutstandingModules() {
      this.axios
        .get('/client/reports/fetchoutstandingmodules')
        .then(({ data }) => {
          this.outstandingModules = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    fetchOutstandingPlaylists() {
      this.axios
        .get('/client/reports/fetchoutstandingplaylists')
        .then(({ data }) => {
          this.outstandingPlaylists = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
