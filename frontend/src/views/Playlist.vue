<template>
  <div
    class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid px-0 px-lg-8 row"
  >
    <div class="col-xl-3 col-lg-4 col-md-12">
      <info />
    </div>
    <div class="col-xl-9 col-lg-8 col-md-12 pt-0">
      <body-content />
    </div>
  </div>
</template>

<script>
import Info from '../components/playlist/Info.vue';
import Body from '../components/playlist/Body.vue';

export default {
  name: 'Playlist',
  components: {
    Info,
    'body-content': Body,
  },
};
</script>

<style scoped></style>
