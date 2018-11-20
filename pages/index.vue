<template>
<div id=frame>
	<h1 id=top>TOP</h1>
<div id=viewer data-count=3>
	<div id=red class=slide></div>
	<div id=blue class=slide></div>
	<div id=lime class=slide></div>
</div>
	<h1 id=bot>BOT</h1>
</div>
</template>

<script>
export default {
	head: {
		script: [
			{ src: '/run.js' }
		]
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
