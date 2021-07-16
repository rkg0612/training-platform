<template>
  <div class="kt-header__topbar">
    <search-dialog />
    <top-notification />
    <top-user />
  </div>
</template>
<script>
import SearchDialog from '@/components/partials/_header/SearchDialog.vue';
import TopUser from './TopUser.vue';
import TopNotification from './TopNotification.vue';

export default {
  name: 'TopBase',
  components: {
    SearchDialog,
    TopUser,
    TopNotification,
  },
  created() {
    this.setProfile(this.$auth.user());
  },
  methods: {
    setProfile(profile) {
      this.$store.commit('profile/setProfile', {
        id: profile.id,
        name: profile.name,
        email: profile.email,
        profilePicture: profile.profile_picture,
        jobTitle: profile.job_title,
        phone: profile.phone_number,
      });
      this.$store.commit('profile/setCompany', profile.dealer);
    },
  },
};
</script>
