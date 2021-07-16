<template>
  <div class="kt-aside__brand kt-grid__item" id="kt_aside_brand">
    <div class="kt-aside__brand-logo">
      <router-link to="/">
        <img alt="Logo" id="main-logo" :src="logo" />
      </router-link>
    </div>
    <div class="kt-aside__brand-tools">
      <button
        class="kt-aside__brand-aside-toggler"
        id="kt_aside_toggler"
        :class="{
          'kt-aside__brand-aside-toggler--active': isAsideMenuMinimized,
        }"
        @click="toggleAsideMenu()"
      >
        <i
          class="far kt-svg-icon"
          id="aside-toggle"
          :class="{
            'fa-chevron-double-left': !isAsideMenuMinimized,
            'fa-chevron-double-right': isAsideMenuMinimized,
          }"
        >
        </i>
      </button>
    </div>
  </div>
</template>
<!-- end:: Aside -->
<script>
export default {
  name: 'AsideBrand',
  data() {
    return {
      isAsideMenuMinimized: false,
    };
  },
  computed: {
    logo() {
      if (this.$store.state.guest.asideBanner) {
        return this.$store.state.guest.asideBanner;
      }
      return 'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/default/main-logo.png';
    },
    logoBgColor() {
      return 'background-color:#222222';
    },
  },
  methods: {
    toggleAsideMenu() {
      const documentBody = document.body;

      this.isAsideMenuMinimized = !this.isAsideMenuMinimized;

      if (this.isAsideMenuMinimized) {
        documentBody.classList.add('kt-aside--minimize');
      } else {
        documentBody.classList.remove('kt-aside--minimize');
      }
    },
  },
};
</script>
