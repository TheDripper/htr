<template>
<div id=frame>
	<div id=viewer :data-count="$store.state.slides.length" v-touch:swipe="swiper">
		<div class="slide" v-for="slide in $store.state.slides" :id="slide.id" :data-slide="slide.img" :style="{ backgroundImage: 'linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url(/dist/' +slide.img+ ')' }" v-html="slide.mark" :data-dex="$store.state.pages[slide.id]">
		</div>
	</div>
	<a href=/dist/><img src=~/assets/logo.svg id=logo /></a>
	<div id=back @click=novert($event,false)><img src=/dist/back.svg />BACK</div>
	<div id=next @click=novert($event,true)><img src=/dist/next.svg />NEXT</div>
<h4 id=ex @click="mob">Explore <img id=burger src=~/assets/burger.svg /></h4>
<nav>
<ul id=dots>
<li v-for="(slide,index) in $store.state.allslides" :class="{'active':index===$store.state.current}" class=tab :data-slide="slide.id">
<div class=wrap>
<a @click=tab($event,false) :href="'/dist/'+slide.id+'/'" :data-dex="index">{{ slide.id }}
<span class=dot>
</span>
</a>
</div>
	<ul class=subdots >
	<li v-for="(sub,index) in slide.subs" @click=tab($event,true) :data-slide="slide.id" :data-subdex="index">{{ sub.name }}<span class=subdot></span></li>
	</ul>
</li>
</ul>
</nav>

<div id=explore>
<ul id=menu>
<li v-for="(slide,index) in $store.state.allslides"><a :class="{'active':index===$store.state.current}" @click=tab($event,false) :data-slide="slide.id">{{ slide.name }}</a></li>
</ul>
<img id=flower src=~/assets/flower.svg />
<img id=close src=/dist/close.svg @click="nomob" />
</div>




</div>
</template>

<script>



function cleanOrder(store) {
	let viewer = document.querySelector('#viewer').childNodes
	let slides = store.state.slides
	slides.sort((a,b)=>{
		let keyA = a.order
		if(keyA=='undefined')
			keyA=0
		let keyB = b.order
		if(keyB=='undefined')
			keyB=0
		return keyA - keyB
	})
}


function goto(id,store) {
	console.log('go')
	let view = document.querySelector('#viewer')
	let targ = null 
	for(var i=0; i<view.childNodes.length; i++) {
		if(view.childNodes[i].id==id)
			targ = i
	}
	console.log(targ)
	//store.commit('setCur',dex)
	let first = document.querySelector('.slide')
	let newMarg = targ * -100
	first.style.marginLeft = newMarg + 'vw' 
}

const axios = require('axios')
function debounce(func, wait, immediate) {
	var timeout;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
}
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
const up = (store) => {
	let sub = document.querySelector('.open').firstChild
	var curMarg = Number(sub.style.marginTop.replace(/\D/g,'')) * -1
	if(curMarg < 0) {
		curMarg += 100;
		sub.style.marginTop = curMarg + 'vh'
	}
	document.querySelector('#next').style.opacity='0'
	document.querySelector('#next').style.pointerEvents='none'
	setTimeout(()=>{
		store.commit('choke')
	},1100)
}
const down = (store) => {
	let sub = document.querySelector('.open').firstChild
	var curMarg = Number(sub.style.marginTop.replace(/\D/g,'')) * -1
	if(curMarg > ((sub.children.length - 1) * -100)) {
		curMarg -= 100;
		sub.style.marginTop = curMarg + 'vh'
	}
	if(curMarg == ((sub.children.length - 1) * -100)) {
		document.querySelector('#next').style.opacity='1'
		document.querySelector('#next').style.pointerEvents='auto'
	}
	setTimeout(()=>{
		store.commit('choke')
	},1100)
}
const prev = (store) => {
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
		if(!document.querySelector('#'+prevID))
			loadSlide(prevID,store,true)
		if(prevID=='home')
			window.history.pushState(null,'','/dist/')
		else
			window.history.pushState(null,'','/dist/'+prevID+'/')
	}
	setTimeout(()=>{
		store.commit('choke')
	},800)
}

const next = async (store) => {
	cleanOrder(store)
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
		if(store.state.current == count) {
			loadSlide(nextID,store,false)
		}
	}
	setTimeout(()=>{
		store.commit('choke')
	},800)
}
const loadSlide = async function(id,store,isPrev) {
	if(!document.querySelector('#'+id)) {
		let nextMark = await axios(window.location.origin+'/dist/'+id+'.html')
		let order = Number(store.state.pages[id])
		let newSlide = {
			id: id,
			mark: nextMark.data,
			img: id+'.png',
			order: order
		}
		if(isPrev)
			store.commit('addPrev',newSlide)
		else 
			store.commit('addSlide',newSlide)
	}
	cleanOrder(store)
	console.log('done')
}

