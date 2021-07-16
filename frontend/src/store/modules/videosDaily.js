const state = {
  videos: [],
  total: "",
  video: []
};

const getters = {
  getVideos: st => st.videos
};

const mutations = {
  setVideos: (st, items) => {
    // eslint-disable-next-line no-param-reassign
    st.videos = items.data;
    // eslint-disable-next-line no-param-reassign
    st.total = items.total;
  },
  addVideo: (st, data) => {
    st.videos.push(data);
  },
  setVideo: (st, item) => {
    // eslint-disable-next-line no-param-reassign
    st.video = Object.assign({}, item);
  },
  removeVideo: (st, index) => {
    st.video.splice(index, 1);
  }
};

const actions = {
  setList({ commit }, payload) {
    commit('setVideos', payload);
  },
  addVideo({ dispatch }, payload) {
    return this._vm.axios.post("/featured-videos", payload);
  },
  getVideo({ commit }, payload) {
    return this._vm.axios
      .get(`/featured-videos/${payload}`)
      .then(({ data }) => {
        commit("setVideo", data);
      })
      .catch(error => console.log(error));
  },
  updateVideo(context, payload) {
    return this._vm.axios.patch(`/featured-videos/${payload.id}`, payload);
  },
  // eslint-disable-next-line no-unused-vars
  deleteVideo(context, payload) {
    return this._vm.axios.delete(`/featured-videos/${payload}`);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
