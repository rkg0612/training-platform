<template>
  <v-card class="mt-2">
    <component-loader :active="isFetching"></component-loader>
    <v-app-bar flat class="pt-2" height="unset" color="grey lighten-4">
      <div class="text-center w-100">
        <h1 class="text-uppercase font-weight-bold" style="font-family: oswald">
          <i class="fal fa-lightbulb"></i>
          {{ moduleBuild.module.name }}
        </h1>
        <p v-html="moduleBuild.module.description"></p>
      </div>
    </v-app-bar>
    <div class="container">
      <v-row>
        <v-col cols="12" sm="12" md="6">
          <!-- start of progress bar -->
          <label class="mb-0 mr-0 mr-sm-2 mr-md-3">
            <b>Module Progress:</b>
          </label>
          <v-progress-linear
            :value="moduleBuild.progress"
            color="#f9b418"
            height="20"
            striped
            rounded
          >
            <template v-slot="{ value }">
              <strong>{{ Math.ceil(value) }}%</strong>
            </template>
          </v-progress-linear>
          <p class="subtitle-2">
            This progress bar updates whenever you complete a unit in this
            module.
          </p>
          <!-- end of progress bar -->
        </v-col>
        <v-col cols="12" sm="12" md="6">
          <v-text-field
            v-model="search"
            label="Search units in the module"
            prepend-icon="fal fa-search"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col
          cols="12"
          sm="12"
          md="4"
          v-if="!isEmpty(moduleBuild.module.my_assigned)"
        >
          <h3 class="subtitle-1">
            <i class="fal fa-calendar-star"></i>
            This module has been assigned to you<br />
            <span class="ml-5"> Due Date: {{ dueDate }} </span>
          </h3>
        </v-col>
        <v-col cols="12" sm="12" md="4" v-else></v-col>
        <v-col cols="12" sm="12" md="8">
          <!-- start of buttons -->
          <div
            class="d-flex flex-wrap justify-content-center justify-content-md-end w-100"
          >
            <v-menu open-on-hover top offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn text color="primary" dark v-bind="attrs" v-on="on">
                  <v-icon right dark class="mr-2">fal fa-download</v-icon>
                  Downloadable Files
                </v-btn>
              </template>

              <v-list>
                <v-list-item
                  v-for="(item, index) in downloadLinks"
                  :key="index"
                  :href="item.link"
                  target="_blank"
                >
                  <v-list-item-title>
                    {{ item.title }}
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
            <v-btn
              color="light-blue darken-1"
              class="ms-2 white--text"
              @click.stop="showShareModule = true"
              text
            >
              <v-icon right dark class="mr-2">fal fa-share-alt</v-icon>Share
              Module
              <share-module
                :module_id="moduleBuild.module.id"
                v-model="showShareModule"
              />
            </v-btn>
            <v-btn
              color="light-blue accent-4"
              class="ms-2 white--text"
              @click.stop="showAssignModule = true"
              text
            >
              <v-icon right dark class="mr-2">fal fa-users</v-icon>Assign Module
              <assign-module
                :module_id="moduleBuild.module.id"
                v-model="showAssignModule"
              />
            </v-btn>
          </div>
          <!-- end of buttons -->
        </v-col>
      </v-row>
    </div>
    <!-- start of 1st video -->
    <div class="container">
      <v-img
        class="white--text"
        v-if="
          includes(
            imageFileTypes,
            moduleBuild.module.thumbnail.split('.').slice(-1).toString()
          )
        "
        :src="moduleBuild.module.thumbnail"
        width="100%"
        height="100%"
      >
      </v-img>
      <div v-else class="embed-responsive embed-responsive-21by9">
        <iframe
          title=""
          :id="'module-page-intro-video-build-' + moduleBuild.module.id"
          class="embed-responsive-item"
          :src="moduleBuild.module.thumbnail"
          allowfullscreen
        ></iframe>
      </div>
      <div class="row">
        <div class="col-lg-12 text-center">
          <v-btn
            text
            color="primary"
            :disabled="moduleBuild.module.intro_video_watched"
            @click="markAsWatched(moduleBuild.module.id)"
            v-if="
              moduleBuild.module.thumbnail.split('.').slice(-1).toString() !==
                'jpg' &&
              moduleBuild.module.thumbnail.split('.').slice(-1).toString() !==
                'png' &&
              moduleBuild.module.thumbnail.split('.').slice(-1).toString() !==
                'webp'
            "
          >
            Mark as Completed
          </v-btn>
        </div>
      </div>
    </div>
    <!-- end of 1st video -->

    <!-- start of units divider -->
    <div class="px-5" v-for="series in moduleBuild.series">
      <div v-if="series.is_banner">
        <v-app-bar flat class="pt-1 mb-3" height="unset">
          <h1 class="text-uppercase text-center w-100">{{ series.name }}</h1>
          <hr />
        </v-app-bar>
        <router-link
          :to="{ name: 'UnitPage', params: { id: series.units[0].unit_id } }"
        >
          <v-img
            class="white--text mb-3"
            :src="series.units[0].unit.thumbnail"
            height="200px"
            width="100%"
            gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.7)"
          >
          </v-img>
        </router-link>
      </div>
      <div v-else>
        <v-app-bar flat class="pt-1" height="unset">
          <h1 class="text-uppercase text-center w-100">{{ series.name }}</h1>
          <hr />
        </v-app-bar>
        <div class="row">
          <div
            class="col-md-6 col-lg-4"
            v-for="unitSeries in filteredUnits(series.units)"
          >
            <unit-card :unit="unitSeries"></unit-card>
          </div>
        </div>
      </div>
    </div>
    <!-- end of units divider -->
  </v-card>
