<template>
  <div>
    <number-management />
    <internet-post />
    <phoneshop-post />
    <group-shop />
  </div>
</template>
<script>
import NumberManagement from '../components/secretshop/NumberManagement.vue';
import InternetPost from '../components/secretshop/InternetPost.vue';
import PhoneshopPost from '../components/secretshop/PhoneshopPost.vue';
import GroupShop from '../components/secretshop/GroupShop.vue';

export default {
  name: 'SecretshopManagement',
  components: {
    NumberManagement,
    InternetPost,
    PhoneshopPost,
    GroupShop,
  },
  created() {
    this.getStates();
    this.$store.dispatch('dealers/getDealers');
  },
  methods: {
    getStates() {
      this.$http.get('/states').then(({ data }) => {
        this.$store.dispatch('states/setStates', data);
      });
    },
  },
};
</script>
