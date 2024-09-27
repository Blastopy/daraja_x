window.onscroll = function() {scrollFunction()};

function scrollFunction() {
	if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
		document.getElementsByTagName("header").style.display = 'none';
	} else {
		document.getElementsByTagName('header').style.display = 'inline-block';
	}
}