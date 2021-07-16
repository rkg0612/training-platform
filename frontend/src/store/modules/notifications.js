const state = {
  list: [],
  unreadPagination: {},
};

const actions = {
  fetchAllNotifications(context, payload) {
    return this._vm.axios.get(
      `notifications?page=${payload.page}`
    );
  },
  fetchNewNotification(context, payload) {
    return this._vm.axios.get(
      `notifications/latest?limit=${payload.limit}`
    );
  },
  fetchSeenNotification(context, payload) {
    return this._vm.axios.get(
      `notifications/seen?page=${payload.page}`
    );
  },
  fetchUnreadNotifications(context, payload) {
    return this._vm.axios.get(
      `notifications?type=unread&page=${payload.page}`
    );
  }
};

const mutations =  {
  setNotifications: (st, data) => {
    st.list = data;
  },
  pushNotification: (st, data) => {
    st.list.splice(0, 0, data);
  },
  setUnreadPagination: (st, data) => {
    st.unreadPagination = data;
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
