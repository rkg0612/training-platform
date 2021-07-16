<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div
      class="kt-portlet__head kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm"
    >
      <div class="kt-portlet__head-label row">
        <span class="kt-portlet__head-icon">
          <i class="fal fa-phone-square-alt"></i>
        </span>
        <h3 class="kt-portlet__head-title">Phone Shop Data</h3>
      </div>
    </div>
    <div class="kt-portlet__body">
      <component-loader :active="loading" />
      <!--Table content from CKEditor in phone shop form goes here-->
      <div class="mx-auto">
        <div v-html="phoneShop.inbound_call_grade"></div>
      </div>
      <!--Recording content from phone shop form goes here-->
      <v-card flat>
        <template v-if="!isEmpty(this.phoneShop.dealer_recordings)">
          <v-row justify="center">
            <v-col md="6">
              <h3>Dealer Recordings</h3>
            </v-col>
          </v-row>
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-center">Recording</th>
                  <th class="text-center">Label</th>
                </tr>
              </thead>
              <tbody>
                <template
                  v-for="(dealer, index) in phoneShop.dealer_recordings"
                >
                  <tr :key="index">
                    <td>
                      <audio
                        :src="dealer.playable_audio"
                        ref="audio"
                        controls
                      ></audio>
                    </td>
                    <td>
                      {{ dealer.label }}
                    </td>
                  </tr>
                </template>
              </tbody>
            </template>
          </v-simple-table>
        </template>
        <template v-if="!isEmpty(this.phoneShop.competitor_recordings)">
          <v-row justify="center">
            <v-col md="6">
              <h3>Competitor Recordings</h3>
            </v-col>
          </v-row>
          <v-simple-table>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-center">Recording</th>
                  <th class="text-center">Label</th>
                </tr>
              </thead>
              <tbody>
                <template
                  v-for="(competitor, index) in phoneShop.competitor_recordings"
                >
                  <tr :key="index">
                    <td>
                      <audio
                        :src="competitor.playable_audio"
                        ref="audio"
                        controls
                      ></audio>
                    </td>
                    <td>
                      {{ competitor.label }}
                    </td>
                  </tr>
                </template>
              </tbody>
            </template>
          </v-simple-table>
        </template>
      </v-card>
    </div>
  </div>
</template>

<script>
import { isEmpty as _isEmpty } from 'lodash-es';

export default {
  name: 'MainContent',
  data() {
    return {
      isEmpty: _isEmpty,
    };
  },
  props: {
    loading: {
      required: true,
      default: false,
    },
    shopContent: {
      required: false,
      type: Object,
    },
  },
  computed: {
    phoneShop() {
      if (
        this.$route.name === 'GroupShop' ||
        this.$route.name === 'GroupShopGuestView'
      ) {
        return this.shopContent;
      }
      return this.$store.state.phoneShops.selectedItem;
    },
  },
  methods: {},
};
</script>

<style lang="stylus" scoped>
audio
  height 40px !important
  margin-top 5px
</style>
