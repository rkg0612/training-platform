import {
  findIndex as _findIndex,
  find as _find,
} from 'lodash-es';

const state = {
  playlists: [],
  units: {
    favorites: [],
    assigned: [],
  },
  courseLibrary: [],
  courseLibraryHome: [],
  assignedUnits: [],
  listUsers: [],
  categories: [],
  categoriesLoading: true,
};

const getters = {
};

const mutations = {
  setLibrary(s, payload) {
    const st = s;
    st.courseLibrary = payload;
  },
  setLibraryHome(s, payload) {
    const st = s;
    st.courseLibraryHome = payload;
  },
  addLibraryHome(s, payload) {
    const st = s;
    st.courseLibraryHome.push(payload);
  },
  setAssignedUnits(s, payload) {
    const st = s;
    st.assignedUnits = payload;
  },
  setUsers(s, payload) {
    const st = s;
    st.listUsers = payload;
  },
  setPlaylists(s, payload) {
    const st = s;
    st.playlists = payload;
  },
  addPlaylist(s, payload) {
    const st = s;
    st.playlists.push(payload);
  },
  deletePlaylist(s, id) {
    const st = s;
    const index = _findIndex(st.playlists, { id });
    st.playlists.splice(index, 1);
  },
  addUnit(s, payload) {
    const st = s;
    const index = _findIndex(st.playlists, { id: payload.id });
    const checkDuplicate = _find(st.playlists[index].units, { id: payload.unit.id });

    // skip if unit is already in the playlist
    if (checkDuplicate !== undefined) {
      return;
    }

    st.playlists[index].units.push(payload.unit);
  },
  setFavorites(s, payload) {
    const st = s;
    st.units.favorites = payload;
  },
  unFavorite(s, id) {
    const st = s;
    const index = _findIndex(st.units.favorites, { id });
    st.units.favorites.splice(index, 1);
  },
  addFavorite(s, unit) {
    const st = s;
    st.units.favorites.push(unit);
  },
  setCategories(s, payload) {
    const st = s;
    st.categories = payload;
    st.categoriesLoading = false;
  },
  deleteCategories(s) {
    const st = s;
    st.categories = [];
  },
};

const actions = {
  setLibrary({ commit }, payload) {
    commit('setLibrary', payload);
  },
  setLibraryHome({ commit }, payload) {
    commit('setLibraryHome', payload);
  },
  addLibraryHome({ commit }, payload) {
    commit('addLibraryHome', payload);
  },
  setAssignedUnits({ commit }, payload) {
    commit('setAssignedUnits', payload);
  },
  setUsers({ commit }, payload) {
    commit('setUsers', payload);
  },
  setPlaylists({ commit }, payload) {
    commit('setPlaylists', payload);
  },
  addPlaylist({ commit }, payload) {
    commit('addPlaylist', payload);
  },
  deletePlaylist({ commit }, id) {
    commit('deletePlaylist', id);
  },
  addUnit({ commit }, payload) {
    commit('addUnit', payload);
  },
  setFavorites({ commit }, payload) {
    commit('setFavorites', payload);
  },
  unFavorite({ commit }, id) {
    commit('unFavorite', id);
  },
  addFavorite({ commit }, unit) {
    commit('addFavorite', unit);
  },
  getLibraryHome(context, payload) {
    return this._vm.$http.get('/client/lms/fetchCourseLibraryHome', {
      params: payload,
    });
  },
  getAssignedUnits() {
    return this._vm.$http.get(`/client/lms/fetchAssignedUnits`);
  },
  fetchUsersByDealer() {
    return this._vm.$http.get(`/client/lms/fetchUsersByDealer`);
  },
  assignUnit(context, payload) {
    return this._vm.$http.post(`/client/lms/assignUnit`, payload);
  },
  assignModule(context, payload) {
    return this._vm.$http.post(`/client/lms/assignModule`, payload);
  },
  getInProgress() {
    return this._vm.$http.get(`/client/lms/fetchInProgressModule`);
  },
  getLatestAssigned() {
  return this._vm.$http.get(`/client/lms/fetchLatestAssignedModule`);
  },
  setCategories({ commit }, payload) {
    commit('setCategories', payload);
  },
  deleteCategories({ commit }) {
    commit('deleteCategories');
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
