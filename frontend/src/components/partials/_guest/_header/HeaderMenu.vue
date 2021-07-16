<template>
  <!-- Uncomment this to display the close button of the panel
<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn">
  <i class="la la-close"></i>
</button>
-->
  <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
    <div
      id="kt_header_menu"
      class="kt-header-menu kt-header-menu-mobile kt-header-menu--layout-default"
    >
      <ul class="kt-menu__nav">
        <!--TODO: Need to add kt-menu__item--active class in li tag if link is active -->
        <li
          class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel"
          v-for="(route, index) in routes.slice(6)"
          :class="activeRoute(route.path) ? 'kt-menu__item--active' : ''"
          :key="index"
        >
          <v-hover>
            <router-link
              :to="route.path"
              class="kt-menu__link kt-menu__toggle"
              :target="isNewTab(route.name)"
              slot-scope="{ hover }"
              :style="`${hover ? hoverActiveColor : ''} ${
                activeRoute(route.path)
                  ? hoverActiveColor + topBarFontColor
                  : ''
              }`"
            >
              <span class="kt-menu__link-icon kt-svg-icon">
                <i
                  class="header-icon"
                  :class="route.icon"
                  :style="`${hover ? topBarFontColor : ''} ${
                    activeRoute(route.path) ? topBarFontColor : ''
                  }`"
                ></i>
              </span>
              <span
                class="kt-menu__link-text"
                :style="`${hover ? topBarFontColor : ''} ${
                  activeRoute(route.path) ? topBarFontColor : ''
                }`"
                >{{ route.name }}</span
              >
            </router-link>
          </v-hover>
        </li>
      </ul>
    </div>
  </div>
</template>
<!-- end:: Header Menu -->
<script>
import {
  find as lodashFind,
  filter as lodashFilter,
  isEmpty as lodashIsEmpty,
  map as lodashMap,
  some as lodashSome,
  findIndex as _findIndex,
  includes as _includes,
} from 'lodash-es';

export default {
  name: 'HeaderMenu',
  props: ['topBarFontColor', 'hoverActiveColor'],
  computed: {
    routes() {
      const routes = lodashFind(this.$router.options.routes, { path: '/' });
      const user = this.$auth.user();

      const items = lodashFilter(
        routes.children,
        (item) => item.icon !== undefined
      );

      // Link to v1
      const secretShopIndex = _findIndex(items, { name: 'Secret Shops' });
      const fileManagerIndex = _findIndex(items, { name: 'File Manager' });
      items[secretShopIndex].path = '/clients/shops';
      items[fileManagerIndex].path = '/clients/file-manager';

      return lodashFilter(items, (item) => {
        let include = true;
        if (lodashIsEmpty(item.meta)) {
          return true;
        }

        // eslint-disable-next-line consistent-return
        lodashMap(item.meta.permissions, (permission) => {
          if (
            lodashSome(user.role, { name: permission.role }) &&
            !permission.access
          ) {
            include = false;
            return false;
          }
        });

        return include;
      });
    },
  },
  methods: {
    activeRoute(path) {
      return path === this.$route.path;
    },

    isNewTab(path) {
      return _includes(['Secret Shops', 'File Manager'], path) ? '_blank' : '';
    },
  },
  created() {
    // fix for v1 login
    const auth = this.$auth;
    document.cookie = `default_auth_token=${auth.token()};path=/`;
    document.cookie = `default_auth_user=${JSON.stringify(auth.user())};path=/`;
  },
};
</script>
<style lang="stylus">
.header-icon
  color #262626
</style>
