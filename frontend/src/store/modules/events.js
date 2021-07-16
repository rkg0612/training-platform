import Vue from 'vue';
import {
  findIndex as _findIndex,
} from 'lodash-es';

const state = {
  events: [],
};

const getters = {
  getEvents: st => st.states,
};

const mutations = {
  setEvents: (st, data) => {
    st.events = data;
  },
  addEvent: (st, data) => {
    st.events.push(data);
  },
  updateEvent: (st, payload) => {
    const index = _findIndex(st.events, { id: payload.id });

    Vue.set(st.events, index, payload);
  },
};

const actions = {
  getEvents({ commit }, payload) {
    return this._vm.axios
      .get(`/events?year=${payload.year}&month=${payload.month}`)
      .then(({ data }) => {
        commit('setEvents', data);
      });
  },
  // eslint-disable-next-line no-unused-vars
  addEvent({ commit }, payload) {
    commit('addEvent', payload);
  },
  // eslint-disable-next-line no-unused-vars
  updateEvent({ commit }, payload) {
    commit('updateEvent', payload);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
