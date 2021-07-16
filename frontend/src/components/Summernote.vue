<template>
  <div id="editor"></div>
</template>

<script>
import Jodit from 'jodit';
import 'jodit/build/jodit.css';
export default {
  name: 'Summernote',
  props: ['getEditorContent'],
  data() {
    return {
      editor: '',
      editorContent: '',
    };
  },
  watch: {
    editorContent(val) {
      console.log(val);
      this.$emit('update:content', val);
    },
    getEditorContent(val) {
      this.editorContent = val;
    },
  },
  mounted() {
    this.editor = new Jodit('#editor');
    this.editorContent = this.getEditorContent;
    this.editor.value = this.editorContent;
  },
};
</script>
