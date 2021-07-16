<template>
  <div
    class="kt-portlet votd-container"
    id="video-of-the-day"
    v-if="videoOfTheDay"
  >
    <div class="kt-portlet__body">
      <div class="kt-portlet__content text-center votd-heading">
        <h3 class="m-5">VIDEO OF THE DAY</h3>
        <div v-show="isFetchingVoTD">
          <vue-content-loader
            :width="994"
            :height="559"
            :speed="2"
            primaryColor="#e4e2e2"
            secondaryColor="#ecebeb"
          >
            <rect x="0" y="0" rx="0" ry="0" width="994" height="559" />
          </vue-content-loader>
        </div>
        <div v-show="!isFetchingVoTD">
          <h4 class="mt-3">{{ videoOfTheDay.title }}</h4>
          <div id="votd-cont" class="embed-responsive embed-responsive-16by9">
            <iframe
              title=""
              :id="'votd-' + videoOfTheDay.id"
              class="embed-responsive-item"
              :src="videoOfTheDay.url"
            ></iframe>
          </div>
          <p class="text-center mt-3 subtitle-1">
            Click the button below once you're done watching.
          </p>
          <v-btn
            color="primary"
            @click="markAsCompleted"
            :disabled="isWatched"
            :loading="isWatchedLoading"
            text
            >Mark as Completed
          </v-btn>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import VueContentLoader from 'vue-content-loading';
export default {
  name: 'VideoDay',
  components: {
    VueContentLoader,
  },
  data() {
    return {
      isFetchingVoTD: false,
      loading: false,
      videoOfTheDay: {},
      isWatched: false,
      isWatchedLoading: false,
      played: false,
    };
  },
  methods: {
    getLatestVideo() {
      this.isFetchingVoTD = true;
      this.axios
        .get('get-video-of-the-day')
        .then(({ data }) => {
          this.videoOfTheDay = data;
          this.fetchIsWatched(data.id);
        })
        .finally(() => {
          this.isFetchingVoTD = false;
          this.loadVimeoPlayerSettings();
        });
    },
    fetchIsWatched(id) {
      this.axios.get(`/featured-video-watched/${id}`).then(({ data }) => {
        if (data.watched) {
          console.log(data.watched);
          this.isWatched = data.watched.watched;
          this.played = data.watched.played;
        } else {
          this.isWatched = false;
          this.played = false;
        }
      });
    },
    loadVimeoPlayerSettings() {
      let player = document.querySelector('#votd-cont > iframe');
      let play = false;
      let vm = this;
      console.log('vimeo load');
      this.vPlayer = new Vimeo.Player(player);
      console.log(player);
      this.vPlayer.on('play', function () {
        vm.processPlayedVideo();
      });
    },
    processPlayedVideo() {
      if (!this.played) {
        this.$http
          .post('/featured-video-played', {
            featuredVideoId: this.videoOfTheDay.id,
          })
          .then((response) => {
            this.played = true;
            parent.focus();
            this.$notify('success', 'Added to your completed videos.');
            console.log('played');
          });
      } else {
        console.log('already played');
      }
    },
    markAsCompleted() {
      this.isWatchedLoading = true;
      this.axios
        .post('/featured-video-watched', {
          featuredVideoId: this.videoOfTheDay.id,
        })
        .then(({ data }) => {
          this.isWatched = data.watched;
          this.isWatchedLoading = false;
          this.$notify('success', 'Added to your completed videos.');
        });
    },
  },
  created() {
    this.getLatestVideo();
  },
};
</script>
<style lang="stylus" scoped>
.votd-heading {
  font-family: Oswald;
}

.kt-portlet {
  border-radius: 0px;
}

.kt-portlet.votd-container {
  background: #ecf0f1;
}
</style>
