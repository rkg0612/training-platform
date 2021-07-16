<template>
  <v-card>
    <component-loader :active="isFetching"></component-loader>
    <module-page
      v-for="(module, index) in courseBuild.modules"
      :key="index"
      :module="module"
    ></module-page>
  </v-card>
</template>
<script>
import ModulePage from './ModulePage.vue';

export default {
  name: 'MainCourseLibrary',

  props: {
    courseBuild: Object,
    required: true,
  },

  data: () => ({
    isFetching: false,
  }),

  components: {
    ModulePage,
  },
};
</script>
