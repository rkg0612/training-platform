<template>
  <div v-if="isGetInternetShop">
    <div class="row">
      <div class="col-lg-12">
        <top-widget :internetShop="internetShop" />
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <true-car
          v-if="internetShop.dealer_id === 48 && internetShop.truecar_fields"
          :internetShop="internetShop"
        />
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8">
        <shop-logs
          :internetShop="internetShop"
          :toggleTranscript="toggleTranscript"
        />
      </div>
      <div class="col-lg-4">
        <side-widget :shop="internetShop" />
      </div>
    </div>
  </div>
</template>
<script>
import { map as _map } from 'lodash-es';

import TopWidget from '../components/client-shops/ShopPost/TopWidget.vue';
import TrueCar from '../components/client-shops/ShopPost/TrueCar.vue';
import ShopLogs from '../components/client-shops/ShopPost/ShopLogs.vue';
import SideWidget from '../components/client-shops/ShopPost/SideWidget.vue';

export default {
  name: 'SingleShop',

  components: {
    TopWidget,
    ShopLogs,
    SideWidget,
    TrueCar,
  },

  data() {
    return {
      loading: {
        fetch: false,
      },
      internetShop: {},
      isGetInternetShop: false,
    };
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      this.loading.fetching = true;
      this.$store
        .dispatch('internetShops/getInternetShop', this.$route.params.id)
        .then(({ data }) => {
          const obj = data;
          _map(obj.phone_number.calls, (item) => {
            if (item.transcript !== null) {
              item.transcript_open = false;
            }
          });

          this.internetShop = obj;
        })
        .finally(() => {
          this.$nextTick(() => {
            this.loading.fetching = false;
            this.isGetInternetShop = true;
          });
        });
    },
    toggleTranscript(index) {
      const callData = this.internetShop.phone_number.calls[index];
      this.internetShop.phone_number.calls[
        index
      ].transcript_open = !callData.transcript_open;
    },
  },
};
</script>
