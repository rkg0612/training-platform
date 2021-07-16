<template>
  <div>
    <div v-show="loading.fetching">
      <vue-content-loader :speed="2" primary="#f9b418" secondary="#ecebeb">
        <circle cx="150" cy="86" r="8" />
        <circle cx="194" cy="86" r="8" />
        <circle cx="238" cy="86" r="8" />
      </vue-content-loader>
    </div>
    <guest-layout v-show="!loading.fetching">
      <div class="row">
        <div class="col-lg-8">
          <main-content :loading="loading.fetching" />
        </div>
        <div class="col-lg-4">
          <side-widget :loading="loading.fetching" />
        </div>
      </div>
    </guest-layout>
  </div>
</template>

<script>
import MainContent from '../../components/client-shops/phone-shop/MainContent.vue';
import SideWidget from '../../components/client-shops/phone-shop/SideWidget.vue';
import GuestLayout from '../../layouts/Guest';
import ComponentLoader from '../../components/partials/ComponentLoader';
import VueContentLoader from 'vue-content-loading';

export default {
  name: 'Phoneshop',
  components: {
    GuestLayout,
    MainContent,
    SideWidget,
    VueContentLoader,
  },
  data() {
    return {
      loading: {
        fetching: false,
      },
    };
  },
  created() {
    this.loading.fetching = true;
    this.axios
      .get(`guest/phone-shop/${this.$route.params.id}`)
      .then(({ data }) => {
        this.$store.commit('phoneShops/setSelectedItem', data.phoneShop);
        this.$store.commit('guest/setAsideBanner', data.dealer.value);
        setTimeout(() => {
          this.loading.fetching = false;
        }, 2000);
      });
  },
};
</script>

<style lang="stylus" scoped></style>
