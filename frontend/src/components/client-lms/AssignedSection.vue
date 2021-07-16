<template>
  <div class="kt-portlet kt-portlet--head-lg k-portlet--height-fluid">
    <div class="kt-portlet__body text-center">
      <h3 class="playlist-heading">
        <i class="fal fa-tasks m-1"></i>
        Assigned and Shared Units
      </h3>
      <p class="text-center">
        List of units assigned to you and shared to you.
      </p>
      <v-divider></v-divider>
      <h5 class="text-center" v-if="assignedUnits.length < 1">
        You currently don't have any units assigned to you.
      </h5>
      <div class="custome-break-point d-flex flex-wrap">
        <div
          class="col-llg-4 col-md-6"
          v-for="unit in assignedUnits"
          :key="unit.id"
        >
          <unit-card :unit="unit"></unit-card>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import UnitCard from './UnitCard.vue';

export default {
  name: 'AssignedSection',
  components: {
    UnitCard,
  },

  computed: {
    assignedUnits() {
      return this.$store.state.lmsClient.assignedUnits;
    },
  },

  created() {
    this.$store
      .dispatch('lmsClient/getAssignedUnits')
      .then(({ data }) => {
        this.$store.dispatch('lmsClient/setAssignedUnits', data);
      })
      .catch(() => {
        console.log(
          'Encountered an error while loading your Assigned Units. Please try again'
        );
      });
  },
};
</script>
