(function($){
	$(document).on('submit','#contactform',function(e){
		e.preventDefault();
		console.log($(this));
		var ajaxData = $(this).serialize();
		console.log(ajaxData);
			$.ajax({
				"url":"/dist/mailer/mailer.php",
				//"dataType":"json",
				"method":"POST",
				"data":ajaxData,
				"success":function(data) {
					console.log(data);
    					$('#contactform').find("input[type=text], input[type=email], textarea").val("");
					$('#contactform').find('input[type=checkbox]').prop('checked', false); 
					
					$('#tacmodal').css({
						'opacity':'1',
						'pointerEvents':'auto'
					});
					setTimeout(function(){
						$('#tacmodal').css({
							'opacity':'0',
							'pointerEvents':'none'
						});
					},7000);
				},
				"error":function(err){
				}

			});
	});
})(jQuery)
