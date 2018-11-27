import Vuex from 'vuex'

const createStore = () => {
  return new Vuex.Store({
    state: () => ({
      current: 0,
      vert: false
    }),
    mutations: {
      next (state) {
        state.current++
      },
      prev (state) {
        state.current--
      },
      vert (state) {
        state.vert ? state.vert = false : state.vert = true
      }
    }
  })
}

export default createStore
