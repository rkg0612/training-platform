<template>
  <div>
    <div class="banner-section" v-show="showBanner">
      <!--Put the dealer banner here-->
      <img :src="dealerBanner" alt="dealer_banner" class="dealer-banner" />
    </div>
  </div>
</template>
<script>
export default {
  name: 'TopWidget',
  computed: {
    showBanner() {
      if (this.$route.name !== 'Home') {
        return false;
      }
      return true;
    },
    dealerBanner() {
      const banner = this.$getSetting('background_image');
      if (banner) return banner;
      return 'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/default/home-banner.jpg';
    },
  },
};
</script>
<style lang="stylus" scoped>
.dealer-banner
  width 100%
</style>
