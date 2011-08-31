//------------------------------------------------------------------------------
// Use this file to add any custom jQuery to the form that gets loaaded.
//------------------------------------------------------------------------------

$(function(){
	
	// hide the form and show the loading image whenever the form is submitted
	$('#btn_cw_submit').click(function(){
		
		$('#frm_cw_form').fadeOut(300);
		$('#cw_form_container').addClass('loading');
		
	});
});