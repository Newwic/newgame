import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    todos: []
  },
  mutations: {
    ADD_TODO(state, todo) {
      state.todos.push(todo)
    },
    REMOVE_TODO(state, index) {
      state.todos.splice(index, 1)
    }
  },
  actions: {
    addTodo({ commit }, todo) {
      commit('ADD_TODO', todo)
    }
  },
  getters: {
    allTodos: state => state.todos
  }
})
