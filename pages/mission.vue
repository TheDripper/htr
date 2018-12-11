<template>
<div id=frame>
	<div id=viewer :data-count="$store.state.slides.length" v-touch:swipe="swiper">
		<div class="slide" v-for="slide in $store.state.slides" :id="slide.id" :data-slide="slide.img" :style="{ backgroundImage: 'linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url(/' +slide.img+ ')' }" v-html="slide.mark">
		</div>
	</div>
	<a href=/dist/><img src=~/assets/logo.svg id=logo /></a>
	<h2 id=back @click="novert">Back</h2>
<nav>
<h4 id=ex @click="mob">Explore <img id=burger src=~/assets/burger.svg /></h4>
<ul id=dots>
<li v-for="(slide,index) in $store.state.allslides" :class="{'active':index===$store.state.current}">{{ slide.id }}<span class=dot></span></li>
</ul>
</nav>

<div id=explore>
<ul id=menu>
<li v-for="(slide,index) in $store.state.allslides"><a :class="{'active':index===$store.state.current}" :href="'/'+slide.id+'/'">{{ slide.name }}</a></li>
</ul>
<img id=flower src=~/assets/flower.svg />
<img id=close src=~/assets/close.svg @click="nomob" />
</div>




</div>
</template>

<script>
const axios = require('axios')
const throttle = (func, limit) => {
  let inThrottle
  return function() {
    const args = arguments
    const context = this
    if (!inThrottle) {
      func.apply(context, args)
      inThrottle = true
      setTimeout(() => inThrottle = false, limit)
    }
  }
}
const prev = (curslide,store) => {
	var view = document.querySelector('#viewer');
	var slide = view.firstChild;
	var count = view.dataset.count;
	var curMarg = Number(slide.style.marginLeft.slice(0,-2));
	if(store.state.current!=0) {
		if(curMarg < 0) {
			curMarg += 100;
			slide.style.marginLeft = curMarg+'vw';
		}
		store.commit('prev')
		let prevdex = Number(store.state.current)
		let prevID = store.state.allslides[prevdex].id
		window.history.pushState(null,'','/'+prevID+'/')
		if(!document.querySelector('#'+prevID))
			loadSlide(prevID,store,true)
	}
}

const next = async (curslide,store) => {
	console.log('test')
	var view = document.querySelector('#viewer');
	var slide = view.firstChild;
	var count = view.dataset.count;
	if(store.state.current < store.state.allslides.length - 1) {
		var curMarg = Number(slide.style.marginLeft.slice(0,-2));
		curMarg -= 100;
		slide.style.marginLeft = curMarg+'vw';
		store.commit('next')
		let nextdex = Number(store.state.current)
		let nextID = store.state.allslides[nextdex].id
		window.history.pushState(null,'','/'+nextID+'/')
		console.log(store.state.current)
		console.log(count)
		if(store.state.current == count) {
			loadSlide(nextID,store,false)
		}
	}
}
const loadSlide = async function(id,store,isPrev) {
	console.log(id)
	let nextMark = await axios(window.location.origin+'/'+id+'.html')
	let newSlide = {
		id: id,
		mark: nextMark.data,
		img: id+'.png'
	}
	if(isPrev)
		store.commit('addPrev',newSlide)
	else 
		store.commit('addSlide',newSlide)
}

const vert = function(e,store) {
	e.target.parentNode.querySelector('.subs').classList.add('open')
	e.target.parentNode.querySelector('.subs').style.transform = "translateY(0%)"
	store.commit('vert')
	document.querySelector('#back').style.opacity='1'
	document.querySelector('#back').style.pointerEvents='auto'
}

