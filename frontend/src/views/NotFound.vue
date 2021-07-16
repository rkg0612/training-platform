<template>
  <div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-error-v6">
      <div class="kt-error_container">
        <div class="kt-error_subtitle kt-font-light">
          <h1>Oops...</h1>
        </div>
        <p class="kt-error_description kt-font-light">
          This is not the page you are looking for.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound',
};
</script>

<style lang="stylus">
.kt-error-v6
  background-image: url('../assets/images/bg6.jpg')
</style>
