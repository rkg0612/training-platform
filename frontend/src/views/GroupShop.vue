<template>
  <div>
    <div v-if="loading">
      <component-loader :active="loading" />
    </div>
    <div v-else>
      <h3 class="shopHeader">
        <i class="fal fa-chart-bar"></i>
        Internet Shop
      </h3>
      <div class="row">
        <div class="col-lg-12">
          <response-time
            :internet-shops="groupShop.internet_shops"
            :hide-dealer-name="groupShop.hide_dealer_name"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <attempts-chart
            :internet-shops="groupShop.internet_shops"
            :hide-dealer-name="groupShop.hide_dealer_name"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <dealer-logs
            :internet-shops="groupShop.internet_shops"
            :hide-dealer-name="groupShop.hide_dealer_name"
          />
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
</template>
<script>
import ResponseTime from '../components/client-shops/group-shop/ResponseTime.vue';
import AttemptsChart from '../components/client-shops/group-shop/AttemptsChart.vue';
import DealerLogs from '../components/client-shops/group-shop/DealerLogs.vue';
import PhoneShopContent from '../components/client-shops/group-shop/PhoneShopContent.vue';
import { isEmpty as _isEmpty } from 'lodash-es';

export default {
  name: 'SingleShop',

  components: {
    ResponseTime,
    AttemptsChart,
    DealerLogs,
    PhoneShopContent,
  },

  data() {
    return {
      isEmpty: _isEmpty,
      groupShop: {},
      loading: true,
    };
  },

  created() {
    this.fetchGroupShopContent();
  },

  methods: {
    fetchGroupShopContent() {
      this.loading = true;
      const { id } = this.$route.params;
      this.axios
        .get(`secret-shop-management/group-shops/${id}`)
        .then(({ data }) => {
          this.groupShop = data;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
<style lang="stylus" scoped>
.shopHeader
  color #646c9A
  font-family Oswald
</style>
