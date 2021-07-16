<template>
  <div>
    <file-manager></file-manager>
  </div>
</template>
<script>
import FileManager from '../components/client-filemanager/FileManager.vue';

export default {
  name: 'ClientLmsFileManager',
  components: {
    FileManager,
  },
};
</script>
