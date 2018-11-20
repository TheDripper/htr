<template>
<div id=frame>
	<h1 id=top>TOP</h1>
<div id=viewer>
	<div id=red class=slide></div>
	<div id=blue class=slide></div>
	<div id=lime class=slide></div>
</div>
	<h1 id=bot>BOT</h1>
</div>
</template>

<script>
export default {
  methods: {
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
	width: 100vh;
	height: 100vw;
	overflow: scroll;
	transform: rotate(-90deg);
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
	width: 100vh;
	height: 100vw;
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
