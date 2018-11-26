import Vuex from 'vuex'

const createStore = () => {
  return new Vuex.Store({
    state: () => ({
      current: 0
    }),
    mutations: {
      next (state) {
        state.current++
      },
      prev (state) {
        state.current--
      }
    }
  })
}

export default createStore
