(function(){
	var x = $('.menu');

	$(document)
	.on('click', '.menu-bars', function(e){
		e.preventDefault();
		x.css('display', 'flex');
	})
	.on('click', '.menu-cross', function(e){
		e.preventDefault();
		x.css('display', 'none');
	});
})(jQuery);