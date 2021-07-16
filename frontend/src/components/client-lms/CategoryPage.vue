<template>
  <v-card>
    <component-loader :active="isFetching"></component-loader>
    <v-app-bar
      flat
      class="pt-2"
      height="unset"
      color="grey lighten-2"
      style="font-family: oswald"
    >
      <div class="w-100">
        <h1 class="font-weight-bold">
          Category: <span>{{ categoryModules.name }}</span>
        </h1>
      </div>
    </v-app-bar>
    <div class="px-5">
      <div v-for="module in categoryModules.modules" v-bind:key="module.id">
        <template v-if="!isEmpty(module.build)">
          <module-page :module="module.build"></module-page>
        </template>
        <template v-else> No Module Build! </template>
      </div>
    </div>
  </v-card>
</template>
<script>
import ModulePage from './ModulePage.vue';
import { isEmpty as _isEmpty } from 'lodash-es';

export default {
  name: 'CategoryPage',

  data: () => ({
    categoryprog: 78,
    isFetching: false,
    isEmpty: _isEmpty,
  }),

  components: {
    ModulePage,
  },

  mounted() {
    this.fetchCategory();
  },

  computed: {
    categoryModules() {
      return this.$store.state.clientCategories.selectedCategory;
    },
  },

  methods: {
    fetchCategory() {
      this.isFetching = true;
      this.$store
        .dispatch('clientCategories/showCategory', {
          id: this.$route.params.id,
        })
        .then(({ data }) => {
          this.$store.commit('clientCategories/setCategory', data);
          this.isFetching = false;
        });
    },
  },
};
</script>
