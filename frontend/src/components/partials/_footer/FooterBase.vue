<!-- begin:: Footer -->
<template>
  <div
    class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop"
    id="kt_footer"
  >
    <div class="kt-container kt-container--fluid">
      <div class="kt-footer__copyright">
        {{ new Date().getFullYear() }}&nbsp;&copy;&nbsp; Training Hub for Car
        People by &nbsp;
        <a href="https://webinarinc.com"> Webinarinc </a>
      </div>
      <div class="kt-footer__menu">
        <a
          href="https://webinarinc.com/training/"
          target="_blank"
          class="kt-footer__menu-link kt-link"
        >
          About
        </a>
        <a href="#" target="_blank" class="kt-footer__menu-link kt-link">
          Privacy
        </a>
        <a
          href="https://webinarinc.com/contact/"
          target="_blank"
          class="kt-footer__menu-link kt-link"
        >
          Contact Us
        </a>
      </div>
    </div>
  </div>
</template>
<!-- end:: Footer -->
<script>
export default {
  name: 'FooterBase',
};
</script>
