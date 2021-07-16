<template>
  <div>
    <dealer-base />
    <user-base />
    <group-user />
  </div>
</template>
<script>
import DealerBase from '../components/user-management/dealer/DealerBase.vue';
import UserBase from '../components/user-management/user/UserBase.vue';
import GroupUser from '../components/user-management/user/GroupUser.vue';

export default {
  name: 'UserManagement',
  components: {
    DealerBase,
    UserBase,
    GroupUser,
  },
};
</script>
