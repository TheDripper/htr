import Vuex from 'vuex'

const createStore = () => {
  return new Vuex.Store({
    state: () => ({
      current: 0,
      vert: false,
      slides: [],
      allslides: [],
      mark: '',
      nextMark: '',
      prevMark: ''
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
      loadAll(state,data) {
	state.allslides = data	
      },
      addSlide(state,data) {
	      console.log('add')
	state.slides.push(data)
      }
    }
  })
}

export default createStore
