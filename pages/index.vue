<template>
<div id=frame>
<div id=viewer :data-count="slides.length">
	<div class="slide" v-for="slide in slides" :style="{ backgroundImage: 'url(' +slide.img+ ')' }" :id="slide.id">
	<h4>{{ slide.tag }}</h4>
	<h1>{{ slide.text }}</h1>
	<p>{{ slide.copy }}</p>
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
					text: 'Change Starts Here. Introducing Haiti Takes Root.',
					id: 'home',
					copy: 'LOREM IPSUM DOLOR SIT AMET, CONSECTETUER ADIPISCING ELIAM NONUMMY NIBH EUISMOD TINCIDUNT UT LAOREET DOLORE MAGNA ALIQUAM ERAT VOLUTPAT. UT WISI ENIM AD MINIM VENIAM, QUIS NOSTRUD EXERCI TATION LOBORTIS NISLUT.'

				},
				{
					img: 'two.png',
					text: 'The Battle Against Deforestation Takes a Village.',
					id: 'mission',
					copy: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure.',
					tag: 'Our Mission'
				},
				{
					img: 'three.png',
					text: 'Together We Can',
					id: 'about',
					copy: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure.'
				},
				{
					img: 'four.png',
					text: 'Change Starts Here.',
					id: 'approach',
					copy: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure.',
					tag: 'Our Approach'
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
	flex-direction: column;
	justify-content: center;
	align-items: center;
	background-size: cover;
	position: relative;
	padding: 0 10vw;
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
	text-align: center;
	max-width: 80%;
	line-height: 1;
	margin-bottom: 40px;
}
.slide p {
	z-index: 99;
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
.slide h4 {
	z-index: 99;
	text-transform: uppercase;
	font-family: "flamaSemi";
} 

</style>
