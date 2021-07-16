<template>
  <div class="row">
    <template v-for="recording in recordings">
      <div class="col-4">
        <v-card>
          <v-card-title>
            <p class="ml-2">{{ recording.name }}</p></v-card-title
          >
          <v-card-text>
            <audio :src="recording.file" ref="audio" controls></audio>
          </v-card-text>
          <v-card-actions>
            <v-btn @click="removeRecording(recording)">Remove Recording</v-btn>
          </v-card-actions>
        </v-card>
      </div>
    </template>
  </div>
</template>
<script>
export default {
  name: 'DisplayAudio',
  props: {
    removedFiles: {
      type: Array,
    },
    typeOfRecordings: {
      type: String,
    },
  },
  computed: {
    recordings() {
      if (this.typeOfRecordings === 'dealerRecordings') {
        return this.$store.state.phoneShops.dealerRecordings;
      }
      return this.$store.state.phoneShops.competitorRecordings;
    },
  },
  methods: {
    removeRecording(item) {
      const { file } = item;
      if (file.toLowerCase().includes('aws')) {
        this.removedFiles.push(item);
      }
      this.$store.dispatch('phoneShops/removeRecording', {
        recording: item,
        type: this.typeOfRecordings,
      });
    },
  },
};
</script>

<style scoped></style>
