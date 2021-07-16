<template>
  <div
    class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid"
    id="kt_aside_menu_wrapper"
  >
    <div id="kt_aside_menu" class="kt-aside-menu kt-scroll">
      <ul class="kt-menu__nav" id="aside-menu">
        <li class="kt-menu__item">
          <v-hover>
            <router-link to="/client/profile" class="kt-menu__link">
              <span class="kt-menu__link-icon">
                <i class="kt-svg-icon fal fa-user-circle"></i>
              </span>
              <span class="kt-menu__link-text">My Profile</span>
            </router-link>
          </v-hover>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AsideMenu',
  methods: {},
};
</script>

<style lang="stylus" scoped></style>
