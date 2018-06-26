jQuery(document).ready(function($)
{
	//console.log( gims_testing );
	var $actionBtn = $('#doaction');
	$actionBtn.on('click',function()
	{
		var $results_tx = $('.cbk_id:checked').map(function()
		{
			return $(this).val();
		});
		
		$.ajax
		({
			url: gims_testing.url,
			method:'POST',
			dataType:'json',
			data:
			{
				action: 'gims_generateCard',
				nonce: gims_testing.myvalidator,
				requested_ids:$results_tx.get()
			},
			success: function(data)
			{
				//console.log(data.resultado);
				alert(data.response_data);
				//$('#myModal').css("display", "block");
				//$('#GC_display').html(data.response_data);	
			}
		});
	}); 
});