import { findIndex as _findIndex } from 'lodash-es';

const state = {
  list: [],
  newQuiz: {
    title: "",
    questions: []
  },
  selectedQuestion: {
    id: "",
    value: "",
    answers: []
  },
  questionList: [],
};

const getters = {};

const mutations = {
  setQuestion: (st, question) => {
    st.newQuiz.questions.push(question);
  },
  setTitle: (st, title) => {
    st.newQuiz.title = title;
  },
  setQuizzes: (st, quizzes) => {
    st.list = quizzes;
  },
  resetQuestion: st => {
    st.newQuiz = {
      title: "",
      questions: []
    };
  },
  removeQuestion: (st, questionIndex) => {
    st.newQuiz.questions.splice(questionIndex, 1);
  },
  setSelectedQuestion: (st, question) => {
    st.selectedQuestion.id = question.id;
    st.selectedQuestion.value = question.value;
    st.selectedQuestion.answers = [];
    const answers = question.answers;
    answers.forEach(answer => {
      st.selectedQuestion.answers.push(answer);
    });
  },
  updateSelectedQuestion: (st, {
    id, payload
  }) => {
    st.newQuiz.questions.splice(_findIndex(st.newQuiz.questions, id), 1, payload);
  },
  resetQuizQuestions: st => {
    st.newQuiz.questions = [];
  },
  resetSelectedQuestion: st => {
    st.selectedQuestion = {
      id: "",
      value: "",
      answers: []
    };
  },
  setQuestionList: (st, data) => {
    st.questionList = data;
  }
};

const actions = {
  getQuizzes(context, { page, search, status }) {
    return this._vm.axios.get(`lms-manager/builder/quizzes?page=${page}&search=${search}&status=${status}`);
  },
  saveQuiz(context, payload) {
    return this._vm.axios.post("lms-manager/builder/quizzes", payload);
  },
  editQuiz(context, { payload, id }) {
    return this._vm.axios.patch(`lms-manager/builder/quizzes/${id}`, payload);
  },
  getQuiz(context, { id }) {
    return this._vm.axios.get(`lms-manager/builder/quizzes/${id}`);
  },
  deleteQuiz(context, { id }) {
    return this._vm.axios.delete(`lms-manager/builder/quizzes/${id}`);
  },
  restoreQuiz(context, { id }) {
    return this._vm.axios.patch(`lms-manager/builder/quiz/restore/${id}`);
  },
  searchQuestions(context, { page, search }) {
    return this._vm.axios.get(`lms-manager/builder/questions?page=${page}&search=${search}`);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
