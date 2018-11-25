<template>
<div id=frame>
	<h1 id=top>TOP</h1>
<div id=viewer :data-count="slides.length">
	<div class="slide" v-for="slide in slides" :style="{ backgroundImage: 'url(' +slide.img+ ')' }">
	{{ slide.text }}
	</div>
</div>
	<h1 id=bot>BOT</h1>
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
	created() {
		if(process.browser) {
			console.log('test')
			document.addEventListener('wheel',throttle(function(e){
				//console.log('X');
				//console.log(e.deltaX);
				//console.log('Y');
				//console.log(e.deltaY);
				var view = document.querySelector('#viewer');
				var slide = view.firstChild;
				var count = view.dataset.count;
				if (e.deltaY > 0) {
					var curMarg = Number(slide.style.marginRight.slice(0,-2));
					console.log(curMarg);
					if(curMarg < -100) {
						curMarg += 100;
						slide.style.marginRight = curMarg+'vw';
					}
			
				} else if (e.deltaY < 0) {
					var curMarg = Number(slide.style.marginRight.slice(0,-2));
					console.log(curMarg);
					if(!curMarg)
						curMarg = -100
					if(curMarg/100 * -1 < count - 1) {
						curMarg -= 100;
						slide.style.marginRight = curMarg+'vw';
					}
				}
			},2000));
		}
	},
	asyncData() {
		return {
			slides: [
				{
					img: 'one.jpg',
					text: 'dummy slide'
				},
				{
					img: 'two.jpg',
					text: 'first slide'
				},
				{
					img: 'three.jpg',
					text: 'third slide'
				},
				{
					img: 'four.jpg',
					text: 'fourth slide'
				},
				{
					img: 'five.jpg',
					text: 'fifth slide'
				}
			]
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
#top {
	position: absolute;
	top: 0;
	left: 50%;
	transform: translateX(-50%);
	z-index: 99;
}
#bot {
	position: absolute;
	bottom: 0;
	left: 50%;
	transform: translateX(-50%);
	z-index: 99;
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
}
.slide:first-child {
	margin-right: -100vw;
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
</style>