export default {
	head: {
		script: [
			//{ src: '/run.js' }
		]
	},
	asyncData({store}) {
		if(process.browser) {
		}
	},
	updated() {
		if(process.browser) {
			let last = document.querySelector('.bindme')
			let store = this.$store
			if(last) {
				last.addEventListener('click',function(e){
					vert(e,store)
				});
				last.classList.remove('bindme')
			}
		}
	},
	async created() {
		if(process.browser) {
		let vuestance = this
		document.addEventListener('wheel',throttle(function(e){
			if(!vuestance.$store.state.vert) {
				var view = document.querySelector('#viewer');
				let curslide = view.childNodes[vuestance.$store.state.current]
				if (e.deltaY > 0) {
					prev(curslide,vuestance.$store)
				} else if (e.deltaY < 0) {
					next(curslide,vuestance.$store)
				}
			}
		},1000));
		let allslides = [
			{
				id: 'home',
				name: "Home",
			},
			{
				id: 'mission',
				name: "Mission",
			},
			{
				id: 'coalition',
				name: "Coalition"
			}
		]
		let base = 'http://localhost:3000'
		let home = await axios(base+'/home.html');
		let index = [
			{
				id: 'home',
				name: 'Home',
				mark: home.data,
				img: 'one.png'
			}
		]
		//let id = this.route.name.replace(/\//g, "");
		let id = 'mission'
		let pages = {
			"home": 0,
			"mission": 1,
			"coalition": 2
		}
		if(id) {
			this.$store.commit('setID',id)
			this.$store.commit('setCur',pages[id])
			loadSlide(this.$store.state.id,this.$store,false)
		} else {
			console.log(this.$store.state.current)
			this.$store.commit('setCur',0)
			console.log(this.route.name)
			$store.commit('addSlide',index)
			//loadSlide('index',this.$store,false)
		}
		this.$store.commit('loadAll',allslides);
		}

	},
	methods: {
		//next: async () => {
		//	console.log('test')
		//	var view = document.querySelector('#viewer');
		//	var slide = view.firstChild;
		//	var count = view.dataset.count;
		//	if(store.state.current < store.state.allslides.length - 1) {
		//		var curMarg = Number(slide.style.marginLeft.slice(0,-2));
		//		curMarg -= 100;
		//		slide.style.marginLeft = curMarg+'vw';
		//		store.commit('next')
		//		let nextdex = Number(store.state.current)
		//		let nextID = store.state.allslides[nextdex].id
		//		window.history.pushState(null,'','/'+nextID+'/')
		//		console.log(store.state.current)
		//		console.log(count)
		//		if(store.state.current == count) {
		//			loadSlide(nextID,store,false)
		//		}
		//	}
		//},
		novert: function(e) {
			document.querySelector('.open').style.transform="translateY(100%)"
			document.querySelector('.open').classList.remove('open')
			this.$store.commit('vert')
			document.querySelector('#back').style.opacity='0'
			document.querySelector('#back').style.pointerEvents='none'
		},
		mob: function(e) {
			document.querySelector('#explore').style.opacity='1';
			document.querySelector('#explore').style.pointerEvents='auto';
		},
		nomob: function(e) {
			document.querySelector('#explore').style.opacity='0';
			document.querySelector('#explore').style.pointerEvents='none';
		},
		swiper: function(e) {
			if(!this.$store.state.vert) {
				var view = document.querySelector('#viewer');
				var slide = view.firstChild;
				var count = view.dataset.count;
				let curslide = view.childNodes[this.$store.state.current]
				if(e=='left') {
					next(curslide,this)
				} else if(e=='right') {
					prev(curslide,this)
				}
			}
		}

	}
}
</script>
<style>
#frame {
	width: 100vw;
	height: 100vh;
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;
}
#viewer {
	width: 100vw;
	height: 100vh;
	overflow: hidden;
	display: flex;
}
.slide {
	width: 100vw;
	height: 100vh;
	flex-shrink: 0;
	transition: all 0.5s ease;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	background-size: cover;
	position: relative;
	padding: 0 10vw;
	background-color: black;
}
.slide:first-child {
}
.shade {
	position: absolute;
	top: 0;
	left: 0;
	background: black;
	opacity: 0.5;
	width: 100%;
	height: 100%;
	pointer-events: none;
}
#lime {
	background: lime;
}
#red {
	background: red;
}
#blue {
	background: blue;
}
#dots {
	display: flex;
	flex-direction: column;
	align-items: flex-end;
	height: 85vh;
	justify-content: space-between;
	padding: 40px;
}
#dots li {
	color: #ECE5C9;
	display: flex;
	align-items: center;
	font-family: "flamaSemi";
	text-transform: uppercase;
	font-size: 12px;
}
#dots .dot {
	width: 14px;
	height: 14px;
	border-radius: 50%;
	background: transparent;
	border: 1px solid white;
	transition: all 0.5s ease;
	display: inline-block;
	margin-left: 20px;
}
#dots li.active .dot {
	background: white;
	width: 20px;
	height: 20px;
}
nav{
	position: fixed;
	right: 20px;
	top: 50%;
	transform: translateY(-50%);
	z-index: 40;
}
.slide h1 {
	text-align: center;
	max-width: 80%;
	line-height: 1;
	margin-bottom: 40px;
}
.slide p {
	max-width: 80%;
	text-align: center;
}
#home p {
	font-family: "flamaSemi";
	text-transform: uppercase;
	max-width: 60%;
	text-align: center;
	font-size: 18px;
}
#mission {
	color: white;
	text-align: left;
	align-items: flex-start;
}
#mission h1 {
	text-align: left;
	color: white;
}
#mission p {
	text-align: left;
	color: white;
}
#about p {
	max-width: 60%;
}
#approach {
	align-items: flex-start;
}
h4 {
	text-transform: uppercase;
	font-family: "flamaSemi";
} 
.slide button {
}
.subs {
	position: absolute;
	top: 0;
	left: 0;
	transform: translateY(100%);
	transition: all 0.3s ease;
	width: 100vw;
	height: 100vh;
	z-index: 10;
	overflow: hidden;
	overflow-y: scroll;
}
.sub {
	width: 100vw;
	height: 100vh;
	display: flex;
	justify-content: center;
	align-items: center;
	flex-direction: column;
	padding: 0 10vw;
}
#back {
	font-size: 43px;
	text-transform: uppercase;
	color: white;
	position: fixed;
	bottom: 10px;
	left: 10px;
	opacity: 0;
	transition: all 0.3s ease;
	font-family: "flamaSemi";
	pointer-events: none;
	z-index: 15;
	cursor: pointer;

}
.open .back {
	opacity: 1;
	pointer-events: auto;
}
.slide a {
}
.opener {
	display: flex;
	flex-direction: column;
	align-items: center;
	margin: 20px auto;
	font-size: 24px;
	font-family: "flamaSemi";
	position: absolute;
	bottom: 40px;
	left: 50%;
	transform: translateX(-50%);
}
.opener img {
	pointer-events: none;
	transition: all 0.3s ease;
}
.opener:hover img {
	transform: translateY(10px);
}
#burger {
	width: 27px;
	height: 19px;
	margin-left: 20px;
	cursor: pointer;
}
#ex {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	padding-right: 40px;
	cursor: pointer;
}
#explore {
	width: 100vw;
	height: 100vh;
	background-size: cover;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	overflow: hidden;
	background-image:linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)),url('~/assets/explore.png');
	position: fixed;
	opacity: 0;
	z-index: 50;
	pointer-events: none;
	transition: all 0.3s ease;
}
#flower {
	position: absolute;
	left: 50%;
	top: 50%;
	transform: translate(-50%,-50%);
	width: 40vmax;
}
#menu {
	font-family: "argentReg";
	color: #ECE5C9;
	text-align: center;
	position: relative;
	z-index: 999;
}
#menu a {
	font-size: 47px;
}
#menu a.active {
	font-family: "argentBold";
}
#close {
	position: absolute;
	top: 30px;
	right: 40px;
	width: 61px;
	height: 61px;
	cursor: pointer;
}
#home .name {
}
@media(max-width:1100px) {
	#dots {
		padding: 10px;
	}
	#ex {
		padding: 10px;
	}
}
@media(max-width:600px) {
	nav {
		display: none;
	}
	.slide {
		padding: 0 5vw;
	}
	#home p {
		max-width: 80%;
	}
	.slide * {
		max-width: 100% !important;
	}
	.slide h1 {
		margin-bottom: 10px;
	}
	.slide p {
		font-size: 12px;
		margin-bottom: 40px;
	}
	#logo {
		width: 60px;
		top: 10px;
		left: 10px;
	}
	#home p {
		font-size: 15px;
	}
	.opener img {
		width: 40px;
	}
}
#prevslide {
	transition: all 0.3s ease;
}
#trace {
	width: 894px;
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%,-50%);
}
#tracerow {
	position: absolute;
	bottom: -70px;
	left: 50%;
	width: 416px;
}
#rightrow {
	position: absolute;
	bottom: 10vh;
	left: 50%;
	transform: translateX(-50%) rotate(270deg);

}
#photos {
	position: absolute;
	top: 0;
	left: 0;
	width: 100vw;

}


</style>



