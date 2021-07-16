<!-- begin:: Aside Menu -->
<template>
  <div
    class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid"
    id="kt_aside_menu_wrapper"
    :style="sideBarBgColor"
  >
    <div id="kt_aside_menu" class="kt-aside-menu kt-scroll">
      <ul class="kt-menu__nav" id="aside-menu" :style="sideBarBgColor">
        <!-- Start of Admin Menu -->
        <li
          class="kt-menu__item"
          v-for="(route, index) in routes.slice(0, 4)"
          :style="activeRoute(route.path) ? sideBarActiveColor : ''"
          :key="`route${index}`"
          v-show="!isCurrentUserAClient"
        >
          <v-hover>
            <router-link
              :to="route.path"
              class="kt-menu__link"
              slot-scope="{ hover }"
              :style="`${hover ? sideBarActiveColor : ''}`"
            >
              <span class="kt-menu__link-icon">
                <i
                  class="kt-svg-icon"
                  :class="route.icon"
                  :style="`${hover ? sideBarFontHover : ''} ${
                    activeRoute(route.path) ? sideBarFontHover : ''
                  }`"
                ></i>
              </span>
              <span
                class="kt-menu__link-text"
                :style="`${hover ? sideBarFontHover : ''} ${
                  activeRoute(route.path) ? sideBarFontHover : ''
                }`"
                >{{ route.name }}</span
              >
            </router-link>
          </v-hover>
        </li>
        <li class="kt-menu__item">
          <v-hover>
            <router-link
              to="/client/profile"
              class="kt-menu__link"
              :style="sideBarFontHover"
            >
              <span class="kt-menu__link-icon">
                <i
                  class="kt-svg-icon fal fa-user-circle"
                  :style="sideBarFontHover"
                ></i>
              </span>
              <span class="kt-menu__link-text" :style="sideBarFontHover"
                >My Profile</span
              >
            </router-link>
          </v-hover>
        </li>
        <li v-show="!isCurrentUserAClient" class="kt-menu__item">
          <v-hover>
            <a
              href="/clients/secretshop"
              class="kt-menu__link"
              :style="sideBarFontHover"
              target="_blank"
            >
              <span class="kt-menu__link-icon"> </span>
              <span class="kt-menu__link-text" :style="sideBarFontHover"
                >V1 Secret Shop Posts</span
              >
            </a>
          </v-hover>
        </li>
        <li v-show="!isCurrentUserAClient" class="kt-menu__item">
          <v-hover>
            <a
              href="/clients/secretshop/numbers"
              class="kt-menu__link"
              :style="sideBarFontHover"
              target="_blank"
            >
              <span class="kt-menu__link-icon"> </span>
              <span class="kt-menu__link-text" :style="sideBarFontHover"
                >V1 Secret Shop Numbers</span
              >
            </a>
          </v-hover>
        </li>
        <li v-show="!isCurrentUserAClient" class="kt-menu__item">
          <v-hover>
            <a
              href="/clients/users"
              class="kt-menu__link"
              :style="sideBarFontHover"
              target="_blank"
            >
              <span class="kt-menu__link-icon"> </span>
              <span class="kt-menu__link-text" :style="sideBarFontHover"
                >V1 Users</span
              >
            </a>
          </v-hover>
        </li>
        <li v-show="!isCurrentUserAClient" class="kt-menu__item">
          <v-hover>
            <a
              href="/clients/dealer/all"
              class="kt-menu__link"
              :style="sideBarFontHover"
              target="_blank"
            >
              <span class="kt-menu__link-icon"> </span>
              <span class="kt-menu__link-text" :style="sideBarFontHover"
                >V1 Dealers</span
              >
            </a>
          </v-hover>
        </li>
        <li class="kt-menu__item">
          <v-hover>
            <a @click="logout" class="kt-menu__link" :style="sideBarFontHover">
              <span class="kt-menu__link-icon">
                <i
                  class="kt-svg-icon fal fa-sign-out"
                  :style="sideBarFontHover"
                ></i>
              </span>
              <span class="kt-menu__link-text" :style="sideBarFontHover"
                >Logout</span
              >
            </a>
          </v-hover>
        </li>

        <li class="kt-menu__section" v-if="scormCoursesAvailable">
          <h4 class="kt-menu__section-text">SCORM COURSES</h4>
          <i class="fas fa-info-square kt-menu__section-icon" />
        </li>

        <aside-scorm-courses
          v-for="(course, index) in scormCourses"
          :key="index"
          :course="course"
        />

        <li class="kt-menu__section">
          <h4 class="kt-menu__section-text">TRAINING CATEGORIES</h4>
          <i class="fas fa-info-square kt-menu__section-icon" />
        </li>

        <template v-if="loading">
          <vcl-list
            :speed="1.3"
            primary="#848484"
            height="120"
            width="250"
            :rows="20"
            class="ml-5"
            v-for="index in 2"
            :key="index"
          />
        </template>

        <li
          class="kt-menu__item kt-menu__item--submenu"
          v-for="(category, index) in categories"
          :key="`category${index}`"
          :class="{
            'kt-menu__item--open': category.active,
            'kt-menu__item--here': activeCategory(category),
          }"
          v-else
        >
          <a
            href="javascript:"
            class="kt-menu__link kt-menu__toggle"
            @click="toggleCategory(index)"
          >
            <span class="kt-menu__link-icon">
              <i class="fa fa-list" />
            </span>
            <span class="kt-menu__link-text" id="category_menu_text">{{
              category.name
            }}</span>
            <i class="kt-menu__ver-arrow fa fa-chevron-right" />
          </a>
          <div class="kt-menu__submenu">
            <span class="kt-menu__arrow"></span>
            <ul class="kt-menu__subnav">
              <li
                class="kt-menu__item kt-menu__item--submenu"
                v-for="(module, moduleIndex) in category.modules"
                :key="`module${moduleIndex}`"
                :class="{
                  'kt-menu__item--open': module.active,
                  'kt-menu__item--here': activeModule(module),
                }"
              >
                <a
                  href="javascript:"
                  class="kt-menu__link kt-menu__toggle"
                  @click="toggleModule(index, moduleIndex)"
                >
                  <i class="kt-menu__link-bullet kt-menu__link-bullet--line">
                    <span></span>
                  </i>
                  <span class="kt-menu__link-text">{{ module.name }}</span>
                  <i class="kt-menu__ver-arrow fa fa-chevron-right" />
                </a>
                <div class="kt-menu__submenu">
                  <span class="kt-menu__arrow"></span>
                  <ul class="kt-menu__subnav">
                    <li class="kt-menu__item">
                      <v-btn
                        :to="`/client/lms/module/${module.module_id}`"
                        class="ma-1"
                        color="blue darken-1"
                        text
                      >
                        <v-icon class="mr-2" small
                          >fal fa-external-link-alt</v-icon
                        >
                        <span class="kt-menu__link-text"> Open Module </span>
                      </v-btn>
                    </li>
                    <li
                      class="kt-menu__item"
                      v-for="(unit, unitIndex) in module.units"
                      v-if="unit !== null"
                      :key="`unit${unitIndex}`"
                      :class="unit.active ? 'kt-menu__item--active' : ''"
                    >
                      <router-link
                        :to="`/client/lms/unit/${unit.id}`"
                        class="kt-menu__link"
                      >
                        <i
                          class="kt-menu__link-bullet kt-menu__link-bullet--dot"
                        >
                          <span></span>
                        </i>
                        <span class="kt-menu__link-text">{{ unit.name }}</span>
                      </router-link>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import {
  find as _find,
  filter as _filter,
  isEmpty as _isEmpty,
  map as _map,
  some as _some,
  findIndex as _findIndex,
  forEach as _forEach,
} from 'lodash-es';
import { VclList } from 'vue-content-loading';
import AsideScormCourses from '@/components/partials/_aside/AsideScormCourses.vue';

export default {
  name: 'AsideMenu',
  components: {
    AsideScormCourses,
    VclList,
  },
  data() {
    return {
      scormCourses: [],
    };
  },
  computed: {
    roles() {
      return this.$auth.user().roles;
    },
    sideBarBgColor() {
      const bgColor = this.$getSetting('sidebar_bg_color');
      if (bgColor) {
        return `background-color:${bgColor}`;
      }
      return 'background-color:#353535';
    },
    routes() {
      const routes = _find(this.$router.options.routes, { path: '/' });
      const user = this.$auth.user();

      let items = _filter(routes.children, (item) => item.icon !== undefined);

      return _filter(items, (item) => {
        let include = true;
        if (_isEmpty(item.meta)) {
          return true;
        }

        // eslint-disable-next-line consistent-return
        _map(item.meta.permissions, (permission) => {
          if (
            _some(user.roles, { name: permission.role }) &&
            permission.access === false
          ) {
            include = false;
            return false;
          }
        });

        return include;
      });
    },
    isCurrentUserAClient() {
      let isClient = false;
      _forEach(this.roles, (role) => {
        if (
          role.name.indexOf('salesperson') !== -1 ||
          role.name.indexOf('manager') !== -1
        ) {
          isClient = true;
        }
      });
      return isClient;
    },
    sideBarActiveColor() {
      const activeHoverColor = this.$getSetting('sidebar_menu_hover_color');
      if (activeHoverColor) {
        return `background-color: ${activeHoverColor} !important;`;
      }
      return '#555555';
    },
    sideBarFontHover() {
      const fontColor = this.$getSetting('sidebar_font_color');
      if (fontColor) {
        return `color: ${fontColor} !important`;
      }
      return '#A2A3B7';
    },
    categories() {
      return this.$store.state.lmsClient.categories;
    },
    loading() {
      return this.$store.state.lmsClient.categoriesLoading;
    },
    scormCoursesAvailable() {
      return this.scormCourses.length > 0;
    },
  },
  methods: {
    logout() {
      this.$store.dispatch('lmsClient/deleteCategories');
      this.$auth.logout();
    },
    activeRoute(path) {
      return path === this.$route.path;
    },
    toggle(event) {
      const parent = this.getClosest(event.target, '.kt-menu__item');
      parent.classList.toggle('kt-menu__item--open');
    },
    toggleForModuleBuilds(event) {
      event.target.classList.toggle('powerflex');
    },
    getClosest(elem, selector) {
      // Element.matches() polyfill
      if (!Element.prototype.matches) {
        Element.prototype.matches =
          Element.prototype.matchesSelector ||
          Element.prototype.mozMatchesSelector ||
          Element.prototype.msMatchesSelector ||
          Element.prototype.oMatchesSelector ||
          Element.prototype.webkitMatchesSelector ||
          function (s) {
            var matches = (
                this.document || this.ownerDocument
              ).querySelectorAll(s),
              i = matches.length;
            while (--i >= 0 && matches.item(i) !== this) {}
            return i > -1;
          };
      }

      // Get the closest matching element
      for (; elem && elem !== document; elem = elem.parentNode) {
        if (elem.matches(selector)) return elem;
      }
      return null;
    },
    toggleCategory(index) {
      this.categories[index].active = !this.categories[index].active;
    },
    toggleModule(index, moduleIndex) {
      const module = this.categories[index].modules[moduleIndex];
      this.categories[index].modules[moduleIndex].active = !module.active;
    },
    activeCategory(category) {
      if (this.$route.name !== 'UnitPage') {
        return;
      }

      let active = false;
      category.modules.forEach((module) => {
        active = _some(module.units, { id: Number(this.$route.params.id) });
        if (active) {
          return false;
        }
      });

      // eslint-disable-next-line consistent-return
      return active;
    },
    activeModule(module) {
      if (this.$route.name !== 'UnitPage') {
        return;
      }

      // eslint-disable-next-line consistent-return
      return _some(module.units, { id: Number(this.$route.params.id) });
    },
    fetchBuild() {
      this.$http.get('/client/lms/fetchCourseLibrary').then(({ data }) => {
        const categories = data;

        categories.map((item) => {
          const category = item;
          category.active = false;
          category.modules.map((module) => (module.active = false));
          return category;
        });

        this.$store.dispatch('lmsClient/setCategories', categories);
      });
    },
    getScormCourses() {
      this.$http
        .get('/scorm/course')
        .then(({ data }) => {
          this.scormCourses = data;
        })
        .catch(() => {});
    },
  },
  created() {
    if (this.categories.length < 1) {
      this.fetchBuild();
    }

    this.getScormCourses();
  },
};
</script>

<style lang="stylus" scoped>

.powerflex
  display: flex;

.scroll-area
  position: relative;
  margin: auto;
  width: 400px;
  height: 300px;


#category_menu_text
  text-transform uppercase
  font-weight 700
</style>
