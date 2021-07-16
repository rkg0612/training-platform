<template>
  <div id="componentLoaderContainer" v-if="active">
    <div id="componentLoader">
      <span class="font-weight-medium">Please wait...</span>
      <div class="kt-spinner kt-spinner--loader kt-spinner--warning"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ComponentLoader',
  props: ['active'],
  data() {
    return {
      positionRelative: 'positionRelative',
    };
  },
  mounted() {
    this.$parent.$el.classList.add(this.positionRelative);
  },
  watch: {
    active(show) {
      if (!show) {
        this.$parent.$el.classList.remove(this.positionRelative);
      } else {
        this.$parent.$el.classList.add(this.positionRelative);
      }
    },
  },
};
</script>

<style scoped lang="stylus">
.kt-spinner
    position absolute
    right 40px
    top 25px
</style>
