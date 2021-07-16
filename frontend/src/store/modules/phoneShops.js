import { forEach as _forEach, findIndex as _findIndex } from 'lodash-es';

const state = {
  phoneShopList: [],
  selectedItem: {},
  competitorRecordings: [],
  dealerRecordings: [],
};

const getters = {
  getPhoneShops: st => st.phoneShop
};

const mutations = {
  setPhoneShops: (st, data) => {
    st.phoneShopList = data;
  },
  setSelectedItem: (st, data) => {
    st.selectedItem = data;
  },
  setDealerRecordings: (st, recordings) => {
    _forEach(recordings, (recording) => {
      st.dealerRecordings.push({
        file: recording.file,
        name: recording.name
      });
    });
  },
  setCompetitorRecordings: (st, recordings) => {
    _forEach(recordings,(recording) => {
      st.competitorRecordings.push({
        file: recording.file,
        name: recording.name
      })
    });
  },
  removeRecording: (st, { recording, type }) => {
    if (type === 'dealerRecordings') {
      const index = _findIndex(st.dealerRecordings, { file: recording.file });
      st.dealerRecordings.splice(index, 1);
    }
    if (type === 'competitorRecordings') {
      const index = _findIndex(st.competitorRecordings, { file: recording.file });
      st.competitorRecordings.splice(index, 1);
    }
  },
  resetRecordings(st) {
    st.competitorRecordings = [];
    st.dealerRecordings = [];
  },
};

const actions = {
  removeRecording({ commit }, payload) {
    commit('removeRecording', payload);
  },
  setRecordings({ commit }, { recordings, type}) {
    if (type === 'dealer') {
      return commit('setDealerRecordings', recordings);
    }
    return commit('setCompetitorRecordings', recordings);
  },
  resetRecordings({ commit }) {
    commit('resetRecordings');
  },
  updatePhoneShop(context, { id, payload }) {
    return this._vm.axios.post(`/secret-shop-management/phone-shops/${id}`, payload, {
      headers: {
        'Content-type': 'multipart/form-data',
      }
    })
  },
  deletePhoneShop(context, id) {
    return this._vm.axios.delete(`/secret-shop-management/phone-shops/${id}`);
  },
  getPhoneShop(context, id) {
    return this._vm.axios.get(`/secret-shop-management/phone-shops/${id}`);
  },
  restorePhoneShop(context, id) {
    return this._vm.axios.get(`/secret-shop-management/phone-shops/restore/${id}`);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
