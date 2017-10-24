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
	})
	.on('click', '.confirm', function(e){
		var m = 'Are you sure?', a = $(this).data('msg');
		if (typeof a !== 'undefined')
			m = a;

		return confirm(m);
	});
})(jQuery);