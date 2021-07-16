<template>
  <v-app>
    <!-- <header-mobile /> -->
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
          <!--  <div class="client-banner">
            <top-banner class="d-none d-sm-block"></top-banner>
            <lms-intro></lms-intro>
          </div> -->
          <!--End of banner for client only-->

          <div
            class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor"
            id="kt_content"
          >
            <div
              class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid"
            >
              <!-- // router-view to slot -->
              <slot></slot>
            </div>
          </div>
          <!-- <footer-base /> -->
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import AsideBase from '../components/partials/_guest/_aside/AsideBase.vue';
import HeaderBase from '../components/partials/_guest/_header/HeaderBase.vue';
export default {
  name: 'Guest',
  components: {
    AsideBase,
    HeaderBase,
  },
};
</script>

<style lang="stylus" scoped>
.client-banner
  margin-top -5px
</style>
