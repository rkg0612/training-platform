<template>
  <div class="row">
    <div class="col-lg-8">
      <main-content :loading="false" :shop-content="shopContent" />
    </div>
    <div class="col-lg-4">
      <side-widget :loading="false" :shop-content="shopContent" />
    </div>
  </div>
</template>
<script>
import MainContent from '../phone-shop/MainContent.vue';
import SideWidget from '../phone-shop/SideWidget.vue';

export default {
  name: 'PhoneShopContent',

  components: {
    MainContent,
    SideWidget,
  },

  props: {
    shopContent: {
      required: false,
      type: Object,
    },
  },
};
</script>
