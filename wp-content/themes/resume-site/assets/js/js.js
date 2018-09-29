$(document).ready(function(){
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.up').fadeIn();
		} else {
			$('.up').fadeOut();
		}
	});
	$('.up').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});

	$('.myskills').mouseover(function(){$('.skillbar').each(function(){
	$(this).find('.skillbar-bar').animate({
		width:$(this).attr('data-percent')
	}, 3000);
});});
});