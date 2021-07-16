const path = require('path');
const CKEditorWebpackPlugin = require('@ckeditor/ckeditor5-dev-webpack-plugin');
const { styles } = require('@ckeditor/ckeditor5-dev-utils');
const webpack = require('webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const publicDir = '../public';

module.exports = {
  // proxy API requests to Valet during development
  devServer: {
    proxy: {
      '^/api': {
        target: process.env.BASE_URL,
        changeOrigin: true,
      },
      '^/broadcasting/': {
        target: process.env.BASE_URL,
        changeOrigin: true,
      },
    },
  },

  outputDir: publicDir,

  indexPath:
    process.env.NODE_ENV === 'production'
      ? '../resources/views/index.blade.php'
      : 'index.html',

  transpileDependencies: [
    /ckeditor5-[^/\\]+[/\\]src[/\\].+\.js$/,
    'vuetify',
    'luxon',
  ],

  configureWebpack: {
    plugins: [
      // CKEditor needs its own plugin to be built using webpack.
      new CKEditorWebpackPlugin({
        // See https://ckeditor.com/docs/ckeditor5/latest/features/ui-language.html
        language: 'en',
      }),
      new webpack.ProvidePlugin({
        $: 'jquery',
        jquery: 'jquery',
        'window.jQuery': 'jquery',
        jQuery: 'jquery',
        'window.Quill': 'quill/dist/quill.js',
        Quill: 'quill/dist/quill.js',
      }),
      new CopyWebpackPlugin({
        patterns: [
          {
            from: path.resolve(
              __dirname,
              'node_modules/outdated-browser-rework/dist/outdated-browser-rework.min.js'
            ),
            to: process.env.NODE_ENV === 'production' ? publicDir : './',
          },
          {
            from: path.resolve(
              __dirname,
              'node_modules/outdated-browser-rework/dist/style.css'
            ),
            to: `${
              process.env.NODE_ENV === 'production' ? `${publicDir}/` : './'
            }outdatedbrowser.min.css`,
          },
        ],
      }),
    ],
  },

  chainWebpack: (config) => {
    const svgRule = config.module.rule('svg');

    svgRule.exclude.add(path.join(__dirname, 'node_modules', '@ckeditor'));

    config.module
      .rule('cke-svg')
      .test(/ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/)
      .use('raw-loader')
      .loader('raw-loader');

    // (2.) Transpile the .css files imported by the editor using PostCSS.
    // Make sure only the CSS belonging to ckeditor5-* packages is processed this way.
    config.module
      .rule('cke-css')
      .test(/ckeditor5-[^/\\]+[/\\].+\.css$/)
      .use('postcss-loader')
      .loader('postcss-loader')
      .tap(() =>
        styles.getPostCssConfig({
          themeImporter: {
            themePath: require.resolve('@ckeditor/ckeditor5-theme-lark'),
          },
          minify: true,
        })
      );
  },
};
