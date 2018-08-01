jQuery(document).ready(function($)
{
    'use strict';
    /*+++++++++++++++++++++++++++++++++++++++++++
                    Declare Modal
     +++++++++++++++++++++++++++++++++++++++++++++*/
    
    $('#NewStudentModal').dialog({
		autoOpen: false,
		height:550,
		width:700,
		modal: true,
		buttons:
		{
			"Guardar": function()
			{
                //Init AJAX Call 
                $.ajax
                ({
                    url: gimcl_ajaxform.url,
                    method:'POST',
                    dataType:'json',
                    data:
                    {
                        action: 'gimcl_AsyncHandler',
                        nonce: gimcl_ajaxform.myvalidator,
                        redata_userid:$('#personalid-input').val(),
                        redate_firstname:$('#firstname-input').val(),
                        redata_lastname:$('#lastname-input').val(),
                        redata_arr_course:''
                    },
                    success: function(data)
                    {
                    
                    }
                });
			},
			"Cerrar": function()
            {
                $(this).dialog('close'); 
            }
		}
	});
	
	
    var $actionBtn = $('.btnAddRegistry');
	$actionBtn.on('click',function()
	{
        $('#NewStudentModal').dialog('open');
    });
    
    //Create Add New Course to Temporal table logic
    
    var $BtnAddCourse = $('.btnAddNewRow');
	$BtnAddCourse.on('click',function()
	{
        var cu_code = $('#course-code-input').val();
        var cu_desciption = $('#course-description-input').val();
        var cu_duration = $('#course-duration-input').val();
        
        var CurrentRow = cu_code+','+cu_desciption+','+cu_duration+'|';
        var CurrentHF = $('#hf_courses').val();
        CurrentHF += CurrentRow;
        
        $('#hf_courses').val(CurrentHF);        
        
        //Clean Fields
        $('#course-code-input').val('');
        $('#course-description-input').val('');
        $('#course-duration-input').val('');
    });
    
});