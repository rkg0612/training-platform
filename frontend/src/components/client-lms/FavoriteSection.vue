<template>
  <div
    class="kt-portlet kt-portlet--head-lg k-portlet--height-fluid kt-portlet--solid-dark"
  >
    <div class="kt-portlet__body text-center">
      <h3 class="playlist-heading mt-3">
        <i class="fal fa-heart m-1"></i>
        Your Favorites
      </h3>
      <p class="text-center">Your all-time favorite units.</p>
      <h5 class="text-center" v-if="units.length < 1">
        You currently don't have any favorites.
      </h5>
      <div class="row" v-else>
        <div
          class="col-llg-4 col-md-6"
          v-for="(unit, index) in units"
          :key="index"
        >
          <unit-card :unit="unit" type="favorite" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import UnitCard from './UnitCard.vue';

export default {
  name: 'Favorites',
  components: {
    UnitCard,
  },
  data: () => ({
    loading: true,
  }),
  computed: {
    units() {
      return this.$store.state.lmsClient.units.favorites;
    },
  },
  methods: {
    loadUnits() {
      this.$http
        .get('/unit/favorite')
        .then(({ data }) => {
          this.loading = false;
          this.$store.dispatch('lmsClient/setFavorites', data);
        })
        .catch(() => {
          this.loading = false;
        });
    },
  },
  created() {
    this.loadUnits();
  },
};
</script>
<style lang="stylus" scoped>
.kt-portlet.kt-portlet--solid-dark
  background #222
</style>
