<template>
  <div>
    <top-widget class="d-none d-sm-flex"></top-widget>
    <lms-widget></lms-widget>
    <!-- <secret-shop></secret-shop> -->
    <user-base></user-base>
    <group-user />
    <!-- <group-user></group-user> -->
    <webinar-cal id="event-calendar"></webinar-cal>
  </div>
</template>
<script>
import TopWidget from '../components/client-home/TopWidget.vue';
import LmsWidget from '../components/client-home/LmsWidget.vue';
import SecretShop from '../components/client-home/SecretShop.vue';
import UserBase from '../components/user-management/user/UserBase.vue';
import GroupUser from '../components/user-management/user/GroupUser.vue';
import WebinarCal from '../components/dashboard/WebinarCal.vue';

export default {
  name: 'ClientHome',
  components: {
    TopWidget,
    LmsWidget,
    SecretShop,
    UserBase,
    GroupUser,
    WebinarCal,
  },
};
</script>
<style lang="stylus" scoped></style>
