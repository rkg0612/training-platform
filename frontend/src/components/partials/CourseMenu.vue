<template>
  <div
    id="courseMenuWrapper"
    class="position-absolute animate__animated animate__faster"
    :class="showMenu ? 'animate__fadeInDown' : 'animate__fadeOut'"
    v-show="showMenu"
  >
    <div class="row justify-content-md-center">
      <div class="col d-none d-md-block" style="flex: 0 0 100px" />
      <div class="col d-block d-md-none" style="flex: 0 0 20px" />
      <div class="col">
        <h4>Browse Courses</h4>
        <hr class="my-5" />
        <v-chip
          v-for="(category, index) in categories"
          :key="index"
          v-html="category.name"
          class="mr-2 mb-1"
          @click="toggleCategory(index)"
          :color="!category.active ? 'default' : '#f9b418'"
        />
        <div class="row pl-3 mt-4">
          <div
            class="col-4 pl-0 mb-4"
            v-for="(module, index) in modules"
            :key="index"
          >
            <h5 class="mb-4" v-html="module.name"></h5>
            <template v-for="(unit, unitIndex) in module.units">
              <a
                href="javascript:"
                class="unitLinks"
                :key="unitIndex"
                v-if="unitIndex <= 5"
                @click.prevent="goToLink('unit', unit.id)"
              >
                {{ unit.name }}
              </a>
            </template>
            <a
              href="javascript:"
              class="showAllLinks font-weight-bold mt-4"
              @click.prevent="goToLink('module', module.module_id)"
            >
              Show all
            </a>
          </div>
        </div>
      </div>
      <div class="col d-none d-lg-block" style="flex: 0 0 500px" />
      <div class="col d-none d-md-block d-lg-none" style="flex: 0 0 200px" />
    </div>
  </div>
</template>

<script>
import { map as _map, flatten as _flatten, filter as _filter } from 'lodash-es';

export default {
  name: 'CourseMenu',
  computed: {
    categories() {
      return this.$store.state.lmsClient.categories;
    },
    modules() {
      const mapped = _map(this.filtered, (item) => item.modules);
      return _flatten(mapped);
    },
    filtered() {
      const categories = _filter(
        this.$store.state.lmsClient.categories,
        (item) => {
          return item.active;
        }
      );

      if (categories.length < 1) {
        return this.$store.state.lmsClient.categories;
      }

      return categories;
    },
    showMenu() {
      return this.$store.state.app.menu;
    },
  },
  methods: {
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
    toggleCategory(index) {
      this.categories[index].active = !this.categories[index].active;
    },
    goToLink(type, id) {
      const route = type === 'unit' ? 'UnitPage' : 'ModulePage';
      this.$router.push({
        name: route,
        params: {
          id,
        },
      });
      this.$store.dispatch('app/setMenuState', false);
    },
  },
};
</script>

<style lang="stylus" scoped>
#courseMenuWrapper
  background white
  width 100%
  height 100%
  z-index 3
  top 45px
  padding-top 30px
  --animate-duration: 0.45s;

#courseMenuWrapper .row > .col
  background-color #fff

.unitLinks
  color #5b5b5b !important
  font-size 15px
  display block

.unitLinks:hover
  text-decoration underline!important

.showAllLinks
  color #f9b418 !important
  font-size 15px
  display block
</style>
