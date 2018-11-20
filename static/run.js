function throttle(func, wait, options) {
  var context, args, result;
  var timeout = null;
  var previous = 0;
  if (!options) options = {};
  var later = function() {
    previous = options.leading === false ? 0 : Date.now();
    timeout = null;
    result = func.apply(context, args);
    if (!timeout) context = args = null;
  };
  return function() {
    var now = Date.now();
    if (!previous && options.leading === false) previous = now;
    var remaining = wait - (now - previous);
    context = this;
    args = arguments;
    if (remaining <= 0 || remaining > wait) {
      if (timeout) {
        clearTimeout(timeout);
        timeout = null;
      }
      previous = now;
      result = func.apply(context, args);
      if (!timeout) context = args = null;
    } else if (!timeout && options.trailing !== false) {
      timeout = setTimeout(later, remaining);
    }
    return result;
  };
};


document.addEventListener('wheel',throttle(function(e){
	var view = document.querySelector('#viewer');
	console.log(view.scrollLeft);
	if (e.deltaY > 0) {
		console.log('up')
		//view.scrollBy({
		//	left: -window.innerWidth,
		//	behavior: 'smooth'
		//})
		//view.scrollLeft -= window.innerWidth
	} else if (e.deltaY < 0) {
		console.log('down')
		//view.scrollBy({
		//	left: window.innerWidth,
		//	behavior: 'smooth'
		//})
		//view.scrollLeft += window.innerWidth
	}
},1000));
