<template>
  <v-dialog v-model="dialog" width="500px">
    <template v-slot:activator="{ on, attrs }">
      <div
        class="kt-header__topbar-item kt-header__topbar-item--search dropdown"
        id="kt_quick_search_toggle"
        v-bind="attrs"
        v-on="on"
      >
        <div class="kt-header__topbar-wrapper">
          <span class="kt-header__topbar-icon">
            <i class="fal fa-search kt-svg-icon" id="search-icon"></i>
          </span>
        </div>
      </div>
    </template>
    <v-card>
      <v-card-text class="p-0">
        <div id="searchField" class="py-2 px-4">
          <v-text-field
            v-model="search"
            placeholder="Search here"
            solo
            flat
            hide-details
            prepend-icon="fa-search"
            ref="searchInput"
            @keyup="handleSearch"
            :disabled="searchLoader"
          />
        </div>
        <v-divider class="mt-0" v-show="showResults" />
        <v-skeleton-loader
          class="mx-auto"
          max-width="77%"
          type="paragraph, sentences"
          v-show="searchLoader"
        />
        <div id="searchResult" class="px-4" v-show="showResults">
          <template v-for="(obj, name) in results">
            <h5
              class="pl-2 text-uppercase"
              style="color: #c3c3c3"
              :key="`${name}_name`"
            >
              {{ name }} - {{ obj.length }} {{ resultsText(obj.length) }}
            </h5>
            <template v-for="(result, index) in obj">
              <router-link
                :to="resultLink(name, result)"
                :key="`${name}_${index}`"
                class="resultLink text--darken-2 py-2 px-3"
                :class="index + 1 === obj.length ? 'mb-5' : ''"
              >
                <span class="resultName">{{ result.name }}</span>
                <span class="float-right" style="color: #b9bab9">
                  <i class="fad fa-sign-out ml-2" />
                </span>
              </router-link>
              <v-divider
                v-if="showResultDivider(index, obj)"
                style="margin: 3px 0"
                :key="`${name}_${index}_divider`"
              />
            </template>
          </template>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import { debounce as _debounce, isEmpty as _isEmpty } from 'lodash-es';

export default {
  name: 'SearchDialog',
  data() {
    return {
      dialog: false,
      search: '',
      results: {},
      scrollbar: {
        maxScrollbarLength: 60,
      },
      searchLoader: false,
    };
  },
  computed: {
    showResults() {
      return !_isEmpty(this.results) && !this.searchLoader;
    },
  },
  methods: {
    getSearchResult() {
      this.toggleSearchLoader();
      this.$http
        .get(`/search?search_query=${this.search}`)
        .then((response) => {
          this.toggleSearchLoader();
          this.results = response.data.results;
        })
        .catch(() => {
          this.toggleSearchLoader();
        });
    },
    handleSearch: _debounce(function () {
      if (this.search === '') {
        return;
      }

      this.getSearchResult();
    }, 900),
    toggleSearchLoader() {
      this.searchLoader = !this.searchLoader;
    },
    resultLink(name, result) {
      const page = name.includes('module') ? 'module' : 'unit';
      return `/client/lms/${page}/${result.id}`;
    },
    showResultDivider(index, obj) {
      return Number(index) + 1 < obj.length;
    },
    resultsText(length) {
      return length <= 1 ? 'result' : 'results';
    },
  },
  watch: {
    dialog(show) {
      if (show) {
        setTimeout(() => {
          this.$refs.searchInput.focus();
        }, 20);
      }
    },
  },
};
</script>

<style lang="stylus" scoped>
#searchField .v-icon.v-icon
  font-size 25px!important

#searchField >>> input
  font-size 22px

.resultLink
  display block
  font-size 12px
  color #585858
  border-radius 5px

.resultName
  color #2b2b2b

.resultLink:hover
  background-color #e3e3e3
</style>
