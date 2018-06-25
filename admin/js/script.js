jQuery(document).ready(function($)
{
	//console.log( gims_testing );
	var $testingBtn = $('.random-x');
	$testingBtn.on('click',function()
	{
			$.ajax
			({
				url: gims_testing.url,
				method:'POST',
				dataType:'json',
				data:
				{
					action: 'gims_generatecard',
					nonce: gims_testing.myvalidator,
					dato_prueba:'HOLA MUNDO'
				},
				success: function(data)
				{
					console.log(data.resultado);
				}
			});
	}); 
});