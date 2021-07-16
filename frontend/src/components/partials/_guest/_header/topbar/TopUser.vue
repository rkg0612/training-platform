<!--begin: User Bar -->
<template>
  <div
    class="kt-header__topbar-item kt-header__topbar-item--user"
    id="dropdown-position"
  >
    <div
      class="kt-header__topbar-wrapper"
      :class="{ show: isUserDropdownExpanded }"
      v-click-outside="hideDropDown"
    >
      <div
        class="kt-header__topbar-user"
        @click="isUserDropdownExpanded = !isUserDropdownExpanded"
      >
        <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
        <span class="kt-header__topbar-username kt-hidden-mobile">User</span>
        <!--TODO: if user has pic, use img for user thumbnail
        if user doesn't have pic, use the initial badge-->
        <img class="" alt="Pic" :src="displayProfilePicture" />

        <!--use below badge element instead the user avatar to display
        username's first letter(remove kt-hidden class to display it) -->

        <!-- <span class="kt-badge kt-badge--username
          kt-badge--unified-dark kt-badge--lg
          kt-badge--rounded kt-badge--bold">S
        </span> -->
      </div>
    </div>
    <div
      class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl"
      :class="{ show: isUserDropdownExpanded }"
      id="dropUser-wrapper"
    >
      <!--user dropdown-->
      <drop-user></drop-user>
    </div>
  </div>
</template>
<script>
import DropUser from '../../_topbar/dropdown/DropUser.vue';

export default {
  name: 'TopUser',
  components: {
    DropUser,
  },
  data() {
    return {
      isUserDropdownExpanded: false,
    };
  },
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
    hideDropDown() {
      this.isUserDropdownExpanded = false;
    },
  },
};
</script>
<style lang="stylus">
#dropdown-position
  position relative

#dropUser-wrapper
  position absolute
  left -180px
  width 350px
</style>
