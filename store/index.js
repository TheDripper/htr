import Vuex from 'vuex'

const createStore = () => {
  return new Vuex.Store({
    state: () => ({
      current: 0,
      vert: false,
      proj: false,
      choke: false,
      slides: [],
      allslides: [],
      mark: '',
      nextMark: '',
      prevMark: '',
      id: '',
      subdex: 0,
      pages:  {
	"home": 0,
	"mission": 1,
	"impact": 2,
	"coalition": 3,
	"activities": 4, 
	"news_events": 5,
	"contact": 6
      }
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
      proj (state) {
        state.proj ? state.proj = false : state.proj = true
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
      },
      nochoke(state){
	      state.choke = false
      },
      setDex(state,data) {
        state.subdex = data
      },
      lowDex(state) {
	state.subdex--
      },
      hiDex(state) {
	state.subdex++
      }
    }
  })
}

export default createStore