const vert = function(id,store,subdex) {
	document.querySelector('#logo').style.opacity = '0'
	document.querySelector('#logo').style.pointerEvents = 'none'
	document.querySelectorAll('.subs').forEach(sub=>{
		if(sub.parentNode.id==id) {
			sub.classList.add('open')
			sub.style.transform = "translateY(0%)"
			if(subdex) {
				let newMarg = Number(subdex) * -100 + 'vh'
				sub.firstChild.style.marginTop = newMarg
			}
		}
	})
	store.commit('vert')
	document.querySelector('#back').style.opacity='1'
	document.querySelector('#back').style.pointerEvents='auto'
}
const spinner = function(e){
	document.getElementById('circle').dataset.cur=e.target.id
	document.getElementById('blocktext').textContent = e.target.dataset.copy
	document.getElementById('green').src = '/dist/green'+e.target.id+'.png'
	document.querySelector('.big').classList.remove('big')
	e.target.classList.add('big')
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
			if (window.NodeList && !NodeList.prototype.forEach) {
			    NodeList.prototype.forEach = function (callback, thisArg) {
			        thisArg = thisArg || window;
			        for (var i = 0; i < this.length; i++) {
			            callback.call(thisArg, this[i], i, this);
			        }
			    };
			}
			let last = document.querySelector('.bindme')
			let store = this.$store
			if(last) {
				last.addEventListener('click',function(e){
					let id = e.target.closest('.slide').id
					vert(id,store,0)
				});
				last.classList.remove('bindme')
			}
			let spin = document.querySelectorAll('.bindspin')
			if(spin) {
				spin.forEach(img=>{
					img.addEventListener('click',function(e){
						spinner(e)
					})
				})
			}
			let rightrow = document.querySelector('.diffbind')
			if(rightrow) {
				rightrow.addEventListener('click',function(e){
					store.commit('choke')
					next(store)
				})
				rightrow.classList.remove('diffbind')
			}
			let onbutt = document.querySelectorAll('.view')
			if(onbutt) {
				onbutt.forEach(butt=>{
					butt.addEventListener('click',function(e){
						document.querySelector('#storycont').dataset.mode=e.target.dataset.view
						document.querySelector('.on').classList.remove('on')
						e.target.classList.add('on')
					})
				})
			}
		}
	},
	async created() {
		if(process.browser) {
		
		let vuestance = this
		document.addEventListener('wheel',function(e){
			if(!vuestance.$store.state.vert) {
				e.preventDefault();
				var view = document.querySelector('#viewer');
				if (e.deltaY > 0 && !vuestance.$store.state.choke) {
					vuestance.$store.commit('choke')
					next(vuestance.$store)
				} else if (e.deltaY < 0 && !vuestance.$store.state.choke) {
					vuestance.$store.commit('choke')
					prev(vuestance.$store)
				} else {
					return;
				}
			} else {
				e.preventDefault();
				if (e.deltaY > 0 && !vuestance.$store.state.choke) {
					vuestance.$store.commit('choke')
					down(vuestance.$store)
				} else if (e.deltaY < 0 && !vuestance.$store.state.choke) {
					vuestance.$store.commit('choke')
					up(vuestance.$store)
				} else {
					return
				}

			}
		})
		let allslides = [
			{
				id: 'home',
				name: "Home",
			},
			{
				id: 'mission',
				name: "Mission",
				subs: [
					{
						id: 'mission_how',
						name: "How",
					},
					{
						id: 'mission_why',
						name: "Why",
					}
				]
			},
			{
				id: 'impact',
				name: "Impact"
			},
			{
				id: 'contact',
				name: 'Contact'
			}
		]
		let id = window.location.pathname.split('/').filter(dir=>{
			return dir != ''
		})
		id = id[id.length-1]
		if(id=='dist')
			id='home'
		let pages = this.$store.state.pages
		if(id) {
			let pagedex = pages[id]
			this.$store.commit('setID',id)
			this.$store.commit('setCur',pagedex)
			await loadSlide(this.$store.state.id,this.$store,false)
		} else {
			this.$store.commit('setCur',0)
			$store.commit('addSlide',index)
			//loadSlide('index',this.$store,false)
		}
		this.$store.commit('loadAll',allslides);
		}
	},
	methods: {
		tab: async function(e,sub) {
			e.preventDefault();
			let id = e.target.closest('.tab').dataset.slide
			let pages = this.$store.state.pages
			this.$store.commit('setID',id)
			this.$store.commit('setCur',pages[id])
			if(!document.querySelector('#'+id))
				await loadSlide(this.$store.state.id,this.$store,false)
			console.log(this.$store.state.slides)
			setTimeout(()=>{
				goto(id,this.$store)
			},50)
			console.log("TEST")
			document.querySelector('#explore').style.opacity='0';
			document.querySelector('#explore').style.pointerEvents='none';
			let subs = document.querySelectorAll('.subs')
			if(sub) {
				let subdex = e.target.dataset.subdex
				vert(id,this.$store,subdex)
				//for(var i=0; i<subs.length; i++) {
				//	if(subs[i].parentNode.id==id) {
				//		subs[i].style.transform='translate(0)'
				//		vert(id,this.$store)
				//	}
				//}
			}
		},
		novert: function(e,showNext) {
			document.querySelector('#logo').style.opacity = '1'
			document.querySelector('#logo').style.pointerEvents = 'auto'
			document.querySelector('.open').style.transform="translateY(100%)"
			document.querySelector('.open').firstChild.style.marginTop = '0'
			document.querySelector('.open').classList.remove('open')
			this.$store.commit('vert')
			document.querySelector('#back').style.opacity='0'
			document.querySelector('#back').style.pointerEvents='none'
			document.querySelector('#next').style.opacity='0'
			document.querySelector('#next').style.pointerEvents='none'
			if(showNext) {
				this.$store.commit('choke')
				next(this.$store)
			}
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
				if(e=='left') {
					this.$store.commit('choke')
					next(this.$store)
				} else if(e=='right') {
					this.$store.commit('choke')
					prev(this.$store)
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
	height: 66vh;
	justify-content: space-between;
	padding: 40px;
	padding-right: 80px;
	.wrap {
		display: flex;
		&:hover {
			.dot {
  				animation: pulse 1s infinite;
			}
		}
	}
	.active {
		transition: all 0.3s ease;
	}
}
.element {
}

@keyframes pulse {
  0% {
	  width: 14px;
	  height: 14px;
  }
  50% {
	width: 20px;
	height: 20px;
  }
  100% {
	  width: 14px;
	  height: 14px;
  }
}
#dots > li {
	display: flex;
	align-items: center;
	flex-direction: column;
	position: relative;
	a {
		color: #ECE5C9;
		font-family: "flamaSemi";
		text-transform: uppercase;
		font-size: 12px;
		display: flex;
		align-items: center;
	}
	&:not(.active) {
		padding-right: 2px;
	}
	&:last-child {
		.dot:after {
			display: none;
		}
	}
}
	.subdots {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		opacity: 0;
		pointer-events: none;
		position: absolute;
		right: 0;
    		top: 0;
    		height: calc(22vh - 14px);
    		justify-content: space-around;
    		padding: 50px 0px;
		transition: all 0.2s ease;
		li {
			position: relative;
			color: #ECE5C9;
			font-family: "flamaSemi";
			font-size: 12px;
			display: flex;
			align-items: center;
			cursor: pointer;
		}
	}
	.subdot {
		width: 8px;
		height: 8px;
		border-radius: 50px;
		border: 1px solid white;
		margin-left: 5px;
		cursor: pointer;
	}
#dots .dot {
	width: 14px;
	height: 14px;
	border-radius: 50%;
	background: transparent;
	border: 1px solid white;
	transition: all 0.3s ease;
	margin-left: 20px;
	position: relative;
	overflow: visible;
	flex-shrink: 0;

	
	
	//&:after {
	//	content: '';
	//	border-right: 1px solid white;
	//	height: calc(12vh - 14px);
	//	position: absolute;
	//	top: 0;
	//	left: 50%;
	//	transform: translate(-50%,14px);
	//}
}
#dots {
	.active {
		.subdots {
			opacity: 1;
			pointer-events: auto;
		}
	}
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
	@media(max-width:800px) {
		margin-bottom: 20px;
	}
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
	transition: all 0.6s ease;
	width: 100vw;
	z-index: 20;
}
.sub {
	transition: all 0.4s ease;
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
	background: rgba(0,0,0,0.7);
	padding: 10px;
	font-size: 16px;
	text-transform: uppercase;
	color: #ECE5D1;
	position: fixed;
	bottom: 10px;
	left: 10px;
	opacity: 0;
	transition: all 0.3s ease;
	font-family: "flamaSemi";
	pointer-events: none;
	z-index: 20;
	cursor: pointer;
	display: flex;
	align-items: center;
	img {
		width: 40px;
		margin-right: 10px;
	}

}
#next {
	background: rgba(0,0,0,0.7);
	padding: 10px;
	font-size: 16px;
	text-transform: uppercase;
	color: #ECE5D1;
	position: fixed;
	bottom: 10px;
	left: 130px;
	opacity: 0;
	transition: all 0.3s ease;
	font-family: "flamaSemi";
	pointer-events: none;
	z-index: 20;
	cursor: pointer;
	display: flex;
	align-items: center;
	img {
		width: 40px;
		margin-right: 10px;
	}

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
	position: absolute;
	bottom: 40px;
	left: 50%;
	transform: translateX(-50%);
	font-family: "heart" !important;	
	color: #ECE5C9;
	font-size: 40px;
	cursor: pointer;
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
	position: absolute;
	top: 30px;
	right: 70px;
	@media(max-width:1660px) {
		right: 20px;
	}
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
	z-index: 950;
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
	cursor: pointer;
	position: absolute;
	top: 30px;
	right: 110px;
	width: 27px;
}
#home .name {
}
@media(max-width:1660px) {
	#dots {
		padding: 10px;
	}
	#ex {
		padding: 10px;
	}
}
@media(max-width:600px) {
	#dots {
		display: none;
	}
	nav {
		top: 25px;
		right: 5px;
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
#dot {
	background-image: url('/dist/dot.png');
	width: 318px;
	height: 216px;
	background-size: cover;
	transform: translate(20px,110px);
}
#trace {
	width: 890px;
	position: absolute;
	top: 8vh;
	left: 50%;
	transform: translateX(-50%);
	opacity: 0.6;
}
#tracerow {
	position: absolute;
	bottom: -70px;
	left: 50%;
	width: 416px;
	cursor: pointer;
}
#rightrow {
	width: 80px;
	margin-left: 40px;
	transition: all 0.3s ease;
	cursor: pointer;
}
#diff {
	display: flex;
	position: absolute;
	align-items: center;
	transform: translateY(22vh);
	cursor: pointer;
}
@media(max-width:800px) {
	#diff {
		flex-direction: column;
		transform: none;
		bottom: 10vh;
	}
	#dot {
		display: none;
	}
	#rightrow {
		width: 50px;
	}
}
#photos {
}
@media(max-width:1500px) {
}
#circle {
	z-index: 3000;
	width: 470px;
	height: 470px;
	position: absolute;
	right: 15%;
	max-width: 100%;
	background-image: url('/dist/circleback.svg');
	background-size: 90%;
	background-position: center;
	background-repeat: no-repeat;
	img {
		transition: all 0.4s ease;
		width: 100px;
		cursor: pointer;
		&.big {
			width: 150px;
		}
		@media(max-width:600px) {
			width: 60px;
			&.big {
				width: 80px;
			}
		}
	}
	&[data-cur=seeds] {
		#seeds {
			position: absolute;
			top: 0;
			left: 50%;
			transform: translateX(-50%);
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
		}
		#heart {
			position: absolute;
			top: 50%;
			right: 0;
			transform: translateY(-50%);
		}
		#seeds {
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
		}
		#hands {
			position: absolute;
			top: 50%;
			left: 0;
			transform: translateY(-50%);
		}
}
	&[data-cur=hands] {
		#hands {
			position: absolute;
			top: 0;
			left: 50%;
			transform: translateX(-50%);
		}
		#lake {
			position: absolute;
			top: 50%;
			right: 0;
			transform: translateY(-50%);
		}
		#heart {
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
		}
		#seeds {
			position: absolute;
			top: 50%;
			left: 0;
			transform: translateY(-50%);
		}
	}
	&[data-cur=heart] {
		#heart {
			position: absolute;
			top: 0;
			left: 50%;
			transform: translateX(-50%);
		}
		#seeds {
			position: absolute;
			top: 50%;
			right: 0;
			transform: translateY(-50%);
		}
		#hands {
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
		}
		#lake {
			position: absolute;
			top: 50%;
			left: 0;
			transform: translateY(-50%);
		}
	}
	@media(max-width:1150px) {
		position: relative;
		order: 1;
		right: auto;
	}
}
#mission_how {
	h1 {
		font-size: 40px !important;
	}
	h4, h1, p {
		width: 50%;
		padding-right: 50px;
		@media(max-width:1200px) {
			width: 50%;
		}
		@media(max-width:1150px) {
			width: 100%;
			max-width: none;
		}
	}
	align-items: flex-start;
	@media(max-width:1400px) {
		padding-left: 20px;
	}
	@media(max-width:1150px) {
		height: auto;
		flex-direction: column;
		padding: 20px;
		align-items: center;
	}
}
#copyblock {
	width: 40%;
	height: 250px;
	background: url('/dist/copyblock.png');
	background-size: cover;
	padding: 20px;
	display: flex;
	font-size: 17px;
	color: #444830;
	align-items: center;
	padding-top: 40px;
	@media(max-width:1200px) {
		width: 60%;
	}
	@media(max-width:1150px) {
		order: 2;
		width: 100%;
		p {
			font-size: 12px;
		}
	}
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
#photos {
	position: absolute;
	top: 0;
	left: 0;
	width: 100vw;
	width: 100vw;
	height: 100vh;
	background-size: cover;
	background-position: 100% 30%;
}
#mission_why {
	.wrap {
		max-width: 80%;
		text-align: right;
		justify-content: flex-end;
		display: flex;
		flex-direction: column;
		align-items: flex-end;
		//transform: translateY(30px);
	}
	h1, p, h4 {
		color: #ECE5C9;
		position: relative;
		z-index: 55;
		text-align: center;
		width: 100%;
		max-width: 80%;
	}
}
@media(max-width:1540px) {
	#photos {
		transform: translateX(-10%);
	}
	#mission_why h1, #mission_why p, #mission_why h4 {
		max-width: none !important;
	}
	#mission_why .wrap {
		transform: translateY(-40px);
	}
}
@media(max-width:1200px) {
	#mission_why {
		align-items: flex-start;
	}
	#mission_why h1, #mission_why p, #mission_why h4 {
		max-width: none !important;
		text-align: left !important;
	}
}
@media(max-width:900px) {
	#mission_why {
		.wrap {
			padding: 15px;
			background: rgba(0,0,0,0.6);
			border: 2px solid white;
		}
		p {
			margin-bottom: 0;
		}
	}
}
@media(max-width:600px) {
	#photos {
		transform: none;
	}
	#mission_why h1, #mission_why p, #mission_why h4 {
		transform: none;
	}
	#mission_why .wrap {
		transform: translateY(-30px);
		h1 {
			margin-bottom: 5px;
		}
	}
}

