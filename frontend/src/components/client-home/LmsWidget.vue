<template>
  <div class="row">
    <!-- Start of In Progress Section -->
    <in-progress></in-progress>
    <!-- End of In Progress Section -->
    <!-- Start of modules assigned -->
    <assigned-module></assigned-module>
    <!-- End of In modules assigned -->
    <!-- Start of playlist -->
    <play-list></play-list>
    <!-- End of playlist -->
  </div>
</template>
<script>
import InProgress from './lms/InProgress.vue';
import PlayList from './lms/PlayList.vue';
import AssignedModule from './lms/AssignedModule.vue';

export default {
  name: 'LmsWidget',
  components: {
    InProgress,
    PlayList,
    AssignedModule,
  },
};
</script>
