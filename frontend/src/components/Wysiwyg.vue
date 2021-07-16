<template>
  <quill-editor
    :options="editorOption"
    :content="editorContent"
    v-model="editorContent"
  ></quill-editor>
</template>

<script>
import { quillEditor } from 'vue-quill-editor';
export default {
  name: 'Wysiywg',
  props: ['getEditorContent'],
  components: {
    quillEditor,
  },
  data() {
    return {
      editorOption: {},
      editorContent: '',
    };
  },
  watch: {
    editorContent(val) {
      this.$emit('update:content', val);
    },
    getEditorContent(val) {
      this.editorContent = val;
    },
  },
  created() {
    this.editorContent = this.getEditorContent;
  },
};
</script>

<style scoped></style>
