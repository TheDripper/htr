import Vuex from 'vuex'

const createStore = () => {
  return new Vuex.Store({
    state: () => ({
      current: 0,
      vert: false,
      slides: [],
      mark: ''
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
      },
      loadSlides(state,data) {
	state.slides = data	
      },
      loadMark(state,data) {
	state.mark = data	
      }
    }
  })
}

export default createStore
