import {
  isArray as _isArray,
  forEach as _forEach,
  filter as _filter,
  find as _find,
  head as _head,
  flatten as _flatten,
  includes as _includes,
} from 'lodash-es';

export default {
  data: () => ({
    defaultForm: {
      start_at: ['', ''],
      city_id: '',
      state_id: '',
      zipCode: '',
      is_shop_new: '',
      make: '',
      model: '',
      specific_dealer_id: '',
      lead_source: '',
      source: '',
      salesperson_name: '',
      csm_name: '',
    },
    loading: {
      fetchStates: false,
      fetchCities: false,
      fetchSpecificDealers: false,
      fetchSalesPersons: false,
      fetchSummaries: false,
      fetchSources: false,
      fetchCSM: false,
      fetchCars: false,
    },
    disabledStates: {
      all: false,
    },
  }),
  computed: {
    states() {
      if (this.form.city_id) {
        this.form.state_id = _find(this.$store.state.secretShopSummary.cities, {
          id: this.form.city_id,
        }).state_id;
      }
      return this.$store.getters['secretShopSummary/getStates'];
    },
    cities() {
      if (this.form.state_id) {
        return _filter(this.$store.state.secretShopSummary.cities, {
          state_id: this.form.state_id,
        });
      }
      if (this.form.state_id === '') {
        return this.$store.state.secretShopSummary.cities;
      }
      return this.$store.getters['secretShopSummary/getCities'];
    },
    zipCodes() {
      return this.$store.state.secretShopSummary.zipCodes;
    },
    makes() {
      if (this.form.model) {
        const model = this.form.model;
        const cars = _filter(
          this.$store.state.secretShopSummary.cars,
          function (car) {
            return _includes(car.models, model);
          }
        );

        this.form.make = _head(cars).make;
      }
      return this.$store.getters['secretShopSummary/getMakes'];
    },
    models() {
      if (this.form.make) {
        let car = _filter(this.$store.state.secretShopSummary.cars, {
          make: this.form.make,
        });
        car = _head(car);

        return _flatten(car.models);
      }
      return this.$store.getters['secretShopSummary/getModels'];
    },
    specificDealers() {
      return this.$store.getters['secretShopSummary/getSpecificDealers'];
    },
    salesPersons() {
      return this.$store.state.secretShopSummary.salesPersons;
    },
    sources() {
      return this.$store.getters['secretShopSummary/getSources'];
    },
    summaries() {
      return this.$store.state.secretShopSummary.summaries;
    },
    dateRange() {
      return this.formatDateRangeForTextField(this.form.start_at);
    },
    csm() {
      return this.$store.getters['secretShopSummary/getCSM'];
    },
  },
  methods: {
    getSpecificDealers(specificDealersOnly = false) {
      if (specificDealersOnly) {
        return this.$store.dispatch(
          'secretShopSummary/getFilters',
          this.createParams('specific_dealer_id')
        );
      }
      this.loading.fetchSpecificDealers = true;
      this.loading.fetchSummaries = true;
      this.$store
        .dispatch('secretShopSummary/getSpecificDealers')
        .then(({ data }) => {
          this.$store.commit('secretShopSummary/setSpecificDealers', data);
          this.loading.fetchSpecificDealers = false;
          this.loading.fetchSummaries = false;
        });
    },
    getCities() {
      this.loading.fetchCities = true;
      this.loading.fetchSummaries = true;
      this.$store.dispatch('secretShopSummary/getCities').then(({ data }) => {
        this.$store.commit('secretShopSummary/setCities', data);
        this.loading.fetchSummaries = false;
        this.loading.fetchCities = false;
      });
    },
    getStates() {
      this.loading.fetchStates = true;
      this.loading.fetchSummaries = true;
      this.$store.dispatch('secretShopSummary/getStates').then(({ data }) => {
        this.$store.commit('secretShopSummary/setStates', data);
        this.loading.fetchStates = false;
        this.loading.fetchSummaries = false;
      });
    },
    getSalesPersons() {
      this.loading.fetchSummaries = true;
      this.loading.fetchSalesPersons = true;
      this.$store
        .dispatch('secretShopSummary/getSalesPersons')
        .then(({ data }) => {
          this.$store.commit('secretShopSummary/setSalesPersons', data);
          this.loading.fetchSalesPersons = false;
          this.loading.fetchSummaries = false;
        });
    },
    getSources() {
      this.loading.fetchSources = true;
      this.loading.fetchSummaries = true;
      this.$store.dispatch('secretShopSummary/getSources').then(({ data }) => {
        this.$store.commit('secretShopSummary/setSources', data);
        this.loading.fetchSources = false;
        this.loading.fetchSummaries = false;
      });
    },
    getCSM() {
      this.loading.fetchCSM = true;
      this.loading.fetchSummaries = true;
      this.$store.dispatch('secretShopSummary/getCSM').then(({ data }) => {
        this.$store.commit('secretShopSummary/setCsmNames', data);
        this.loading.fetchCSM = false;
        this.loading.fetchSummaries = false;
      });
    },

    getCarMakesAndModels() {
      this.loading.fetchSummaries = true;
      this.loading.fetchCars = true;
      this.$store
        .dispatch('secretShopSummary/getCarMakesAndModels')
        .then(({ data }) => {
          this.$store.commit('secretShopSummary/setCars', data);
          this.loading.fetchSummaries = false;
          this.loading.fetchCars = false;
        });
    },
    createParams(excludeColumns = []) {
      const params = {};
      Object.entries(this.form).forEach(([column, value]) => {
        if (_isArray(value)) {
          value = value.filter(function (str) {
            return /\S/.test(str);
          });
        }
        if (value) {
          if (_isArray(value) && !value.length) return;
          if (excludeColumns.length && excludeColumns.includes(column)) return;
          params[column] = value;
        }
      });
      return params;
    },
    getFilters() {
      this.disabledStates.all = true;
      this.loading.fetchSummaries = true;
      setTimeout(() => {
        this.$store
          .dispatch('secretShopSummary/getFilters', this.createParams())
          .finally(() => {
            this.disabledStates.all = false;
            this.loading.fetchSummaries = false;
          });
      }, 1000);
    },
  },
};
