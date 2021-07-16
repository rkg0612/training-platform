<template>
  <v-card class="mx-auto text-left">
    <v-img
      class="white--text"
      :src="unit.thumbnail"
      min-height="200px"
      max-height="350px"
      gradient="to top right, rgba(0,0,0,.70), rgba(25,32,72,.7)"
    >
      <!--Will show if unit is completed-->
      <v-chip
        class="ma-2"
        color="yellow darken-3"
        dark
        v-if="unit.is_completed"
        small
      >
        <v-avatar left class="yellow darken-4">
          <i class="fas fa-badge-check m-1"></i>
        </v-avatar>
        Completed
      </v-chip>
      <!--End of completed status-->
      <!--Will show if unit is assigned-->
      <v-chip
        v-else-if="unit.due_date !== null"
        class="ma-2"
        color="red darken-1"
        dark
        small
      >
        <v-avatar left class="red darken-4">
          <i class="fal fa-share-square"></i>
        </v-avatar>
        Assigned (Due Date: {{ dueDate }})
      </v-chip>
      <!--End of assigned status-->
      <!--Will show if unit is shared-->
      <v-chip
        v-else-if="unit.shared_by !== null"
        class="ma-2"
        color="purple darken-1"
        dark
        small
      >
        <v-avatar left class="purple darken-3">
          <i class="fal fa-retweet"></i>
        </v-avatar>
        Shared
      </v-chip>
      <!--End of shared status-->
      <v-card-title class="headline justify-center">
        {{ unit.name }}
      </v-card-title>
    </v-img>
    <div class="text-center">
      <p class="mt-3">
        <span>Tags:</span>
        <v-chip
          class="ml-1 grey lighten-3 mt-2"
          small
          v-for="tags in unit.tags"
          >{{ tags.name }}</v-chip
        >
      </p>
      <p>
        <i class="fal fa-clock"></i>
        Run Time: {{ unit.video_duration }}
      </p>
      <v-btn
        small
        color="light-blue darken-2"
        class="m-1"
        @click="takeNow(unit)"
        text
      >
        <v-icon class="m-2">fal fa-play</v-icon>
        Open
      </v-btn>
      <v-btn
        small
        dark
        color="red darken-1"
        class="m-1"
        @click="toggleFavorite"
        :loading="favoriteLoader"
        :disabled="favoriteLoader"
        text
      >
        <v-icon class="m-2">fal {{ favoriteIcon }}</v-icon>
        {{ favoriteText }}
      </v-btn>
      <v-menu>
        <template v-slot:activator="{ on, attrs }">
          <v-btn v-bind="attrs" v-on="on" text small>
            <v-icon class="mr-2">fal fa-list-ul</v-icon>
            More
          </v-btn>
        </template>

        <v-list>
          <v-list-item>
            <v-btn
              dark
              small
              outlined
              color="grey darken-3"
              @click.stop="showShareUnit = true"
              text
            >
              <v-icon class="mr-2">fal fa-share-alt</v-icon>
              Share
            </v-btn>
            <share-unit :unit_id="unit.id" v-model="showShareUnit" />
          </v-list-item>
          <v-list-item>
            <v-btn
              dark
              small
              outlined
              color="grey darken-3"
              @click.stop="showAssignUnit = true"
              text
            >
              <v-icon class="mr-2">fal fa-users-cog</v-icon>
              Assign
            </v-btn>
            <assign-unit :unit_id="unit.id" v-model="showAssignUnit" />
          </v-list-item>
          <v-list-item>
            <v-btn
              dark
              small
              outlined
              color="grey darken-3"
              @click.stop="showUnitPlaylist = true"
              text
            >
              <v-icon class="mr-2">fal fa-layer-plus</v-icon>
              Add to Playlist
            </v-btn>
            <unit-playlist :unit="unit" v-model="showUnitPlaylist" />
          </v-list-item>
        </v-list>
      </v-menu>
      <v-card-actions class="flex-wrap">
        <div
          class="row no-gutters justify-content-center flex-nowrap align-items-center"
        >
          <v-btn class="my-1" icon @click="showDescription = !showDescription">
            <v-icon>
              {{
                showDescription ? 'fal fa-chevron-up' : 'fal fa-chevron-down'
              }}
            </v-icon>
          </v-btn>
        </div>
      </v-card-actions>
      <v-expand-transition>
        <div v-show="showDescription">
          <v-divider></v-divider>
          <v-card-text>
            {{ unit.description }}
          </v-card-text>
        </div>
      </v-expand-transition>
    </div>
  </v-card>
</template>
<script>
import ShareUnit from './dialog-box/ShareUnit.vue';
import AssignUnit from './dialog-box/AssignUnit.vue';
import UnitPlaylist from './dialog-box/UnitPlaylist.vue';
import dayjs from 'dayjs';

export default {
  name: 'UnitCard',

  props: {
    unit: {
      type: Object,
      required: true,
    },
    type: {
      type: String,
      default: 'default',
    },
  },
  components: {
    ShareUnit,
    AssignUnit,
    UnitPlaylist,
  },

  data: () => ({
    showDescription: false,
    showShareUnit: false,
    showAssignUnit: false,
    showUnitPlaylist: false,
    favoriteLoader: false,
  }),

  computed: {
    dueDate() {
      return dayjs(this.unit.due_date).format('MMMM D, YYYY');
    },
    thumbnail() {
      return this.unit.thumbnail;
    },
    favoriteIcon() {
      return this.type !== 'favorite' ? 'fa-heart' : 'fa-times';
    },
    favoriteText() {
      return this.type !== 'favorite' ? 'Favorite' : 'UnFavorite';
    },
  },

  methods: {
    toggleFavorite() {
      this.favoriteLoader = true;
      const { id } = this.unit;

      // if unit is already a favorite type
      // then this is already in the favorite list
      // the proceed to un-favorite
      if (this.type === 'favorite') {
        this.$http
          .delete(`/unit/favorite/${id}`)
          .then(() => {
            this.favoriteLoader = false;
            this.$store.dispatch('lmsClient/unFavorite', id);
          })
          .catch(() => {
            this.favoriteLoader = false;
          });
        return;
      }

      // this will add the unit to he list of favorites
      this.$http
        .post('/unit/favorite', {
          unit_id: id,
        })
        .then(() => {
          this.favoriteLoader = false;
          this.$store.dispatch('lmsClient/addFavorite', this.unit);
          this.$notify('success', 'Successfully added to your favorites!');
        })
        .catch(() => {
          this.favoriteLoader = false;
        });
    },
    takeNow(unit) {
      this.$router.push({
        name: 'UnitPage',
        params: {
          id: unit.id,
        },
      });
    },
  },
};
</script>
<style lang="stylus">
.unit-tools
  width 100% !important
</style>
