var throttle = (func, limit) => {
  var inThrottle
  return function() {
    var args = arguments
    var context = this
    if (!inThrottle) {
      func.apply(context, args)
      inThrottle = true
      setTimeout(function(){ return inThrottle = false, limit })
    }
  }
}

document.addEventListener('wheel',throttle(function(e){
	var view = document.querySelector('#viewer');
	var slide = view.firstChild;
	console.log(e);
	console.log(slide);
	var count = view.dataset.count;
	if (e.deltaY > 0) {
		var curMarg = Number(slide.style.marginRight.slice(0,-2));
		console.log(curMarg);
		console.log(count);
		if(curMarg < 0) {
			curMarg += 100;
			slide.style.marginRight = curMarg+'vw';
		}

	} else if (e.deltaY < 0) {
		var slide = document.querySelector('.slide');
		var curMarg = Number(slide.style.marginRight.slice(0,-2));
		console.log(curMarg);
		console.log(count);
		if(curMarg/100 * -1 < count - 1) {
			curMarg -= 100;
			slide.style.marginRight = curMarg+'vw';
		}
		//view.scrollBy({
		//	left: window.innerWidth,
		//	behavior: 'smooth'
		//})
		//view.scrollLeft += window.innerWidth
	}
},5000));
