const state = {
  noAccessDialog: false,
  noAccessType: '',
  noAccessPermissionDialog: false,
  menu: false,
};

const getters = {
};

const mutations = {
  toggleNoAccessDialog: (s) => {
    const st = s;
    st.noAccessDialog = !st.noAccessDialog;
  },
  setNoAccessType: (s, type) => {
    const st = s;
    st.noAccessType = type;
  },
  toggleNoAccessPermissionDialog: (s) => {
    const st = s;
    st.noAccessPermissionDialog = !st.noAccessPermissionDialog;
  },
  toggleMenu(s) {
    const st = s;
    st.menu = !st.menu;
  },
  setMenuState(s, value) {
    const st = s;
    st.menu = value;
  },
};

const actions = {
  toggleNoAccessDialog({ commit }) {
    commit('toggleNoAccessDialog');
  },
  setNoAccessType({ commit }, type) {
    commit('setNoAccessType', type);
  },
  toggleNoAccessPermissionDialog({ commit }) {
    commit('toggleNoAccessPermissionDialog');
  },
  toggleMenu({ commit }) {
    commit('toggleMenu');
  },
  setMenuState({ commit }, value) {
    commit('setMenuState', value);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
