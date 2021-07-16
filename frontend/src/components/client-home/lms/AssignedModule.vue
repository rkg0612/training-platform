<template>
  <div class="col-lg-4">
    <v-card class="mx-auto">
      <v-img
        class="white--text align-end"
        height="80px"
        src="https://i.imgur.com/lBauPit.jpg"
        gradient="to top right, rgba(0,0,0,.33), rgba(25,32,72,.7)"
      >
        <v-card-title class="headline">
          <v-icon left dark> fal fa-pen-nib </v-icon>
          Assigned to You
        </v-card-title>
      </v-img>
      <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
        <section>
          <div class="kt-portlet__body" v-if="!isEmpty(assignedToYou)">
            <div class="tab-content">
              <div class="tab-pane active" id="kt_widget4_tab1_content">
                <div class="kt-widget4">
                  <div
                    class="kt-widget4__item"
                    v-for="(assigned, index) in assignedToYou"
                    :key="index"
                  >
                    <div class="kt-widget4__info">
                      <a href="#" class="kt-widget4__username">
                        {{ assigned.name }}
                      </a>
                      <p class="kt-widget4__text">
                        Assigned By: {{ assigned.assigned_by }}
                      </p>
                    </div>
                    <v-btn :to="assigned.url" text color="primary" small>
                      <v-icon class="mr-2" small>fal fa-eye</v-icon>
                      View
                    </v-btn>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-else>
            <p class="ml-3 my-3" style="color: #595d6e">
              You currently don't have any assigned modules.
            </p>
          </div>
        </section>
      </div>
    </v-card>
  </div>
</template>
<script>
import { isEmpty as _isEmpty } from 'lodash-es';
import dayjs from 'dayjs';
export default {
  name: 'AssignedModule',
  data: () => ({
    loading: true,
    assignedToYou: [],
    isEmpty: _isEmpty,
  }),

  created() {
    this.loadModules();
  },

  methods: {
    loadModules() {
      this.$store
        .dispatch('lmsClient/getLatestAssigned')
        .then(({ data }) => {
          this.loading = false;
          this.assignedToYou = data;
        })
        .catch(() => {
          this.loading = false;
          console.log(
            'Encountered an error while loading the Modules. Please try again'
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
  background-image url('https://i.imgur.com/EGqO9i5.jpg') //default image
  //background image should be replaced by the module banner set in module creation
</style>
