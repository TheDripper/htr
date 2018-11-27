<template>
  <div>
    <nuxt/>
  </div>
</template>

<style>
html, body {
	box-sizing: border-box;
	margin: 0;
	padding: 0;
}
html
{
	color: #ECE5C9;
	font-family: "argentReg", serif;
}
p, a {
	font-family: "argentReg", serif;
	color: #ECE5C9;
	font-size: 18px;
}
a:hover {
	color: white !important;
}
*, *:before, *:after
{
  box-sizing: border-box;
  margin: 0;
}
.button--green
{
  display: inline-block;
  border-radius: 4px;
  border: 1px solid #3b8070;
  color: #3b8070;
  text-decoration: none;
  padding: 10px 30px;
}
.button--green:hover
{
  color: #fff;
  background-color: #3b8070;
}
.button--grey
{
  display: inline-block;
  border-radius: 4px;
  border: 1px solid #35495e;
  color: #35495e;
  text-decoration: none;
  padding: 10px 30px;
  margin-left: 15px;
}
@font-face {
	font-family: "argentReg";
	src: url('/ArgentCF-Regular.ttf');
}
@font-face {
	font-family: "argentBold";
	src: url('/ArgentCF-Bold.ttf');
}
@font-face {
	font-family: "flamaSemi";
	src: url('/FlamaSemicondensed-Medium.otf');
}
h1 {
	font-family: "argentBold", serif;
	font-size: 76px;
	color: #ECE5C9;
}
h2 {
	font-family: "argentBold", serif;
}
h4 {
	color: #ECE5C9;
	font-size: 15px;
}
#logo {
	position: fixed;
	top: 40px;
	left: 40px;
	z-index: 50;
	width: 150px;
}
@media(max-width:1100px) {
	h1 {
		font-size: 48px;
	}
	.slide p {
		font-size: 18px;
	}
	#logo {
		width: 100px;
	}
}
@media(max-width:600px) {
	h1 {
		font-size: 32px;
	}
	#home p {
		max-width: 80%;
	}
}
</style>
