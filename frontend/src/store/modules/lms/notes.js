import {
  findIndex as _findIndex,
  some as _some,
} from 'lodash-es';

const state = {
  items: [
    {
      author: {
        name: '',
      },
      value: '',
      updated_at: ''
    }
  ],
};

const mutations = {
  setNotes: (st, data) => {
    st.items = data;
  },
  addNotes: (st, data) => {
    const isExisting = _some(st.items, data);
    if (!isExisting) {
      st.items.push(data);
    }
  },
  addNote: (st, data) => {
    st.items.unshift(data);
  },
  updateNote: (st, data) => {
    const index = _findIndex(st.items, { id: data.id });
    st.items.splice(index, 1, data);
  }
};

const actions = {
  getNotes(context, {
    unitId,
    page
  }) {
    return this._vm.axios.get(`/notes/unit/${unitId}?page=${page}`);
  },
  storeNote(context, payload) {
    return this._vm.axios.post('/notes/unit', payload);
  },
  editNote(context, payload) {
    return this._vm.axios.patch(`/notes/${payload.id}`, payload);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};
