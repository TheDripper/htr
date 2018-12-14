import Vuex from 'vuex'

const createStore = () => {
  return new Vuex.Store({
    state: () => ({
      current: 0,
      vert: false,
      choke: false,
      slides: [],
      allslides: [],
      mark: '',
      nextMark: '',
      prevMark: '',
      id: ''
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
	state.slides.push(data)
      },
      addPrev(state,data) {
	      state.slides.unshift(data)
      },
      setID(state,data) {
	state.id=data
      },
      setCur(state,data) {
	      state.current=data
      },
      choke(state) {
        state.choke ? state.choke = false : state.choke = true
      }

    }
  })
}

export default createStore
