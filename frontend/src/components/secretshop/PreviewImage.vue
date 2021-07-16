<template>
  <div>
    <draggable v-model="list" draggable>
      <div>asdasd</div>
    </draggable>
  </div>
</template>

<script>
import draggable from 'vuedraggable';
export default {
  components: {
    draggable,
  },
};
</script>

<style scoped></style>
