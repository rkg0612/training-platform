<template>
  <div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__body">
      <v-card>
        <v-tabs
          next-icon="fad fa-arrow-square-right"
          prev-icon="fad fa-arrow-square-left"
          show-arrows
        >
          <template v-for="(internetShop, index) in internetShops">
            <v-tab>{{ dealerName(internetShop, index) }}</v-tab>
            <v-tab-item>
              <div class="row">
                <div class="col-lg-8">
                  <shop-logs :internet-shop="internetShop"></shop-logs>
                </div>
                <div class="col-lg-4">
                  <side-widget :shop="internetShop"></side-widget>
                </div>
              </div>
            </v-tab-item>
          </template>
        </v-tabs>
      </v-card>
    </div>
  </div>
</template>
<script>
import ShopLogs from '../ShopPost/ShopLogs.vue';
import SideWidget from '../ShopPost/SideWidget.vue';

export default {
  name: 'DealerLogs',

  components: {
    ShopLogs,
    SideWidget,
  },

  props: {
    internetShops: {
      required: true,
      type: Array,
    },
    hideDealerName: {
      default: false,
    },
  },

  methods: {
    dealerName(shop, index) {
      if (!shop.is_dealer) {
        return this.hideDealerName
          ? `Competitor ${index + 1}`
          : shop.competitor.name;
      }
      return this.hideDealerName
        ? `Dealer ${index + 1}`
        : shop.specific_dealer.name;
    },
  },
};
</script>
