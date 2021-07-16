<template>
  <div class="kt-portlet kt-portlet--head-lg k-portlet--height-fluid">
    <div class="kt-portlet__body text-center">
      <h3 class="playlist-heading">
        <i class="fal fa-album-collection m-1"></i>
        Shortcuts
      </h3>
      <v-divider></v-divider>
      <div class="custome-break-point d-flex flex-wrap">
        <div class="row">
          <div class="col-lg-3">
            <v-hover v-slot:default="{ hover }">
              <v-card
                dark
                :to="'/client/lms/playlists'"
                :class="{ 'on-hover': hover }"
                max-width="400px"
              >
                <v-img
                  src="https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/assets/playlist.jpg"
                  class="white--text"
                  gradient="to bottom, rgba(0,0,0,.5), rgba(0,0,0,.8)"
                  height="350px"
                >
                  <v-container fill-height fluid>
                    <v-row align="center" justify="center">
                      <v-col>
                        <v-card-title class="h4 headline justify-center">
                          <v-btn icon>
                            <v-icon dark>fal fa-play </v-icon>
                          </v-btn>
                          Your Playlists
                        </v-card-title>
                        <v-card-text class="justify-center subtitle-1">
                          Playlists you created or shared to you.
                        </v-card-text>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-img>
              </v-card>
            </v-hover>
          </div>
          <div class="col-lg-3">
            <v-hover v-slot:default="{ hover }">
              <v-card
                dark
                :to="'/client/lms/assigned-units'"
                :class="{ 'on-hover': hover }"
                max-width="400px"
              >
                <v-img
                  src="https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/assets/assigned.jpg"
                  class="white--text"
                  gradient="to bottom, rgba(0,0,0,.5), rgba(0,0,0,.8)"
                  height="350px"
                >
                  <v-container fill-height fluid>
                    <v-row align="center" justify="center">
                      <v-col>
                        <v-card-title class="h4 headline justify-center">
                          <v-btn icon>
                            <v-icon dark>fal fa-share-alt </v-icon>
                          </v-btn>
                          Assigned Units
                        </v-card-title>
                        <v-card-text class="justify-center subtitle-1">
                          Assigned or shared units to you.
                        </v-card-text>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-img>
              </v-card>
            </v-hover>
          </div>
          <div class="col-lg-3">
            <v-hover v-slot:default="{ hover }">
              <v-card
                dark
                :to="'/client/lms/favorites'"
                :class="{ 'on-hover': hover }"
                max-width="400px"
              >
                <v-img
                  src="https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/assets/favorite.jpg"
                  class="white--text"
                  gradient="to bottom, rgba(0,0,0,.5), rgba(0,0,0,.8)"
                  height="350px"
                >
                  <v-container fill-height fluid>
                    <v-row align="center" justify="center">
                      <v-col>
                        <v-card-title class="h4 headline justify-center">
                          <v-btn icon>
                            <v-icon dark>fal fa-heart </v-icon>
                          </v-btn>
                          Your Favorites
                        </v-card-title>
                        <v-card-text class="justify-center subtitle-1">
                          Your all-time favorite units.
                        </v-card-text>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-img>
              </v-card>
            </v-hover>
          </div>
          <div class="col-lg-3">
            <v-hover v-slot:default="{ hover }">
              <v-card
                dark
                :to="'/client/lms/most-searched'"
                :class="{ 'on-hover': hover }"
                max-width="400px"
              >
                <v-img
                  src="https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/assets/searched.jpg"
                  class="white--text"
                  gradient="to bottom, rgba(0,0,0,.5), rgba(0,0,0,.8)"
                  height="350px"
                >
                  <v-container fill-height fluid>
                    <v-row align="center" justify="center">
                      <v-col>
                        <v-card-title class="h4 headline justify-center">
                          <v-btn icon>
                            <v-icon dark>fal fa-search </v-icon>
                          </v-btn>
                          Most Searched
                        </v-card-title>
                        <v-card-text class="justify-center subtitle-1">
                          Most searched units of your organization.
                        </v-card-text>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-img>
              </v-card>
            </v-hover>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'ShortcutSection',
};
</script>
<style lang="stylus" scoped>
.v-card:not(.v-sheet--tile):not(.v-card--shaped)
  border-radius 0px

.v-card
  transition opacity .4s ease-in-out

.v-card:not(.on-hover)
  opacity 0.90
</style>
