<template>
  <router-view :key="$route.fullPath"></router-view>
</template>

<script>
import Vue from 'vue';
import dayjs from 'dayjs';
import Noty from 'noty';
import Echo from 'laravel-echo';
import { filter as _filter, head as _head } from 'lodash-es';

window.Pusher = require('pusher-js');
const loginpath = window.location.pathname;

export default {
  name: 'App',
  methods: {
    notify(type, text, killer = false) {
      new Noty({
        type,
        layout: 'topRight',
        theme: 'metroui',
        text,
        timeout: 2000,
        progressBar: true,
        closeWith: ['click'],
        queue: 'global',
        killer,
        visibilityControl: true,
      }).show();
    },
    setOptions(email) {
      this.$store
        .dispatch('dealerOptions/getOptions', email)
        .then(({ data }) => {
          this.$store.commit('dealerOptions/setOptions', data);
        });
    },
    getOption(option) {
      const setting = _head(
        _filter(this.$store.state.dealerOptions.options, {
          name: option,
        })
      );
      if (!setting) return null;
      return setting.value;
    },
    loadVimeoJs() {
      let recaptchaScript = document.createElement('script');
      recaptchaScript.setAttribute(
        'src',
        'https://player.vimeo.com/api/player.js'
      );
      document.head.appendChild(recaptchaScript);
    },
  },
  computed: {
    currentDate() {
      return dayjs().format();
    },
    user() {
      return this.$auth.user();
    },
  },
  created() {
    // LOAD VIMEO JS
    this.loadVimeoJs();
    if (this.$auth.check()) {
      this.$http.defaults.headers.common.Authorization = `Bearer ${this.$auth.token()}`;
      this.setOptions(this.user.email);
    }

    Vue.prototype.$echo = new Echo({
      broadcaster: 'pusher',
      key: '18333852b6596d577f43',
      cluster: 'us3',
      forceTLS: true,
      authEndpoint: '/broadcasting/auth',
      encrypted: true,
      auth: {
        headers: {
          Authorization: `Bearer ${this.$auth.token()}`,
        },
      },
    });

    Vue.prototype.$notify = this.notify;
    Vue.prototype.$currentDate = this.currentDate;
    Vue.prototype.$getSetting = this.getOption;

    setTimeout(() => {
      this.$auth.fetchUser();
    }, 60000);
  },
};
</script>
<style lang="stylus">
.v-select {
  margin-top: 4px;
  padding-top: 12px;
}

.v-select .vs__search::placeholder,
.v-select .vs__dropdown-toggle,
.v-select .vs__dropdown-menu {
  border-radius: 0;
  border: none;
  border-bottom: 1px #888888 solid;
  font-size: 16px;
}
</style>
