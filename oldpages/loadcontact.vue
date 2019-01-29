<template>
<div id=frame>
	<div id=viewer :data-count="$store.state.slides.length" v-touch:swipe="swiper">
	<div class="slide" v-for="slide in slides" :id="slide.id" :data-slide="slide.img" :style="{ backgroundImage: 'linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url(/dist/' +slide.img+ ')' }">

		<h4 class=name>{{ slide.name }}</h4>
		<h1>{{ slide.text }}</h1>
		<p>{{ slide.copy }}</p>
		<a v-if="slide.butt" class="opener bindme">{{ slide.name }}<img src=/dist/down.png /></a>
		<div id=formcont>
		<div class="field">
		  <div class="control">
		    <input type="text" name="first" placeholder="First Name">
		  </div>
		  <div class="control">
		    <input type="text" name="last" placeholder="First Name">
		  </div>
		</div>
		<div class="field">
		  <div class="control">
		    <input type="number" name="phone" placeholder="Phone">
		  </div>
		  <div class="control">
		    <input type="email" name="email" placeholder="Email">
		  </div>
		</div>
		<div class="field">
		  <div class="control">
		    <input type="text" name="subject" placeholder="Subject">
		  </div>
		</div>
		<div class="field">
		  <textarea id="message" name="message">
		  </textarea>
		</div>
		</div>
		<div class=subs>
		<div class=sub v-for="(sub,index) in slide.subs" :style="{ backgroundImage: 'linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url(/dist/' +sub.img+ ')' }" :id="sub.id">
			<div class=wrap>
			<h4>{{ sub.name }}</h4>
			<h1>{{ sub.text }}</h1>
			<p>{{ sub.copy }}</p>
			</div>
		</div>
		</div>
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
<li v-for="(slide,index) in $store.state.allslides"><a :class="{'active':index===$store.state.current}" :href="'/dist/'+slide.id+'/'">{{ slide.name }}</a></li>
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
		window.history.pushState(null,'','/dist/'+prevID+'/')
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
		window.history.pushState(null,'','/dist/'+nextID+'/')
		console.log(store.state.current)
		console.log(count)
		if(store.state.current == count) {
			loadSlide(nextID,store,false)
		}
	}
}
const loadSlide = async function(id,store,isPrev) {
	console.log(id)
	let nextMark = await axios(window.location.origin+'/dist/'+id+'.html')
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
		return {
				slides: [
					{
						id: 'connect',
						img: '/impact.png',
						text: "Connect With Us",
						name: "Get Involved",
						copy: `For donations, contact: lorem@haititakesroot.com <br />
						For press inquiries, contact: lorem@haititakesroot.com <br />
						To join our parters, contact: lorem@haititakesroot.com <br />
						Reach us via telephone: <br /> USA: +1 (999) 999-9999 <br /> Haiti: +2 999 9999`

					}
				]	
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
					//prev(curslide,vuestance.$store)
				} else if (e.deltaY < 0) {
					//next(curslide,vuestance.$store)
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
		//let id = this.route.name.replace(/\//g, "");
		let id = 'home'
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
		on: (e)=>{
			document.querySelector('#storycont').dataset.mode=e.target.dataset.view
			document.querySelector('.on').classList.remove('on')
			e.target.classList.add('on')
		},
		spin: (e)=>{
			document.getElementById('circle').dataset.cur=e.target.id
			document.getElementById('blocktext').textContent = e.target.dataset.copy
			document.getElementById('green').src = '/dist/green'+e.target.id+'.png'

		},
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
<style lang="scss">
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
	background-size: cover;
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
	background-size: cover;
	background-position: center;
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
#circle {
	z-index: 3000;
	width: 35vw;
	height: 35vw;
	position: absolute;
	right: 150px;
	max-width: 590px;
	max-height: 590px;
	img {
		transition: all 0.4s ease;
		width: 100px;
	}
	&[data-cur=seeds] {
		#seeds {
			position: absolute;
			top: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 180px;
		}
		#hands {
			position: absolute;
			top: 50%;
			right: 0;
			transform: translateY(-50%);
		}
		#lake {
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
		}
		#heart {
			position: absolute;
			top: 50%;
			left: 0;
			transform: translateY(-50%);
		}
	}
