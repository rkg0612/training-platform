<template>
  <div>
    <secret-shop />
    <dealer-comparison v-if="!isSpecificDealerManager" />
    <internet-shop-table></internet-shop-table>
    <phone-shop-table></phone-shop-table>
    <group-shop class="mt-5" />
  </div>
</template>
<script>
import SecretShop from '../components/client-home/SecretShop.vue';
import DealerComparison from '../components/client-shops/DealerComparison.vue';
import ShopsTable from '../components/client-shops/ShopsTable.vue';
import GroupShop from '../components/secretshop/GroupShop.vue';
import { some as _some } from 'lodash-es';
import InternetShopTable from '../components/client-shops/InternetShopTable';
import PhoneShopTable from '../components/client-shops/PhoneShopTable';

export default {
  name: 'ClientShops',

  components: {
    PhoneShopTable,
    InternetShopTable,
    SecretShop,
    DealerComparison,
    ShopsTable,
    GroupShop,
  },

  computed: {
    isSpecificDealerManager() {
      return _some(this.$auth.user().roles, {
        friendly_name: 'Specific Dealer Manager',
      });
    },
  },

  created() {},
};
</script>
