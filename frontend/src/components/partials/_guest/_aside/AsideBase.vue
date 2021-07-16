<!-- begin:: Aside -->
<template>
  <!-- <button class="kt-aside-close" id="kt_aside_close_btn">
    <i class="fal fa-times"></i>
  </button> -->
  <div
    class="kt-aside kt-aside--fixed kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop"
    id="kt_aside"
  >
    <aside-brand />
    <aside-menu />
  </div>
</template>
<!-- end:: Aside -->
<script>
import AsideBrand from './AsideBrand.vue';
import AsideMenu from './AsideMenu.vue';

export default {
  name: 'AsideBase',
  components: {
    AsideBrand,
    AsideMenu,
  },
};
</script>
