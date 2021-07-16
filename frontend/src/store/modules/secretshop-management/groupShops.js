import {
  forEach as _forEach,
  findIndex as _findIndex,
} from 'lodash-es';


const state = {
  internetShops: [],
  phoneShops: [],
  dealers: [],
  list: [],
  selectedGroupShop: {},
};

const mutations = {
  setPhoneShops(st, payload) {
    const shops = st.phoneShops;
    const index = _findIndex(shops, { id: payload.id });
    if (index === -1) {
      shops.push(payload);
    }
    st.phoneShops = shops;
  },
  setInternetShops(st, payload) {
    const shops = st.internetShops;
    const index = _findIndex(shops, { id: payload.id });
    if (index === -1) {
      shops.push(payload);
    }
    st.internetShops = shops;
  },
  setList(st, payload) {
    st.list = payload;
  },
  setSelectedGroupShop(st, payload) {
    st.selectedGroupShop = payload;
  },
};

const actions = {
  setPhoneShops({ commit }, payload) {
    _forEach(payload, (shop) => {
      commit('setPhoneShops', shop);
    });
  },
  setInternetShops({ commit }, payload) {
    _forEach(payload, (shop) => {
      commit('setInternetShops', shop);
    });
  },
  setList({ commit }, payload) {
    commit('setList', payload);
  },
  setSelectedGroupShop({ commit }, payload) {
    commit('setSelectedGroupShop', payload);
  },
};

const getters = {};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
