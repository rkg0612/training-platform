<template>
  <div>
    <!--begin: Head -->
    <div
      class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x"
      id="dropUser-bg"
    >
      <div class="kt-user-card__avatar">
        <!--TODO: if user has pic, use img for user thumbnail
        if user doesn't have pic, use the initial badge-->
        <img class="" alt="Pic" :src="displayProfilePicture" />
        <!--use below badge element instead the user avatar to
        display username's first letter(remove kt-hidden class to display it) -->

        <!-- <span class="kt-badge kt-badge--lg
          kt-badge--rounded kt-badge--bold
          kt-font-dark">S
        </span> -->
      </div>
      <div class="kt-user-card__name" @click="onClickButton()">
        {{ currentProfile.name }}
      </div>
    </div>
    <!--end: Head -->

    <!--begin: Navigation -->
    <div class="kt-notification">
      <router-link to="/client/profile" class="kt-notification__item">
        <div class="kt-notification__item-icon">
          <i class="fal fa-id-badge kt-font-dark"></i>
        </div>
        <div class="kt-notification__item-details">
          <div class="kt-notification__item-title kt-font-bold">My Profile</div>
          <div class="kt-notification__item-time">
            Account settings and more
          </div>
        </div>
      </router-link>
      <div class="kt-notification__custom kt-space-between">
        <a
          href="#"
          class="btn btn-label btn-label-dark btn-sm btn-bold"
          @click="logout"
        >
          Sign Out
        </a>
      </div>
    </div>
    <!--end: Navigation -->
  </div>
</template>
<script>
export default {
  name: 'DropUser',
  computed: {
    displayProfilePicture() {
      return this.currentProfile.profilePicture
        ? `https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/users/${this.currentProfile.id}/${this.currentProfile.profilePicture}`
        : 'https://webinarinc-v2-development.s3-us-west-2.amazonaws.com/default/default_profile_picture.jpg';
    },
    currentProfile() {
      return this.$store.state.profile.value;
    },
  },
  methods: {
    logout() {
      this.$store.dispatch('lmsClient/deleteCategories');
      this.$auth.logout();
    },
  },
};
</script>
<style lang="stylus">
#dropUser-bg
  background-image url('../../../../assets/images/user-bg.jpg')
</style>
