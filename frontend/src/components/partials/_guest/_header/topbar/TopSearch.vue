<!--begin: Search -->
<template>
  <div
    class="kt-header__topbar-item kt-header__topbar-item--search dropdown"
    id="kt_quick_search_toggle"
    :class="{ show: isSearchDropdownExpanded }"
    v-click-outside="hideDropDown"
  >
    <div
      class="kt-header__topbar-wrapper"
      @click="isSearchDropdownExpanded = !isSearchDropdownExpanded"
    >
      <span class="kt-header__topbar-icon">
        <i class="fal fa-search kt-svg-icon" id="search-icon"></i>
      </span>
    </div>
    <div
      class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-lg"
      id="dropSearch"
      :class="{ show: isSearchDropdownExpanded }"
    >
      <!--Dropdown Search-->
      <drop-search />
    </div>
  </div>
</template>
<!--end: Search -->
<script>
import DropSearch from '../../_topbar/dropdown/DropSearch.vue';

export default {
  name: 'TopSearch',
  components: {
    DropSearch,
  },
  data() {
    return {
      isSearchDropdownExpanded: false,
    };
  },
  methods: {
    hideDropDown() {
      this.isSearchDropdownExpanded = false;
    },
  },
};
</script>
<style lang="stylus">
#dropSearch
  left -175px
</style>
