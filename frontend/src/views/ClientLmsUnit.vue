<template>
  <div>
    <unit-page></unit-page>
  </div>
</template>
<script>
import UnitPage from '../components/client-lms/UnitPage.vue';
import { head as _head } from 'lodash-es';

export default {
  name: 'ClientLmsModule',
  components: {
    UnitPage,
  },
  mounted() {
    this.setParameters();
  },
  methods: {
    setParameters() {
      if (this.$route.params.playlistId) {
        this.$store.commit(
          'markAsCompleted/setPlaylistId',
          this.$route.params.playlistId
        );
        this.setPlaylist(this.$route.params.playlistId);
      } else {
        this.$store.commit('markAsCompleted/setType', 'single');
        this.setSingleUnit(this.$route.params.id);
      }
      this.$store.commit(
        'markAsCompleted/setCurrentUnitId',
        this.$route.params.id
      );
    },
    setSingleUnit(unitId) {
      this.$store.commit('relatedUnits/setPlaylist', []);
      this.$store.commit('relatedUnits/setType', 'single');
      this.$store
        .dispatch('relatedUnits/fetchSingleRelatedUnits', {
          type: 'single',
          unitId: unitId,
        })
        .then(({ data }) => {
          this.$store.commit('relatedUnits/setSingleUnit', _head(data));
          this.$store.commit('relatedUnits/setIsLoaded', true);
        });
    },
    setPlaylist(playlistId) {
      this.$store.commit('relatedUnits/setType', 'playlist');
      this.$store
        .dispatch('relatedUnits/fetchRelatedUnits', {
          type: 'playlist',
          playlistId: playlistId,
        })
        .then(({ data }) => {
          this.$store.commit('relatedUnits/setPlaylist', _head(data));
          this.$store.commit('relatedUnits/setIsLoaded', true);
        });
    },
  },
};
</script>