</template>
<script>
import UnitCard from './UnitCard.vue';
import AssignModule from './dialog-box/AssignModule.vue';
import ShareModule from './dialog-box/ShareModule.vue';
import dayjs from 'dayjs';
import {
  map as _map,
  filter as _filter,
  isEmpty as _isEmpty,
  includes as _includes,
} from 'lodash-es';

export default {
  name: 'ModulePage',

  data: () => ({
    includes: _includes,
    isEmpty: _isEmpty,
    imageFileTypes: ['jpg', 'png', 'webp'],
    modprog: 78,
    isFetching: false,
    showAssignModule: false,
    showShareModule: false,
    search: '',
    played: false,
    downloadLinks: [
      {
        title: 'Salesbook',
        link:
          'https://docs.google.com/document/d/1yXDXBMDhBqxUrh_n2AWlAUtIYPOSFCCbPgtn74jOdsw/edit?usp=sharing',
      },
      {
        title: 'Call Guides',
        link:
          'https://docs.google.com/presentation/d/1HEhwl-N4R8zf1gNiNhV_xxOo5uCj2qlM0R8b7tG-2fk/edit?usp=sharing',
      },
      {
        title: 'File Manager',
        link: '/clients/file-manager',
      },
    ],
  }),

  props: {
    module: Object,
  },

  components: {
    UnitCard,
    AssignModule,
    ShareModule,
  },

  created() {
    if (this.$route.name === 'ModulePage') {
      this.fetchSingleModule();
      this.fetchUsers();
    }
  },

  mounted() {
    if (this.$route.name !== 'ModulePage') {
      this.loadVimeoPlayerSettings();
    }
  },

  updated() {
    if (this.$route.name == 'ModulePage') {
      this.loadVimeoPlayerSettings();
    }
  },

  computed: {
    moduleBuild() {
      return this.$route.name === 'ModulePage'
        ? this.$store.state.moduleBuild.selectedModuleBuild
        : this.module;
    },
    dueDate() {
      return dayjs(this.moduleBuild.module.my_assigned[0].due_date).format(
        'MMMM D, YYYY'
      );
    },
  },

  methods: {
    loadVimeoPlayerSettings() {
      if (
        !this.includes(
          this.imageFileTypes,
          this.moduleBuild.module.thumbnail.split('.').slice(-1).toString()
        )
      ) {
        console.log('vimeo load');
        let player = document.getElementById(
          'module-page-intro-video-build-' + this.moduleBuild.module.id
        );

        this.vPlayer = new Vimeo.Player(player);
        let play = false;
        let vm = this;
        console.log(player);
        this.played =
          this.moduleBuild.module.intro_video_played === 1 ? true : false;
        this.vPlayer.on('play', function () {
          vm.processPlayedVideo();
        });
      }
    },
    processPlayedVideo() {
      let id = this.moduleBuild.module.id;
      if (!this.played) {
        this.$http
          .post('/mark-module-user-video-played', {
            module_id: id,
          })
          .then((response) => {
            this.moduleBuild.module.intro_video_played = true;
            parent.focus();
            this.$notify('success', 'Added to your completed videos.');
            console.log('played');
          });
      } else {
        console.log('already played');
      }
    },
    markAsWatched(id) {
      this.$http
        .post('/mark-intro-video-watched', {
          module_id: id,
        })
        .then((response) => {
          this.moduleBuild.module.intro_video_watched = true;
          this.$notify('success', 'Successfully completed the video.');
        });
    },
    filteredUnits(series) {
      if (
        this.$route.name === 'ModulePage' ||
        this.$route.name === 'Category Page'
      ) {
        const unitSeries = _map(series, (series) => {
          return series.unit;
        });

        return _filter(unitSeries, (unit) => {
          return unit.name
            .toString()
            .toLowerCase()
            .includes(this.search.toLowerCase());
        });
      }

      return _filter(series, (unit) => {
        return unit.name
          .toString()
          .toLowerCase()
          .includes(this.search.toLowerCase());
      });
    },
    fetchSingleModule() {
      this.isFetching = true;
      this.$store
        .dispatch('moduleBuild/getModuleBuild', this.$route.params.id)
        .then(({ data }) => {
          this.$store.commit('moduleBuild/setSelectedModuleBuild', data);
        })
        .catch((error) => {
          this.$router.push({
            name: 'PageNotFound',
          });
        })
        .finally(() => {
          this.isFetching = false;
        });
    },
    fetchUsers() {
      this.$store
        .dispatch('lmsClient/fetchUsersByDealer')
        .then(({ data }) => {
          this.$store.dispatch('lmsClient/setUsers', data);
        })
        .catch(() => {
          console.log('error');
        });
    },
  },
};
</script>
