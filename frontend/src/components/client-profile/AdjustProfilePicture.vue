<template>
  <v-dialog v-model="show" width="250" height="250">
    <v-card>
      <div class="mx-auto">
        <croppa
          v-model="croppa"
          :width="250"
          :height="250"
          :initial-image="image"
          :prevent-white-space="true"
          :placeholder="'Click to Upload a Photo'"
          :placeholder-font-size="16"
          :remove-button-color="'gray'"
          :remove-button-size="0"
        >
        </croppa>
      </div>

      <v-card-actions>
        <v-btn text color="blue-grey darken-1" @click="croppa.rotate()">
          Rotate
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn text color="primary" @click="save"> Save </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'AdjustProfileImage',
  data() {
    return {
      croppa: {},
      initialImage: '',
      image: '',
    };
  },
  props: {
    value: Boolean,
  },
  mounted() {
    this.setInitialImage();
  },
  computed: {
    show: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit('input', value);
      },
    },
  },
  watch: {
    show: {
      handler: function (val) {},
    },
  },
  methods: {
    setInitialImage() {
      this.axios
        .get(`profile/picture/${this.$auth.user().id}`)
        .then(({ data }) => {
          this.image = data;
        });
    },
    save() {
      const data = this.croppa.generateDataUrl('image/jpeg');
      this.axios
        .patch(`profile/picture/${this.$auth.user().id}`, {
          params: {
            picture: data,
          },
        })
        .then(({ data }) => {
          this.$notify('success', 'Profile picture updated!');
          this.value = false;
        });
    },
  },
};
</script>

<style scoped></style>
