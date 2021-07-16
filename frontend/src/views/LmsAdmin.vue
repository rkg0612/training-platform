<template>
  <div>
    <course-section />
    <category-section />
    <module-section />
    <unit-section />
    <quiz-section />
  </div>
</template>
<script>
import CourseSection from '../components/lms-admin/CourseSection.vue';
import CategorySection from '../components/lms-admin/CategorySection.vue';
import ModuleSection from '../components/lms-admin/ModuleSection.vue';
import UnitSection from '../components/lms-admin/UnitSection.vue';
import QuizSection from '../components/lms-admin/QuizSection';

export default {
  name: 'LmsAdmin',
  components: {
    QuizSection,
    CourseSection,
    CategorySection,
    ModuleSection,
    UnitSection,
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    fetchUsers() {
      this.$store
        .dispatch('salespersonsAndManagers/getSalespersonsAndManagers', {
          roles: ['manager', 'salesperson'],
        })
        .then(({ data }) => {
          this.$store.commit(
            'salespersonsAndManagers/setSalespersonsAndManagers',
            data
          );
        });
    },
  },
};
</script>
