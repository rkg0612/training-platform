<template>
  <v-card>
    <v-card-title>Search results for: {{ query }}</v-card-title>
    <v-card-text>
      <h2>Units related to your search "{{ query }}"</h2>
      <v-progress-circular
        indeterminate
        color="primary"
        v-show="results.length === 0"
      ></v-progress-circular>
      <ul>
        <li v-for="result in results.units" v-bind:key="result.id">
          {{ result.name }} -
          <router-link
            :to="{
              name: 'UnitPage',
              params: { id: result.id },
              query: { track: 1 },
            }"
            >Go</router-link
          >
          >
        </li>
      </ul>
      <h2>Modules related to your search "{{ query }}"</h2>
      <v-progress-circular
        indeterminate
        color="primary"
        v-show="results.length === 0"
      ></v-progress-circular>
      <ul>
        <li v-for="result in results.modules" v-bind:key="result.id">
          {{ result.name }} -
          <router-link :to="{ name: 'ModulePage', params: { id: result.id } }">
            Go
          </router-link>
        </li>
      </ul>
      <h2>Units with tags related to your search "{{ query }}"</h2>
      <v-progress-circular
        indeterminate
        color="primary"
        v-show="results.length === 0"
      ></v-progress-circular>
      <ul>
        <li v-for="result in results.hashtag_units" v-bind:key="result.id">
          {{ result.name }} -
          <router-link
            :to="{
              name: 'UnitPage',
              params: { id: result.id },
              query: { track: 1 },
            }"
            >Go</router-link
          >
          >
        </li>
      </ul>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  name: 'SearchResult',
  data: () => ({
    query: null,
    results: [],
  }),

  created() {
    if (!this.$route.query.s) {
      this.$router.push({ path: '/' });
    }
    this.query = this.$route.query.s;
    this.getSearchResult();
  },

  methods: {
    getSearchResult() {
      this.$http.get(`/search?search_query=${this.query}`).then((response) => {
        this.results = response.data.results;
      });
    },
  },
};
</script>
