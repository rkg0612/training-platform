<template>
  <guest-layout>
    <div>
      <div v-if="loading.fetching">
        <component-loader :active="loading.fetching" />
      </div>
      <div v-else>
        <h3 class="shopHeader">
          <i class="fal fa-chart-bar"></i>
          Internet Shop
        </h3>
        <div class="row">
          <div class="col-lg-12">
            <response-time :internet-shops="groupShop.internet_shops" />
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <attempts-chart :internet-shops="groupShop.internet_shops" />
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <dealer-logs :internet-shops="groupShop.internet_shops" />
          </div>
        </div>
        <hr />
        <template v-if="!isEmpty(groupShop.phone_shops)">
          <h3 class="shopHeader mt-5">
            <i class="fal fa-phone-office"></i>
            Phone Shop
          </h3>
          <template v-for="phoneShop in groupShop.phone_shops">
            <div class="row">
              <div class="col-lg-12">
                <phone-shop-content :shop-content="phoneShop" />
              </div>
            </div>
          </template>
        </template>
        <template v-else> No Phone Shop </template>
      </div>
    </div>
  </guest-layout>
</template>

<script>
import GuestLayout from '../../../layouts/Guest';
import ResponseTime from '../../../components/client-shops/group-shop/ResponseTime.vue';
import AttemptsChart from '../../../components/client-shops/group-shop/AttemptsChart.vue';
import DealerLogs from '../../../components/client-shops/group-shop/DealerLogs.vue';
import PhoneShopContent from '../../../components/client-shops/group-shop/PhoneShopContent.vue';
import { isEmpty as _isEmpty } from 'lodash-es';

export default {
  name: 'GuestGroupShop',
  components: {
    GuestLayout,
    ResponseTime,
    AttemptsChart,
    DealerLogs,
    PhoneShopContent,
  },
  created() {
    this.getGroupShop();
  },
  data() {
    return {
      isEmpty: _isEmpty,
      groupShop: {
        internet_shops: [],
        phone_shops: [],
      },
      loading: {
        fetching: false,
      },
    };
  },
  methods: {
    getGroupShop() {
      this.loading.fetching = true;
      this.axios
        .get(`guest/group-shop/${this.$route.params.id}`)
        .then(({ data }) => {
          this.groupShop = data.groupShop;
          this.$store.commit('guest/setAsideBanner', data.dealer.value);
          setTimeout(() => {
            this.loading.fetching = false;
          }, 2000);
        });
    },
  },
};
</script>

<style scoped></style>
