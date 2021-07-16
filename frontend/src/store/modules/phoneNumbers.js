import { forEach as _forEach, findIndex as _findIndex } from "lodash";

const state = {
  phoneNumbers: []
};

const getters = {
  getPhoneNumbers: st => st.phoneNumbers
};

const mutations = {
  setPhoneNumbers: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.phoneNumbers = data;
  },
  setPhoneNumber: (st, data) => {
    st.phoneNumbers.data.push(data);
  },
  editPhoneNumber: (st, data) => {
    const index = _findIndex(st.phoneNumbers.data, { id: data.id });
    // eslint-disable-next-line no-param-reassign
    st.phoneNumbers.data.splice(index, 1, data);
  },
  deletePhoneNumber: (st, data) => {
    st.phoneNumbers.data.splice(data, 1);
  },
  massDeletePhoneNumber: (st, data) => {
    _forEach(data, value => {
      const index = _findIndex(st.phoneNumbers.data, { value });
      st.phoneNumbers.data.splice(index, 1);
    });
  }
};

const actions = {
  getPhoneNumbers(context, { pagination, filters }) {

    return this._vm.axios.get(
      `/secret-shop-management/phone-numbers?filters=${filters}`, {
        params: pagination
      }
    );
  },
  fetchPhoneNumbers(context, { pagination, filters }) {

    return this._vm.axios.get(
      `/secret-shop-management/fetch-phone-numbers?filters=${filters}`, {
        params: pagination
      }
    );
  },
  // eslint-disable-next-line no-unused-vars
  addPhoneNumber(context, payload) {
    return this._vm.axios.post("/secret-shop-management/phone-numbers", payload);
  },
  // eslint-disable-next-line no-unused-vars
  updatePhoneNumber(context, payload) {
    return this._vm.axios.patch(`/secret-shop-management/phone-numbers/${payload.id}`, payload);
  },
  // eslint-disable-next-line no-unused-vars
  deletePhoneNumber(context, payload) {
    return this._vm.axios.post("/secret-shop-management/phone-numbers/delete", payload);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
