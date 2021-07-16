<template>
  <div class="row">
    <div class="col-lg-8">
      <main-content :loading="loading.fetching" />
    </div>
    <div class="col-lg-4">
      <side-widget :loading="loading.fetching" />
    </div>
  </div>
</template>
<script>
import MainContent from '../components/client-shops/phone-shop/MainContent.vue';
import SideWidget from '../components/client-shops/phone-shop/SideWidget.vue';

export default {
  name: 'PhoneShop',

  components: {
    MainContent,
    SideWidget,
  },

  data() {
    return {
      loading: {
        fetching: false,
      },
    };
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      this.loading.fetching = true;
      this.$store
        .dispatch('phoneShops/getPhoneShop', this.$route.params.id)
        .then(({ data }) => {
          this.$store.commit('phoneShops/setSelectedItem', data);
        })
        .finally(() => {
          setTimeout(() => {
            this.loading.fetching = false;
          }, 500);
        });
    },
  },
};
</script>
