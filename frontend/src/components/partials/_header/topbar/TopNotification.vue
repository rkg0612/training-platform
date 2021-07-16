<template>
  <div
    class="kt-header__topbar-item dropdown"
    :class="{ show: isNotificationExpanded }"
    v-click-outside="hideDropDown"
  >
    <div
      class="kt-header__topbar-wrapper"
      @click="isNotificationExpanded = !isNotificationExpanded"
      ref="notificationBell"
    >
      <span class="kt-header__topbar-icon shake">
        <i class="fal fa-bells kt-svg-icon" id="notification-icon"></i>
      </span>
    </div>
    <div
      class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg"
      id="dropNotification"
      :class="{ show: isNotificationExpanded }"
    >
      <form>
        <drop-notification />
      </form>
    </div>
  </div>
</template>
<script>
import DropNotification from '../../_topbar/dropdown/DropNotification.vue';

export default {
  name: 'TopNotification',
  components: {
    DropNotification,
  },
  data() {
    return {
      isNotificationExpanded: false,
    };
  },
  mounted() {
    this.$echo
      .private(`user-notification-${this.$auth.user().id}`)
      .notification((notification) => {
        this.$store.commit('notifications/pushNotification', {
          data: notification,
        });
        this.$notify('info', 'Incoming notification!');
      });
  },
  methods: {
    hideDropDown() {
      this.isNotificationExpanded = false;
    },
  },
};
</script>
<style lang="stylus">
#dropNotification
  left -175px
</style>
