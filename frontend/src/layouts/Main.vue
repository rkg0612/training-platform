<template>
  <v-app>
    <header-mobile />
    <div class="kt-grid kt-grid--hor kt-grid--root">
      <div
        class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page"
      >
        <aside-base />
        <div
          class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper"
          id="kt_wrapper"
        >
          <header-base />
          <!--Start of banner for client's dashboard and lms-->
          <div class="client-banner">
            <top-banner class="d-none d-sm-block"></top-banner>
            <lms-intro></lms-intro>
          </div>
          <!--End of banner for client only-->

          <div
            class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor"
            id="kt_content"
          >
            <course-menu />
            <div
              class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid"
            >
              <router-view></router-view>
            </div>
          </div>
          <footer-base />
        </div>
      </div>
    </div>
    <no-access-dialog />
    <no-access-permission-dialog />
  </v-app>
</template>

<script>
import NoAccessDialog from '@/components/partials/NoAccessDialog.vue';
import NoAccessPermissionDialog from '@/components/partials/NoAccessPermissionDialog.vue';
import AsideBase from '../components/partials/_aside/AsideBase.vue';
import HeaderBase from '../components/partials/_header/HeaderBase.vue';
import FooterBase from '../components/partials/_footer/FooterBase.vue';
import HeaderMobile from '../components/partials/_header/HeaderMobile.vue';
import TopBanner from '../components/client-home/TopBanner.vue';
import LmsIntro from '../components/client-lms/LmsIntro.vue';
import CourseMenu from '@/components/partials/CourseMenu';

export default {
  name: 'Main',
  components: {
    CourseMenu,
    NoAccessPermissionDialog,
    NoAccessDialog,
    AsideBase,
    HeaderBase,
    FooterBase,
    HeaderMobile,
    TopBanner,
    LmsIntro,
  },
  computed: {
    user() {
      return this.$auth.user();
    },
  },
};
</script>

<style lang="stylus" scoped>
.client-banner
  margin-top -5px
</style>
