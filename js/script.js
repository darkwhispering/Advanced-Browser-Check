jQuery(document).ready(function($){
	var el = $('.advanced-browser-check');
	if(el.length > 0) {
		var url = el.data('url');
		if(!$.cookie('abc-hide')) {
			$.ajax({
				url : url.abc_url,
				type : 'POST',
				dataType : 'HTML',
				data : 'ajax=true',
				success : function(response) {
					if(response) {
						el.html(response).show();
						if($('.adv_browser_check').hasClass('ie6')) {
							$('.advanced-browser-check').addClass('ie6');
						}

						el.on('click','a.abc-hide',function(e){
							e.preventDefault();

							el.fadeOut('slow');
							$.cookie("abc-hide", true, { expires: 1, path: '/' });
						});
					}
				}
			});			
		}
	}
});