&[data-cur=lake] {
		#lake {
			position: absolute;
			top: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 180px;
		}
		#heart {
			position: absolute;
			top: 50%;
			right: 0;
			transform: translateY(-50%);
			width: 135px;
		}
		#seeds {
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 135px;
		}
		#hands {
			position: absolute;
			top: 50%;
			left: 0;
			transform: translateY(-50%);
			width: 135px;
		}
}
	&[data-cur=hands] {
		#hands {
			position: absolute;
			top: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 180px;
		}
		#lake {
			position: absolute;
			top: 50%;
			right: 0;
			transform: translateY(-50%);
			width: 135px;
		}
		#heart {
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 135px;
		}
		#seeds {
			position: absolute;
			top: 50%;
			left: 0;
			transform: translateY(-50%);
			width: 135px;
		}
	}
	&[data-cur=heart] {
		#heart {
			position: absolute;
			top: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 180px;
		}
		#seeds {
			position: absolute;
			top: 50%;
			right: 0;
			transform: translateY(-50%);
			width: 135px;
		}
		#hands {
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 135px;
		}
		#lake {
			position: absolute;
			top: 50%;
			left: 0;
			transform: translateY(-50%);
			width: 135px;
		}
	}
}
#mission_how {
	h1 {
		font-size: 40px !important;
	}
	h4, h1, p {
		width: 50%;
		padding-right: 50px;
	}
	align-items: flex-start;
	@media(max-width:1400px) {
		padding-left: 10px;
	}
}
#copyblock {
	width: 400px;
	height: 250px;
	background: url('/dist/copyblock.png');
	padding: 20px;
	display: flex;
	font-size: 17px;
	color: #444830;
	align-items: center;
	padding-top: 40px;
}
#blocktext {
	width: 100% !important;
	color: #444830 !important;
	padding: 0 !important;
	font-size: 14px;
}
#green {
	width: 100px;
	margin-right: 20px;
}
#impact_map {
	h1, p, h4, a {
		color: #373930;
	}
}
#impact {
	align-items: flex-start;
	h1, p, h4, a {
		text-align: left;
	}

}
#map {
	width: 700px;
	flex-shrink: 0;
}
#impact_map {
	flex-direction: row;
}
#impact_stories {
	background-color: #D8CFB7;
	flex-direction: row;
}
#onestory {
	width: 512px;
	height: 352px;
}
#twostory {
	width: 297px;
	height: 446px;
}
#threestory {
	width: 379px;
	height: 253px;
}
#fourstory {
	width: 249px;
	height: 373px;
}
#fivestory {
	width: 465px;
	height: 375px;
}
.paper {
	background-image: url('/dist/paper.png');
	padding: 12px 10px;
}
#collage {
	display: flex; 
	flex-wrap: wrap;
	justify-content: flex-end;
	align-items: flex-end;
}
.open .sub {
	height: auto;
	min-height: 100vh;
}
#storycont {
	transition: all 0.3s ease;
	display: flex;
	flex-direction: column;
	align-items: center;
	&[data-mode=map] {
		#collage {
			display: none;
		}
	}
	&[data-mode=collage] {
		#stories {
			display: none;
		}
	}
	width: 60%;
	flex-shrink: 0;
}
#papeone {
	transform: translate(-60px,150px);
	z-index: 2;
}
#papethree {
	transform: translate(0,-150px);
}
#papefive {
	transform: translate(-60%,-200px);
	z-index: 10;
}
#storyswitch {
	margin-top: 40px;
	width: 600px;
	display: flex;
	cursor: pointer;
	div {
		width: 50%;
		height: 65px;
		display: flex;
		justify-content: center;
		align-items: center;
		text-transform: uppercase;
		background: #E6E5E0;
		font-family: "flamaSemi";
		color: #444444;
		&.on {
			background: #444444;
			color: #E6E5E0;
		}
	}
}

</style>



