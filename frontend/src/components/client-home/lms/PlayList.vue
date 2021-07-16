<template>
  <div class="col-lg-4">
    <v-card class="mx-auto">
      <v-img
        class="white--text align-end"
        height="80px"
        src="https://i.imgur.com/olFqB1t.jpg"
        gradient="to top right, rgba(0,0,0,.33), rgba(25,32,72,.7)"
      >
        <v-card-title class="headline">
          <v-icon left dark> fal fa-cassette-tape </v-icon>
          Playlist
        </v-card-title>
      </v-img>
      <div class="kt-portlet kt-portlet--height-fluid">
        <section>
          <div class="kt-portlet__body" v-if="!isEmpty(playlists)">
            <div class="tab-content">
              <div class="tab-pane active" id="kt_widget4_tab1_content">
                <div class="kt-widget4">
                  <div
                    class="kt-widget4__item"
                    v-for="(playlist, index) in playlists"
                    :key="index"
                  >
                    <div class="kt-widget4__info">
                      <a href="#" class="kt-widget4__username">
                        {{ playlist.name }}
                      </a>
                      <p class="kt-widget4__text">
                        <span v-if="playlist.assigned_by"
                          >Assigned By: {{ playlist.assigned_by }}
                        </span>
                      </p>
                    </div>
                    <v-btn
                      :to="'/playlist/' + playlist.id"
                      text
                      color="primary"
                      small
                    >
                      <v-icon class="mr-2" small>fal fa-eye</v-icon>
                      View
                    </v-btn>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-if="isEmpty(playlists)">
            <p class="ml-3 my-3" style="color: #595d6e">
              You currently don't have any in playlist.
            </p>
          </div>
        </section>
      </div>
    </v-card>
  </div>
</template>
<script>
import { isEmpty as _isEmpty } from 'lodash-es';
export default {
  name: 'PlayList',
  data: () => ({
    loading: true,
    playlists: [],
    isEmpty: _isEmpty,
  }),

  computed: {
    user() {
      return this.$auth.user();
    },
  },

  methods: {
    loadPlaylists() {
      this.$store
        .dispatch('playlist/fetchHomePlaylists')
        .then(({ data }) => {
          this.loading = false;
          this.playlists = data;
        })
        .catch(() => {
          this.loading = false;
          console.log(
            'Encountered an error while loading the playlists. Please try again'
          );
        });
    },
  },
  created() {
    this.loadPlaylists();
  },
};
</script>
