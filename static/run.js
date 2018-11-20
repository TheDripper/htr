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
document.addEventListener('wheel',throttle((e)=>{
	var view = document.querySelector('#viewer');
	console.log(view.scrollLeft);
	if (e.deltaY > 0) {
		console.log('up')
		view.scrollBy({
			left: -window.innerWidth,
			behavior: 'smooth'
		})
		//view.scrollLeft -= window.innerWidth
	} else if (e.deltaY < 0) {
		console.log('down')
		view.scrollBy({
			left: window.innerWidth,
			behavior: 'smooth'
		})
		//view.scrollLeft += window.innerWidth
	}
},1000));
