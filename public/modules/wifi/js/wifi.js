$(document).ready(function(){

	$('#datatable').on("click", ".crack_wifi", function() {
		datos = {
			"id":$(this).data("id"),
		}
		$.ajax({
			url: "/wifi/activeprocess",
			headers: {
				'GRANADA-TOKEN':readCookie('token'),
			},
			data:datos,
			type: "put",
			dataType: "json",
			success: function(data) {
				if(data.response==true){
					new PNotify({
						title: 'Wifi',
						text: data.message,
						type: 'success',
						styling: 'bootstrap3'
					});
				}else{
					new PNotify({
						title: 'Error Wfi',
						text: data.message,
						styling: 'bootstrap3'
					});
				}
			},
			error: function(xhr, status, error) {
				new PNotify({
					title: 'Oh No!',
					text: xhr.responseText,
					type: 'error',
					styling: 'bootstrap3'
				});
				var err = eval("(" + xhr.responseText + ")");
				console.log(err);
			}
		});
	});

	$('#datatable').on("click", ".delete_wifi", function() {
		//alert($(this).data("id"));



	});
});