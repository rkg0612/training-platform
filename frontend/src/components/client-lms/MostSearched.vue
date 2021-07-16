<template>
  <div class="kt-portlet kt-portlet--head-lg k-portlet--height-fluid">
    <div class="kt-portlet__body text-center">
      <h3 class="playlist-heading">
        <i class="fal fa-tasks m-1"></i>
        Most Searched Units
      </h3>
      <p class="text-center">List of top 10 units.</p>
      <v-divider></v-divider>
      <div class="custome-break-point d-flex flex-wrap">
        <div class="col-lg-4 col-md-6" v-for="unit in items" :key="unit.id">
          <unit-card :unit="unit"></unit-card>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import UnitCard from './UnitCard.vue';

export default {
  name: 'MostSearched',
  components: {
    UnitCard,
  },
  data: () => ({
    items: [],
  }),

  created() {
    this.getMostSearched();
  },

  methods: {
    getMostSearched() {
      this.$http
        .get('search-analytics')
        .then((response) => {
          this.items = response.data;
        })
        .catch((err) => {});
    },
  },
};
</script>
