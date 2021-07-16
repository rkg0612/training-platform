const state = {
  type: '',
  playlist_id: '',
  current_unit: {
    id: '',
    isCompleted: false,
  },
};

const mutations = {
  setType: (st, data) => {
    st.type = data;
  },
  setPlaylistId: (st, data) => {
    st.playlist_id = data;
  },
  setCurrentUnitId: (st, data) => {
    st.current_unit.id = data;
  },
  setIsCompleted: (st, data) => {
    st.current_unit.isCompleted = data;
  }
};

const actions = {
  markAsCompleted(context, payload) {
    return this._vm.axios.post('mark-as-completed', payload);
  },
  isUnitCompleted(context, payload) {
    return this._vm.axios.post('mark-as-completed/is-completed', payload);
  },
  determinePlaylistType(context, payload) {
    return this._vm.axios.post('mark-as-completed/determine-playlist-type', payload);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
