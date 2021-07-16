<template>
  <div>
    <div class="banner-section" v-show="showIntro">
      <!-- LMS Intro Video -->
      <div
        class="embed-responsive embed-responsive-16by9"
        v-if="lmsVideoBanner"
      >
        <iframe
          class="embed-responsive-item"
          :src="lmsVideoBanner"
          allowfullscreen
        >
        </iframe>
      </div>
      <!-- category slider that can be turned off using the dealer setting -->
      <carousel :per-page="1" :autoplay="true" :loop="true" v-if="showCarousel">
        <slide v-for="category in categories" v-bind:key="category.id">
          <router-link
            :to="{ name: 'Category Page', params: { id: category.id } }"
          >
            <img :src="category.thumbnail" class="img img-fluid" />
          </router-link>
        </slide>
      </carousel>
      <!-- LMS Description -->
      <div class="lms-desc m-3 p-3 d-none d-sm-block">
        <div class="text-center" v-html="lmsDescription"></div>
      </div>
      <!-- Three column category -->
      <div class="row no-gutters" v-if="showThreeColumnCategory">
        <div class="col-lg-4">
          <a href="#">
            <img
              src="https://i.imgur.com/FJwbwtJ.jpg"
              alt="showroom"
              class="img-fluid category-thumbnail"
            />
          </a>
        </div>
        <div class="col-lg-4">
          <a href="#">
            <img
              src="https://i.imgur.com/4tg7CXQ.jpg"
              alt="phone"
              class="img-fluid category-thumbnail"
            />
          </a>
        </div>
        <div class="col-lg-4">
          <a href="#">
            <img
              src="https://i.imgur.com/KVwON7t.jpg"
              alt="phone"
              class="img-fluid category-thumbnail"
            />
          </a>
        </div>
      </div>
      <!-- Video of the Day -->
      <video-day></video-day>
    </div>
  </div>
</template>
<script>
import { Carousel, Slide } from 'vue-carousel';
import VideoDay from './VideoDay.vue';
import { filter as _filter, head as _head } from 'lodash-es';

export default {
  name: 'LmsBanner',
  components: {
    VideoDay,
    Carousel,
    Slide,
  },

  data() {
    return {};
  },

  created() {
    this.$store.dispatch('clientCategories/getCategories').then(({ data }) => {
      this.$store.commit('clientCategories/setItems', data);
    });
  },

  computed: {
    categories() {
      return this.$store.state.clientCategories.items;
    },
    showIntro() {
      if (this.$route.name !== 'LMS') {
        return false;
      }
      return true;
    },
    lmsVideoBanner() {
      const showVideoBanner = this.$getSetting('show_video_banner');
      const videoBanner = this.$getSetting('lms_video_banner');
      if (videoBanner && showVideoBanner) return videoBanner;
      return null;
    },
    lmsDescription() {
      return this.$getSetting('lms_description');
    },
    showCategorySlider() {
      const show = this.$getSetting('show_category_slider');
      if (show) return true;
      return false;
    },
    showThreeColumnCategory() {
      const show = this.$getSetting('show_three_column_category');
      if (show) return true;
      return false;
    },
    showCarousel() {
      const routeIsLms = this.$route.name === 'LMS';

      return this.showCategorySlider === true && routeIsLms;
    },
  },
};
</script>
<style lang="stylus" scoped>
.lms-banner
  width 100%

.lms-desc
  font-family Oswald

.category-thumbnail
  min-width 100%
</style>
