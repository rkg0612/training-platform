import { findIndex as _findIndex } from 'lodash-es';

const state = {
  type: '',
  playlist: {
    title: '',
    units: []
  },
  singleUnit: {
    series: [
      {
        units: null
      }
    ]
  },
  isLoaded: false,
};

const mutations = {
  setSingleUnit(st, payload) {
    st.singleUnit = payload
  },
  setPlaylist(st, payload) {
    st.playlist = payload;
  },
  setType(st, payload) {
    st.type = payload;
  },
  setIsLoaded(st, payload) {
    st.isLoaded = payload;
  },
  setIsCompletedToTrue(st, payload) {
    if (st.type === 'playlist') {
      const index = _findIndex(st.playlist.units, { id: payload });

      return st.playlist.units[index].is_completed = 1;
    }
    else {
      let seriesIndex = '';
      let unitIndex = '';

      for (let seriesUnits = 0; seriesUnits <= st.singleUnit.series.length - 1; seriesUnits++) {
        const index = _findIndex(st.singleUnit.series[seriesUnits].units, { id: payload });
        if (index !== -1) {
          seriesIndex = seriesUnits;
          unitIndex = index;
          break;
        }
      }

      return st.singleUnit.series[seriesIndex].units[unitIndex].is_completed = 1;
    }
  },
};

const getters = {
  getNextUnitInPlaylist: (state) => (unitId) => {
    const index = _findIndex(state.playlist.units, { id: Number(unitId) });
    if (index >= state.playlist.units.length) {
      return null;
    }
    return state.playlist.units[index+1].id;
  },
  getPrevUnitInPlaylist: (state) => (unitId) => {
    const index = _findIndex(state.playlist.units, { id: Number(unitId) });
    if (index === 0) {
      return null;
    }
    return state.playlist.units[index-1].id;
  },
  checkIfCompletedAlready: (state) => (unitId) => {
    console.log(state.singleUnit.series);
  },
};

const actions = {
  fetchRelatedUnits(context, payload) {
    return this._vm.axios.get(`/client/lms/related-units?type=${payload.type}&playlistId=${payload.playlistId}`);
  },
  fetchSingleRelatedUnits(context, payload) {
    return this._vm.axios.get(`/client/lms/related-units?type=${payload.type}&unitId=${payload.unitId}`);
  },
};


export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
