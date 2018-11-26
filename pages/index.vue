<template>
<div id=frame>
<div id=viewer :data-count="slides.length">
	<div class="slide" v-for="slide in slides" :style="{ backgroundImage: 'url(' +slide.img+ ')' }">
	<h1>{{ slide.text }}</h1>
	<div class=shade></div>
	</div>
</div>
<nav>
<ul id=dots>
<li v-for="(slide,index) in slides" :class="{'active':index===$store.state.current}"></li>
</ul>
</nav>
</div>
</template>

<script>
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
export default {
	head: {
		script: [
			//{ src: '/run.js' }
		]
	},
	mounted() {
		if(process.browser) {
			console.log(this.$store.state.current);
			let vuestance = this
			document.addEventListener('wheel',throttle(function(e){
				var view = document.querySelector('#viewer');
				var slide = view.firstChild;
				var count = view.dataset.count;
				if (e.deltaY > 0) {
					var curMarg = Number(slide.style.marginLeft.slice(0,-2));
					if(curMarg < 0) {
						curMarg += 100;
						slide.style.marginLeft = curMarg+'vw';
						vuestance.$store.commit('prev')
					}
			
				} else if (e.deltaY < 0) {
					var curMarg = Number(slide.style.marginLeft.slice(0,-2));
					//if(!curMarg)
					//	curMarg = -100
					if(curMarg/100 * -1 < count - 1) {
						curMarg -= 100;
						slide.style.marginLeft = curMarg+'vw';
						vuestance.$store.commit('next')
					}
				}
			},1100));
		}
	},
	asyncData() {
		return {
			slides: [
				{
					img: 'one.png',
					text: 'The Battle Against Deforestation Takes a Village.',
				},
				{
					img: 'two.png',
					text: 'first slide'
				},
				{
					img: 'three.png',
					text: 'third slide'
				},
				{
					img: 'four.png',
					text: 'fourth slide'
				}
			],
			current: 0
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
	justify-content: center;
	align-items: center;
	background-size: cover;
	position: relative;
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
	align-items: center;
	height: 85vh;
	justify-content: space-between;
}
#dots li {
	width: 14px;
	height: 14px;
	border-radius: 50%;
	background: transparent;
	border: 1px solid white;
	transition: all 0.5s ease;
}
#dots li.active {
	background: white;
	width: 20px;
	height: 20px;
}
nav{
	position: fixed;
	right: 20px;
	top: 50%;
	transform: translateY(-50%);
}
.slide h1 {
	z-index: 99;
}

</style>
