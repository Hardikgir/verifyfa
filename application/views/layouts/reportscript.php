<script>
$('.optradio').click(function(){
    var radioValue = $("input[name='optradio']:checked").val();
    if(radioValue=="project")
	{
        $('#projectSelect').prop('disabled',false);
        $('#projectSelect').attr('required',true);

        $('#exceptioncategory').prop('disabled',false);
        $('#exceptioncategory').attr('required',true);

        $('#projectstatus').prop('disabled',false);
        $('#projectstatus').attr('required',true);

        $('#verificationstatus').prop('disabled',false);
        $('#verificationstatus').attr('required',true);
    	
	}
	else if(radioValue=="consolidated")
	{
        $('#projectSelect').prop('disabled','disabled');
        $('#projectSelect').removeAttr('required');
	}
        else if(radioValue=="additional")
	{
        $('#projectSelect').prop('disabled',false);
        $('#projectSelect').attr('required',true);
       
        $('#exceptioncategory').prop('disabled',true);
        $('#exceptioncategory').attr('required',false);

        $('#projectstatus').prop('disabled',true);
        $('#projectstatus').attr('required',false);

        $('#verificationstatus').prop('disabled',true);
        $('#verificationstatus').attr('required',false);
        
	}
	
});
$(document).ready(function(){
	var radioValue = $("input[name='optradio']:checked").val();
    if(radioValue=="project")
	{
        $('#projectSelect').prop('disabled',false);
        $('#projectSelect').attr('required',true);
    	
	}
	else if(radioValue=="consolidated")
	{
        $('#projectSelect').prop('disabled','disabled');
        $('#projectSelect').removeAttr('required');
	}
});
</script>