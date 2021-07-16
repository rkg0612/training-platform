<!-- begin:: Header Mobile -->
<template>
  <div>
    <div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed">
      <div class="kt-header-mobile__logo">
        <v-btn @click.stop="side = !side" icon color="white">
          <v-icon class="menu-icon">far fa-ellipsis-v</v-icon>
        </v-btn>
        <a href="/">
          <img
            width="100%"
            alt="Logo"
            src="../../../assets/images/main-logo.png"
            id="mobile-logo"
          />
        </a>
      </div>
      <div class="kt-header-mobile__toolbar">
        <v-btn @click.stop="menu = !menu" icon color="white">
          <v-icon class="menu-icon">fal fa-bars</v-icon>
        </v-btn>
        <top-search class="mx-1 mx-sm-2" />
        <top-notification class="mr-1 mr-sm-3 mr-md-4" />
        <top-user class="user-mobile" />
      </div>
    </div>
    <div class="hide-1025px">
      <!-- start of menu -->
      <v-navigation-drawer
        class="pt-10 px-4 mt-5"
        height="300px"
        width="100%"
        v-model="menu"
        fixed
        temporary
      >
        <v-list v-for="(route, index) in routes.slice(6)" :key="index" link>
          <v-list-item class="mb-menu" :to="route.path">
            <span class="mr-3">
              <i :class="route.icon"></i>
            </span>
            <span>{{ route.name }}</span>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
      <!-- end of menu -->
      <!-- sidebar menu -->
      <v-navigation-drawer
        class="pt-10 px-4 mt-5 sidebar-width"
        width="50%"
        v-model="side"
        fixed
        color="#353535"
        temporary
      >
        <aside-menu />
      </v-navigation-drawer>
      <!-- sidebar menu -->
    </div>
  </div>
</template>
<!-- end:: Header Mobile -->
<script>
import TopSearch from './topbar/TopSearch.vue';
import TopUser from './topbar/TopUser.vue';
import TopNotification from './topbar/TopNotification.vue';
import AsideMenu from '../_aside/AsideMenu';

import {
  find as lodashFind,
  filter as lodashFilter,
  isEmpty as lodashIsEmpty,
  map as lodashMap,
  some as lodashSome,
} from 'lodash-es';

export default {
  name: 'HeaderMobile',
  components: {
    TopSearch,
    TopUser,
    TopNotification,
    AsideMenu,
  },
  data() {
    return {
      menu: false,
      side: null,
    };
  },
  computed: {
    routes() {
      const routes = lodashFind(this.$router.options.routes, { path: '/' });
      const user = this.$auth.user();

      const items = lodashFilter(
        routes.children,
        (item) => item.icon !== undefined
      );

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
  },
};
</script>

<style>
.mb-menu {
  font-size: 17px;
}
.sidebar-width {
  min-width: 240px;
}
.menu-icon {
  height: 24px !important;
  font-size: 24px !important;
  min-width: 24px !important;
}
.user-mobile {
  color: white;
  font-size: 14px;
}
.user-mobile img {
  align-self: center;
  height: 34px;
  border-radius: 4px;
  margin-left: 10px;
}
@media screen and (min-width: 1025px) {
  .hide-1025px {
    display: none;
  }
}
@media screen and (max-width: 425px) {
  .kt-header-mobile__logo {
    max-width: 120px;
  }
  .user-mobile img {
    align-self: center;
    height: 24px;
    border-radius: 4px;
    margin-left: 8px;
  }
  .menu-icon {
    height: 18px !important;
    font-size: 18px !important;
    min-width: 18px !important;
  }
}
</style>