[data-current=mission] + #logolink {
		display: none;
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
	position: relative;
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
#stories {
	width: 800px;
}
#diff h2 {
		font-family: "heart" !important;	
		color: #ECE5C9;
		font-size: 40px;
		cursor: pointer;
}
@media(max-width:600px) {
	#diff h2 {
		font-size: 24px;
		margin-bottom: 10px;
	}
}
@font-face {
	font-family: "heart";
	src: url('/dist/heartone.ttf')
}
.slide {
	h1, h4, p {
	}
}
#diff:hover {
	#rightrow {
		transform: translateX(20px);
	}
}
#logo {
	transition: all 0.2s ease;
}
@media(max-width:800px) {
	#logo {
		width: 60px;
		top: 15px;
		left: 20px;
	}
}
#home {
	order: 1;
}
#mission {
	order: 2;
}
#impact {
	order: 3;
}
#contact {
	order: 4;
	flex-direction: row;
	h1, h4, p {
		text-align: left;
	}
	#formcont {
		width: 50%;
		.field {
			display: flex;
		}
		.control {
			width: 100%;
			padding: 10px;
		}
		input, textarea {
			width: 100%;
			background: none;
			appearance: none;
			border: 2px solid #ece5d1;
			font-family: "flamaSemi";
			color: #ece5d1;
			font-size: 15px;
			text-transform: uppercase;
			height: 70px;
			padding: 20px 40px;
			&:focus {
				background: #7e8959;
			}
			&::placeholder {
				color: #ece5d1;
			}
		}
		textarea {
			height: 150px;
		}
		
	}
	@media(max-width: 1360px) {
		#formcont {
			width: 80%;
		}
		p {
			max-width: none;
		}
	}
	@media(max-width:1100px) {
		flex-direction: column;
		align-items: center;
		#formcont {
			margin-top: 40px;
		}
	}
	@media(max-width:720px) {
		align-items: flex-start;
		padding: 40px 20px;
		padding-top: 80px;
		#formcont {
			width: 80%;
			margin-top: 0;
			transform: translateX(-10px)
		}
		.field {
			flex-wrap: wrap;
			margin-bottom: 0;
		}
		.wrap {
			width: 80%;
			padding-top: 40px;
		}
		h1 {
			margin-bottom: 0;
		}
		p {
		}
	}
	@media(max-width:600px) {
		padding-right: 0;
		#formcont {
			width: 100%;
		}
		p{
			width: 100%;
		}
		.wrap {
			width: 100%;
		}
	}
}
</style>


