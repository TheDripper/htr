<script>
export default {
  asyncData ({ redirect, store }) {
	  if(process.browser) {
		let id = new URL(window.location).pathname.replace(/\//g, "");
		let pages = {
			"home": 0,
			"mission": 1,
			"coalition": 2
		}
		store.commit('setID',id)
		console.log(pages[id])
		store.commit('setCur',pages[id])
		console.log(store.state.current)
    		return redirect('/')
	  }
  }
}
</script>
