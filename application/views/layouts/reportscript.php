<script>
$('.optradio').click(function(){
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