<template>
  <li class="kt-menu__item">
    <v-hover>
      <a @click="openLink" class="kt-menu__link" href="javascript:">
        <span class="kt-menu__link-icon"></span>
        <span class="kt-menu__link-text">{{ course.title }}</span>
      </a>
    </v-hover>
  </li>
</template>

<script>
export default {
  name: 'AsideScormCourses',
  props: ['course'],
  methods: {
    openLink() {
      const token = this.$auth.token();
      window.open(
        `/api/scorm/course/${this.course.id}?token=${token}`,
        '_blank'
      );
    },
  },
};
</script>

<style scoped></style>
