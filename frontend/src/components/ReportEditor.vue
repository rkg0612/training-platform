<template>
  <div>
    <trumbowyg
      v-model="content"
      :config="config"
      class="form-control"
      name="content"
      @tbw-modalopen="modalOpen"
    ></trumbowyg>
  </div>
</template>

<script type="text/javascript">
// Import editor css

export default {
  name: 'ReportEditor',
  data() {
    return {
      content: '<p>test</p>',
      config: {
        btns: [
          ['viewHTML'],
          ['undo', 'redo'], // Only supported in Blink browsers
          ['formatting'],
          ['strong', 'em', 'del'],
          ['superscript', 'subscript'],
          ['link'],
          ['insertImage'],
          ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
          ['unorderedList', 'orderedList'],
          ['horizontalRule'],
          ['removeformat'],
          ['fullscreen'],
        ],
      },
    };
  },
};
</script>
