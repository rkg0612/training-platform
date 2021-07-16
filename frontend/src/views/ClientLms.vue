<template>
  <div>
    <shortcut-section />
    <main-course-library
      v-for="course in courses"
      :key="course.id"
      :courseBuild="course"
    ></main-course-library>
    <div class="text-center mt-3" v-show="isLoading">
      <v-card>
        <v-progress-circular
          :size="50"
          color="primary"
          indeterminate
          class="pt-2 pb-2"
        ></v-progress-circular>
      </v-card>
    </div>
  </div>
</template>
<script>
import ShortcutSection from '../components/client-lms/ShortcutSection.vue';
import MainCourseLibrary from '../components/client-lms/MainCourseLibrary.vue';

export default {
  name: 'ClientLms',
  components: {
    ShortcutSection,
    MainCourseLibrary,
  },

  data() {
    return {
      bottom: false,
      next_build: 1,
      next_index: 1,
      courses: [],
      isQueryAllowed: true,
      isLoading: false,
    };
  },

  computed: {
    //
  },

  created() {
    this.fetchUsers();
    window.addEventListener('scroll', () => {
      this.bottom = this.bottomVisible();
    });
    this.fetchLibrary();
  },

  methods: {
    bottomVisible() {
      const { scrollY } = window;
      const visible = document.documentElement.clientHeight;
      const pageHeight = document.documentElement.scrollHeight;
      const bottomOfPage = visible + scrollY >= pageHeight;
      return bottomOfPage || pageHeight < visible;
    },
    fetchLibrary() {
      if (!this.isQueryAllowed) {
        return;
      }

      this.toggleLoadingComponent();
      this.$store
        .dispatch('lmsClient/getLibraryHome', {
          current_build: this.next_build,
          current_index: this.next_index,
        })
        .then(({ data }) => {
          this.toggleLoadingComponent();
          if (data) {
            this.$store.dispatch('lmsClient/addLibraryHome', data.data);
            this.courses.push(data.data);
            this.next_index = data.next_index;
            this.next_build = data.next_build;
          } else {
            console.log('treasuer');
            this.isQueryAllowed = false;
          }
        })
        .catch(() => {
          this.toggleLoadingComponent();
          console.log(
            'Encountered an error while loading your course library. Please try again'
          );
        });
    },

    fetchUsers() {
      this.$store
        .dispatch('lmsClient/fetchUsersByDealer')
        .then(({ data }) => {
          this.$store.dispatch('lmsClient/setUsers', data);
        })
        .catch(() => {
          console.log('error');
        });
    },
    toggleLoadingComponent() {
      this.isLoading = !this.isLoading;
    },
  },
  watch: {
    bottom(bottom) {
      if (bottom) {
        console.log('boom');
        this.fetchLibrary();
      }
    },
  },
};
</script>
