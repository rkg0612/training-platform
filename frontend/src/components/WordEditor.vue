<template>
  <ckeditor :editor="editor" v-model="editorData" :config="editorConfig">
  </ckeditor>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
import EssentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials';
import BoldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold';
import ItalicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic';
import LinkPlugin from '@ckeditor/ckeditor5-link/src/link';
import ParagraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
import MediaEmbed from '@ckeditor/ckeditor5-media-embed/src/mediaembed';
import Table from '@ckeditor/ckeditor5-table/src/table';
import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar';
import Font from '@ckeditor/ckeditor5-font/src/font';
import Heading from '@ckeditor/ckeditor5-heading/src/heading';
import Image from '@ckeditor/ckeditor5-image/src/image';
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption';
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle';
import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize';
import EasyImage from '@ckeditor/ckeditor5-easy-image/src/easyimage';

export default {
  name: 'WordEditor',
  props: ['getMyData'],
  data() {
    return {
      editor: ClassicEditor,
      editorData: '',
      editorConfig: {
        plugins: [
          EssentialsPlugin,
          BoldPlugin,
          ItalicPlugin,
          LinkPlugin,
          ParagraphPlugin,
          Alignment,
          MediaEmbed,
          Table,
          TableToolbar,
          Font,
          Heading,
          EasyImage,
          Image,
          ImageToolbar,
          ImageCaption,
          ImageStyle,
          ImageResize,
        ],

        fontFamily: {
          options: [
            'default',
            'Arial, Helvetica, sans-serif',
            'Courier New, Courier, monospace',
            'Georgia, serif',
            'Lucida Sans Unicode, Lucida Grande, sans-serif',
            'Tahoma, Geneva, sans-serif',
            'Times New Roman, Times, serif',
            'Trebuchet MS, Helvetica, sans-serif',
            'Verdana, Geneva, sans-serif',
          ],
        },

        toolbar: [
          'heading',
          'fontSize',
          'fontFamily',
          'fontColor',
          'fontBackgroundColor',
          'bold',
          'italic',
          'link',
          'undo',
          'redo',
          'alignment',
          'mediaEmbed',
          'insertTable',
          'imageUpload',
          'imageTextAlternative',
          '|',
          'imageStyle:full',
          'imageStyle:side',
        ],
        table: {
          contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'],
        },
      },
    };
  },
  watch: {
    editorData(val) {
      this.$emit('update:secondValue', val);
    },
    getMyData(val) {
      this.editorData = val;
    },
  },
  created() {
    this.editorData = this.getMyData;
  },
};
</script>
<style lang="stylus" scoped>
.ck.ck-editor__main>.ck-editor__editable:not(.ck-focused)
  min-height 250px
</style>
