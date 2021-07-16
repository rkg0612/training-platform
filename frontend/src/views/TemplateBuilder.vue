<template>
  <div class="row">
    <!-- <div class="col-lg-12">
      <sidebar-builder />
    </div> -->
    <div class="col-lg-12">
      <course-organizer />
    </div>
    <div class="col-lg-12">
      <category-builder />
    </div>
    <div class="col-lg-12">
      <module-builder />
    </div>
  </div>
</template>
<script>
import SidebarBuilder from '../components/lms-admin/template-builder/SidebarBuilder.vue';
import CourseOrganizer from '../components/lms-admin/template-builder/CourseOrganizer.vue';
import CategoryBuilder from '../components/lms-admin/template-builder/CategoryBuilder.vue';
import ModuleBuilder from '../components/lms-admin/template-builder/ModuleBuilder.vue';

export default {
  name: 'TemplateBuilder',

  components: {
    SidebarBuilder,
    CourseOrganizer,
    CategoryBuilder,
    ModuleBuilder,
  },

  created() {
    this.fetchCourses();
  },

  methods: {
    fetchCourses() {
      // this.moduleBuildList =
      const filter = this.record_filter;
      const payload = this.payload;
      this.$store
        .dispatch('moduleBuilds/fetchCourses')
        .then(({ data }) => {
          console.log(data);
          this.$store.commit('moduleBuilds/setCourses', data);
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
