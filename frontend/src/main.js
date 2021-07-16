import Vue from 'vue';
import vClickOutside from 'v-click-outside';
import VueAuth from '@d0whc3r/vue-auth-plugin';
import VueAxios from 'vue-axios';
import axios from 'axios';
import Datetime from 'vue-datetime';
import BootstrapVue from 'bootstrap-vue';
import VueSweetalert2 from 'vue-sweetalert2';
import VeeValidate from 'vee-validate';
import AudioRecorder from 'vue-audio-recorder';
import Trend from 'vuetrend';
import DatetimePicker from 'vuetify-datetime-picker';
import CKEditor from '@ckeditor/ckeditor5-vue';
import VueGoogleCharts from 'vue-google-charts';
import VueCarousel from 'vue-carousel';
import draggable from 'vuedraggable/src/vuedraggable';
import { flare } from '@flareapp/flare-client';
import { flareVue } from '@flareapp/flare-vue';
import vuetify from './plugins/vuetify';
import App from './App.vue';
import ComponentLoader from './components/partials/ComponentLoader.vue';
import router from './router';
import store from './store/index';
import RecordingField from './components/secretshop/RecordingField.vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'typeface-poppins/index.css';
import 'typeface-roboto/index.css';
import '@fortawesome/fontawesome-pro/css/all.css';
import 'vue-datetime/dist/vue-datetime.css';
import './assets/fonts/flaticon/flaticon.css';
import './assets/css/style.bundle.css';
import './assets/css/pages/login/login-2.css';
import './assets/css/pages/error/error-6.css';
import './assets/css/skins/aside/dark.css';
import './assets/css/skins/brand/dark.css';
import './assets/css/skins/header/base/light.css';
import './assets/css/skins/header/menu/light.css';
import './assets/fonts/metronic/css/styles.css';
import './assets/css/wizard-1.css';
import './assets/css/kt-spinner.css';
import './assets/css/animations/shake.css';
import 'sweetalert2/dist/sweetalert2.min.css';
import './assets/main.styl';
import Filters from './filters';
import 'noty/lib/noty.css';
import 'noty/lib/themes/metroui.css';
import 'vue-multiselect/dist/vue-multiselect.min.css';
// eslint-disable-next-line import/order
import Multiselect from 'vue-multiselect';
import Mixins from './mixins';
import VueTrumbowyg from 'vue-trumbowyg';
import 'trumbowyg/dist/ui/trumbowyg.css';
import VueLazyload from 'vue-lazyload';
import JoditVue from 'jodit-vue';
import Croppa from 'vue-croppa';
import AnswerField from './components/lms-admin/quiz-builder/AnswerField';
import 'jodit/build/jodit.min.css';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
import VueObserveVisibility from 'vue-observe-visibility';

import '@/assets/css/animate.min.css';

Vue.component('vue-select', vSelect);
Vue.use(JoditVue);
Vue.use(Croppa);
Vue.use(VueTrumbowyg);
Vue.use(require('vue-moment'));
Vue.use(VueObserveVisibility);

const instance = axios.create({
  baseURL: '/api',
  headers: {
    Accept: 'application/json',
    'Content-type': 'application/json',
  },
});

instance.CancelToken = axios.CancelToken;
instance.isCancel = axios.isCancel;

const auth = {
  tokenStore: ['localStorage', 'cookie'],
  loginData: {
    url: '/auth/login',
    method: 'POST',
    redirect: '/',
    headerToken: 'Authorization',
    fetchUser: true,
    customToken: (response) => response.data.access_token,
  },
  fetchData: {
    url: '/auth/me',
    method: 'POST',
    interval: 10,
    enabled: true,
  },
  refreshData: {
    url: '/auth/refresh',
    method: 'POST',
    interval: 5,
    enabled: false,
  },
  logoutData: {
    url: '/auth/logout',
    method: 'POST',
    redirect: '/login',
  },
};

if (process.env.NODE_ENV === 'production') {
  flare.light(process.env.VUE_APP_FLARE_KEY);
  Vue.use(flareVue);
}

Vue.use(Filters);
Vue.use(VueLazyload, {
  lazyComponent: true,
});
Vue.use(VueAxios, instance);
Vue.use(vClickOutside);
Vue.use(VueAuth, auth);
Vue.use(Datetime);
Vue.use(BootstrapVue);
Vue.use(VueSweetalert2);
Vue.use(VeeValidate, {
  classes: true,
  fieldsBagName: 'veeValidateFields',
  classNames: {
    valid: 'is-valid',
    invalid: 'is-invalid',
  },
});
Vue.use(AudioRecorder);
Vue.use(Trend);
Vue.use(DatetimePicker);
Vue.use(CKEditor);
Vue.use(VueGoogleCharts);
Vue.use(VueCarousel);
Vue.component('RecordingField', RecordingField);
Vue.component('ComponentLoader', ComponentLoader);
Vue.component('multiselect', Multiselect);
Vue.component('draggable', draggable);
Vue.component('AnswerField', AnswerField);
Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);
Vue.config.productionTip = false;

Vue.mixin(Mixins);

new Vue({
  router,
  store,
  vuetify,
  render: (h) => h(App),
}).$mount('#app');
