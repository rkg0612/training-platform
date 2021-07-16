const state = {
    internetShopReport: '',
};

const mutations = {
    setReport: (st, data) => {
        st.internetShopReport = data;
    }
};

const actions = {
    getReport(context, payload) {
        return this._vm.axios.get(`/preview-reports/internet-shop/${payload}`);
    },
    sendReport(context, payload) {
        return this._vm.axios.post('/send-reports/internet-shop', payload);
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
