<template>
<div id=explore>
<ul id=menu>
<li>Home</li>
<li>Our Mission</li>
<li>Our Coalition</li>
<li>Our Impact</li>
<li>Our Work</li>
<li>Blog & Press</li>
<li>Contact</li>
</ul>
<img id=flower src=/flower.svg />
</div>
</template>
<style>
#explore {
	width: 100vw;
	height: 100vh;
	background-size: cover;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	overflow: hidden;
	background-image:linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)),url('/explore.png');

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
	font-size: 47px;
	color: #ECE5C9;
	text-align: center;
}
</style>
