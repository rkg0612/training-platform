const state = {
  internetShops: [],
  selectedItem: {},
};

const mutations = {
  setInternetShops: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.internetShops = data;
  },
  insertNewInternetShop: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.internetShops.data.pop();
    st.internetShops.data.unshift(data);
  },
  deleteInternetShop: (st, data) => {
    st.internetShops.data.splice(data, 1);
  },
  editInternetShop: (st, payload) => {

    st.internetShops.data.splice(payload.index, 1, payload.data);
  },
  setSelectedItem: (st, data) => {
    st.selectedItem = data;
  },
  insertNewSms: (st, data) => {
    st.selectedItem.phone_number.sms.push(data);
  }
};

const actions = {
  getInternetShops(context, payload) {
    return this._vm.axios.get(`/secret-shop-management/internet-shops?current_page=${payload.pagination.current_page}&search=${payload.filters.search}&status=${payload.filters.status}&sortBy=${payload.pagination.sortBy}&sortDesc=${payload.pagination.sortDesc}&per_page=${payload.pagination.per_page}`);
  },
  deleteInternetShop(context, payload) {
    return this._vm.axios.delete(`/secret-shop-management/internet-shops/${payload}`);
  },
  restoreInternetShop(context, id) {
    return this._vm.axios.get(`/secret-shop-management/internet-shops/restore/${id}`);
  },
  getInternetShop(context, id) {
    return this._vm.axios.get(`/secret-shop-management/internet-shops/${id}`);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};
