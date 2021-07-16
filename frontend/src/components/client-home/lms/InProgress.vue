<template>
  <div class="col-lg-4">
    <v-card class="mx-auto">
      <v-img
        class="white--text align-end"
        height="80px"
        src="https://i.imgur.com/Fj2aHPR.jpg"
        gradient="to top right, rgba(0,0,0,.33), rgba(25,32,72,.7)"
      >
        <v-card-title class="headline">
          <v-icon left dark> fal fa-spinner </v-icon>
          In Progress
        </v-card-title>
      </v-img>
      <div class="kt-portlet kt-portlet--height-fluid kt-widget19 mt-3">
        <section>
          <div
            class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill"
          >
            <div
              class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides module-image"
              v-if="!isEmpty(module)"
            >
              <v-img
                class="white--text"
                v-if="module.thumbnail.split('.').slice(-1).toString() == 'jpg'"
                :src="module.thumbnail"
                width="100%"
                height="100%"
              >
              </v-img>
              <div v-else class="embed-responsive embed-responsive-16by9">
                <iframe
                  class="embed-responsive-item"
                  :src="module.thumbnail"
                  allowfullscreen
                ></iframe>
              </div>
              <div class="kt-widget19__shadow"></div>
            </div>
          </div>
          <div class="kt-portlet__body" v-if="!isEmpty(module)">
            <v-progress-linear
              :value="module.progress"
              color="yellow darken-3"
              rounded
              height="25"
              striped
            >
              <strong>{{ Math.ceil(module.progress) }}%</strong>
            </v-progress-linear>
            <div class="kt-widget19__wrapper mt-5">
              <div class="kt-widget19__content">
                <div class="kt-widget19__info">
                  <a href="#" class="kt-widget19__username">
                    <h5>
                      {{ module.name }}
                    </h5>
                  </a>
                </div>
              </div>
            </div>
            <div class="kt-widget19__action">
              <v-btn
                :to="`/client/lms/module/${module.id}`"
                text
                color="primary"
                small
              >
                <v-icon class="mr-2" small>fal fa-arrow-right</v-icon>
                Continue
              </v-btn>
            </div>
          </div>
          <div v-if="isEmpty(module)">
            <p class="ml-3 my-3" style="color: #595d6e">
              You currently don't have any in progress module.
            </p>
          </div>
        </section>
      </div>
    </v-card>
  </div>
</template>
<script>
import { isEmpty as _isEmpty } from 'lodash-es';
export default {
  name: 'InProgress',
  data: () => ({
    loading: true,
    module: null,
    isEmpty: _isEmpty,
  }),

  created() {
    this.loadModules();
  },

  methods: {
    loadModules() {
      this.$store
        .dispatch('lmsClient/getInProgress')
        .then(({ data }) => {
          this.loading = false;
          this.module = data;
        })
        .catch(() => {
          this.loading = false;
          console.log(
            'Encountered an error while loading the playlists. Please try again'
          );
        });
    },
  },
};
</script>
<style lang="stylus" scoped>
.kt-widget19 .kt-widget19__wrapper .kt-widget19__content .kt-widget19__info
  padding-left 0px

.module-image
  width 95%
  margin auto
  background-image url('https://i.imgur.com/EGqO9i5.jpg')
</style>
