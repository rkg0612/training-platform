<!-- begin:: Header Topbar -->
<template>
  <div class="kt-header__topbar">
    <top-search />
    <top-notification />
    <top-user />
  </div>
</template>
<!-- end:: Header Topbar -->
<script>
import TopSearch from './TopSearch.vue';
import TopUser from './TopUser.vue';
import TopNotification from './TopNotification.vue';

export default {
  name: 'TopBase',
  components: {
    TopSearch,
    TopUser,
    TopNotification,
  },
};
</script>
