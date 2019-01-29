<template>
<div id=frame>
	<div id=viewer :data-count="$store.state.slides.length" v-touch:swipe="swiper">
		<div class="slide" v-for="slide in $store.state.slides" :id="slide.id" :data-slide="slide.img" :style="{ backgroundImage: 'linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url(/dist/' +slide.img+ ')' }">
		</div>
	</div>
</div>
</template>
<script>
export default {
	asyncData(context) {
		return {
			slides: [
				{
					img: 'impact.png',
					text: 'Our Impact',
					name: 'Togethe We Care',
					copy: "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismo."
				}
			]
		}
	}
}
</script>

