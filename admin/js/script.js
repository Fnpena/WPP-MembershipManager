jQuery(document).ready(function($)
{
    'use strict';
    /*+++++++++++++++++++++++++++++++++++++++++++
                    Declare Modal
     +++++++++++++++++++++++++++++++++++++++++++++*/
    var exec_mode = 'A';
    
    function reset_fields()
    {
        $('#personalid-input').attr('disabled','false');
        $('#firstname-input').attr('disabled','false');
        $('#lastname-input').attr('disabled','false');
        
        $('#personalid-input').val('');
        $('#firstname-input').val('');
        $('#lastname-input').val('');
        
        $('#hf_courses').val('');        
        $('.divPanel').html('');
        $('#hfcurrent_user').val('');
    }
    
    $('#NewStudentModal').dialog({
		autoOpen: false,
		height:550,
		width:700,
		modal: true,
        open: function( event, ui ) 
        {
            $('#NewStudentModal').attr('display','inline');
            
            if($('#hfmodal-mode').val()=='Z')
             {  
                 //alert($('#hfmodal-index').val());
                exec_mode = 'U';
                 
                $.ajax
                ({
                    url: gimcl_ajaxform.url,
                    method:'POST',
                    dataType:'json',
                    data:
                    {
                        action: 'gimcl_GetStudentProfile',
                        nonce: gimcl_ajaxform.myvalidator,
                        userid:$('#hfmodal-index').val()
                    },
                    success: function(data)
                    {
                        if(data.response == 'OK')
                        {
                            //console.log(data.firstname);
                            //console.log(data.lastname);
                            //console.log(data.personal_id);
                            //console.log(data.course_list);
                            //console.log(data.course_listRAW);
                            $('#hfcurrent_user').val($('#hfmodal-index').val());
                            
                            $('#personalid-input').val(data.personal_id);
                            $('#firstname-input').val(data.firstname);
                            $('#lastname-input').val(data.lastname);
                            
                            $('#personalid-input').attr('disabled','true');
                            $('#firstname-input').attr('disabled','true');
                            $('#lastname-input').attr('disabled','true');

                        
                            //Hidden Field Course List Data
                            $('#hf_courses').val(data.course_listRAW);        

                            var tmp_tblCourse = $('.divPanel').html();
                            var newRow = tmp_tblCourse;
                            newRow += data.course_list;
                            $('.divPanel').html(newRow);
                        }
                    }
                }); 
                 
             }
            else
             {  //alert('Modal Regular Mode');
                exec_mode = 'A';
             }
        },
		buttons:
		{
			"Guardar": function()
			{
                //alert('EXEC MODE:'+exec_mode);
                $.ajax
                ({
                    url: gimcl_ajaxform.url,
                    method:'POST',
                    dataType:'json',
                    data:
                    {
                        action: 'gimcl_AsyncHandler',
                        nonce: gimcl_ajaxform.myvalidator,
                        userid:$('#personalid-input').val(),
                        current_user:$('#hfcurrent_user').val(),
                        firstname:$('#firstname-input').val(),
                        lastname:$('#lastname-input').val(),
                        arr_course:$('#hf_courses').val(),
                        mode:exec_mode
                    },
                    success: function(data)
                    {
                        if(data.response == 'OK')
                        {
                            console.log('inserted ID:'+data.insert_id);
                            alert(data.message);
                            //1.1 mostrar pop-up con resultado de insercion
                            //2. actualizar informacion de tabla
                            reset_fields();
                            $(this).dialog('close'); 
                        }
                    }
                });
			},
			"Cerrar": function()
            {
                reset_fields();
                $(this).dialog('close'); 
            }
		}
	});
    
    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                            Logic Modal Edit Student 
    ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    
    var $actionBtnEdit = $('.btnEditStudent');
	$actionBtnEdit.on('click',function()
	{
        $('#hfmodal-mode').val('Z');
        $('#hfmodal-index').val($(this).attr('item-code'));
        $('#NewStudentModal').dialog('open');
    });
    
    
    /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    
    /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                            Course Toggle Logic
    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    $('.btnDisplay').hover(
        function()
        { 
            $('.'+$(this).attr('item')).addClass('div-active');
        }, 
        function()
        {
            $('.div-active').animate({
                height: 'toggle'
            });
            $('.'+$(this).attr('item')).removeClass('div-active');
        }
    );
    
    $('.btnDisplay').on('click',function()
    {
        $('.div-active').animate({
            height: 'toggle'
        });
    });
    
    $('.div-hidden').animate({
        height: 'toggle'
    });
    
    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	
	
    var $actionBtn = $('.btnAddRegistry');
	$actionBtn.on('click',function()
	{
        $('#hfmodal-mode').val('X');
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
        
        var tmp_tblCourse = $('.divPanel').html();
        var newRow = tmp_tblCourse;
        newRow += "<div class='row'><div class='col-sm-3'>"+cu_code+"</div>";
        newRow += "<div class='col-sm-7'>"+cu_desciption+"</div>";
        newRow += "<div class='col-sm-2'>"+cu_duration+"</div></div>";
        
        $('.divPanel').html(newRow);
        
        
        //Clean Fields
        $('#course-code-input').val('');
        $('#course-description-input').val('');
        $('#course-duration-input').val('');
    });
    
});