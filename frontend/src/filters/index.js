import formatDateToReadable from './format-display-date-time';

export default {
  install(Vue) {
    Vue.filter('displayDateTime', formatDateToReadable);
  },
};
