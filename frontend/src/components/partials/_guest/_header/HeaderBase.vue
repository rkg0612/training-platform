<!-- begin:: Header -->
<template>
  <div
    id="kt_header"
    class="kt-header kt-grid__item kt-header--fixed"
    :style="topBarBgColor"
  >
    <header-menu
      :top-bar-font-color="topBarFontColor"
      :hover-active-color="topBarHoverColor"
    />

    <top-base />
  </div>
</template>
<!-- end:: Header -->
<script>
import HeaderMenu from './HeaderMenu.vue';
import TopBase from './topbar/TopBase.vue';

export default {
  name: 'HeaderBase',
  components: {
    HeaderMenu,
    TopBase,
  },
  computed: {
    topBarBgColor() {
      return 'background-color:#F9B418';
    },
    topBarFontColor() {
      return 'color:#F9B418 !important';
    },
    topBarHoverColor() {
      return 'background-color:#4d5995 !important';
    },
  },
};
</script>
