jQuery(document).ready(function($){



		$(document).on('click', '#send-test-mail', function(){

			var test_mail_email = $('#test_mail_email').val();
			var test_mail_content = $('#test_mail_content').val();

			$(this).html('<i class="fas fa-spinner fa-spin"></i> Send test mail');


			//console.log(test_mail_email);

			$.ajax(
				{
					type: 'POST',
					context: this,
					url:mail_picker_ajax.mail_picker_ajaxurl,
					data: {"action": "mail_picker_ajax_send_test_mail", "sendTo":test_mail_email, "content":test_mail_content, },
					success: function(data)
					{
						var data	= JSON.parse(data);
						var message	= data['message'];

						//console.log(data);
						$(this).html('Send test mail');
						$('#send-test-mail-status').html(message);

						$('#send-test-mail-status').addClass('active');

						setTimeout(function (){
							//alert('Hello');
							$('#send-test-mail-status').removeClass('active');

							},

						2000)


						// var message	= data['message'];
						//
						//
						// $(this).html('<i class="fas fa-medal"></i>');
					}
				});
		})





});







