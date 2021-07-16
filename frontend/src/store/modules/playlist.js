import { findIndex as _findIndex } from 'lodash-es';

const state = {
  playlist: {
    name: '',
    user: {
      name: '',
    },
    description: '',
    units: [],
    progress: 0,
  },
};

const getters = {
};

const mutations = {
  setPlaylist(s, payload) {
    const st = s;
    st.playlist = payload;
  },
  setData(s, payload) {
    const st = s;
    st.playlist[payload.type] = payload.value;
  },
  toggleUnitDelete(s, index) {
    const st = s;
    st.playlist.units[index].checked = !st.playlist.units[index].checked;
  },
  deleteUnits(s, payload) {
    const st = s;
    payload.forEach((unit) => {
      const index = _findIndex(st.playlist.units, { id: unit.id });
      st.playlist.units.splice(index, 1);
    });
  },
};

const actions = {
  setPlaylist({ commit }, payload) {
    commit('setPlaylist', payload);
  },
  setData({ commit }, payload) {
    commit('setData', payload);
  },
  toggleUnitDelete({ commit }, index) {
    commit('toggleUnitDelete', index);
  },
  deleteUnits({ commit }, payload) {
    commit('deleteUnits', payload);
  },
  getPlaylists() {
    return this._vm.$http.get('/playlist');
  },
  fetchHomePlaylists() {
    return this._vm.$http.get('/client/lms/fetchPlaylists');
  },
  deletePlaylist(context, id) {
    return this._vm.$http.delete(`/playlist/${id}`);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
