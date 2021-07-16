import {
  map as _map,
  uniq as _uniq,
  filter as _filter,
  includes as _includes,
  flatten as _flatten,
} from 'lodash-es';

const prefix = 'secret-shop-data-summary';

const state = {
  states: [],
  cities: [],
  zipCodes: [],
  cars: [],
  specificDealers: [],
  sources: [],
  salesPersons: [],
  csm: [],
  summaries: [],
  filters: [],
};

const getters = {
  getSpecificDealers: (state) => {
    if (state.filters.length) {
      const filters = _uniq(_map(state.filters, 'specific_dealer_id'));
      return _filter(state.specificDealers, (specificDealer) => {
        return _includes(filters, specificDealer.id);
      });
    }
    return state.specificDealers;
  },
  getStates: (state) => {
    if (state.filters.length) {
      const stateFilters = _uniq(_map(state.filters, 'state_id'));
      return _filter(state.states, (s) => {
        return _includes(stateFilters, s.id)
      });
    }
    return state.states;
  },
  getCities: (state) => {
    if (state.cities.length) {
      const citiesFilters = _uniq(_map(state.filters, 'city_id'));
      return _filter(state.cities, (s) => {
        return _includes(citiesFilters, s.id)
      });
    }
    return state.cities;
  },
  getMakes: (state) => {
    let makesList = _map(state.cars, ((car) => car.make));
    if (state.filters.length) {
      const makesFilter = _uniq(_map(state.filters, 'make'));
      makesList = _filter(makesList, (carMake) => {
        return _includes(makesFilter, carMake);
      });
    }
    return makesList;
  },
  getModels: (state) => {
    let modelsList = _flatten(_map(state.cars, ((car) => car.models)));
    if (state.filters.length) {
      const modelsFilter = _uniq(_map(state.filters, 'model'));
      modelsList = _filter(modelsList, (carModel) => {
        return _includes(modelsFilter, carModel);
      });
    }
    return modelsList;
  },
  getCSM: (state) =>  {
    if (state.filters.length) {
      const csmFilters = _uniq(_map(state.filters, 'csm_name'));
      return _filter(state.csm, (csm) => {
        return _includes(csmFilters, csm)
      });
    }
    return state.csm;
  },
  getSources: (state) => {
    if (state.filters.length) {
      const sourcesFilters = _uniq(_map(state.filters, 'lead_source'));
      return _filter(state.sources, (source) => {
        return _includes(sourcesFilters, source)
      })
    }
    return state.sources;
  }
};

const mutations = {
  setStates: (st, data) => {
    st.states = data;
  },
  setCities: (st, data) => {
    st.cities = data;
  },
  setZipCodes: (st, data) => {
    st.zipCodes = data;
  },
  setSpecificDealers: (st, data) => {
    st.specificDealers = data;
  },
  setSources: (st, data) => {
    st.sources = data;
  },
  setCars: (st, data) => {
    st.cars = data;
  },
  setSalesPersons: (st, data) => {
    st.salesPersons = data;
  },
  setCsmNames: (st, data) => {
    st.csm = data;
  },
  setSummaries: (st, data) => {
    st.summaries = data;
  },
  setFilters: (st, data) => {
    st.filters = data;
  }
};

const actions = {
  getStates() {
    return this._vm.axios.get(`${prefix}/states`);
  },
  getCities() {
    return this._vm.axios.get(`${prefix}/cities`);
  },
  getSpecificDealers(context, payload) {
    return this._vm.axios.get(`${prefix}/specific-dealers`, {
      params: payload
    });
  },
  getSalesPersons() {
    return this._vm.axios.get(`${prefix}/sales-persons`);
  },
  getCSM() {
    return this._vm.axios.get(`${prefix}/csm`);
  },
  getCarMakesAndModels() {
    return this._vm.axios.get(`${prefix}/car/makes-and-models`);
  },
  getSources() {
    return this._vm.axios.get(`${prefix}/sources`);
  },
  getFilters({ commit }, payload) {
    return this._vm.axios.get(`${prefix}/get-filtered-data`, {
      params: payload
    }).then(({ data }) => {
      commit('setFilters', data);
    })
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
