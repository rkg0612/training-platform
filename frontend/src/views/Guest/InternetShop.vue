<template>
  <div v-if="isGetInternetShop">
    <guest-layout>
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
          <shop-logs :internetShop="internetShop" />
        </div>
        <div class="col-lg-4">
          <side-widget :shop="internetShop" />
        </div>
      </div>
    </guest-layout>
  </div>
</template>
<script>
import TopWidget from '../../components/client-shops/ShopPost/TopWidget.vue';
import TrueCar from '../../components/client-shops/ShopPost/TrueCar.vue';
import ShopLogs from '../../components/client-shops/ShopPost/ShopLogs.vue';
import SideWidget from '../../components/client-shops/ShopPost/SideWidget.vue';
import GuestLayout from '../../layouts/Guest';
export default {
  wname: 'SingleShop',

  components: {
    TopWidget,
    ShopLogs,
    SideWidget,
    TrueCar,
    GuestLayout,
  },

  data() {
    return {
      loading: {
        fetching: false,
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
      this.$http
        .get(`guest/internet-shop/${this.$route.params.id}`)
        .then(({ data }) => {
          //   this.$store.commit('internetShops/setSelectedItem', data);
          this.internetShop = data;
        })
        .finally(() => {
          this.$nextTick(() => {
            this.loading.fetching = false;
            this.isGetInternetShop = true;
          });
        });
    },
  },
};
</script>
