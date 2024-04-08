<script>
$('#continuePlan').click(function(){
    $('.error-message').remove();
    if($('#company_name').val() =='Select Company Name')
    {
        $('#company_name').after('<span class="error-message">Please Select Company Name</span>');
        $('#company_name').addClass('has-error');
        return false;
    }
    if($('#company_location').val() =='Select Unit Location')
    {
        $('#company_location').after('<span class="error-message">Please Select Unit Location</span>');
        $('#company_location').addClass('has-error');
        return false;
    }
    if($('#project_file').val() =='')
    {
        $('#project_file').after('<span class="error-message">Please Select Project File</span>');
        $('#project_file').addClass('has-error');
        return false;
    }
    $('#modalPlanningForm').modal('show');
});
$('.custom-select').on('change',function(){
    $(this).removeClass('has-error');
    $(this).siblings('.error-message').remove();
});
$('.fileinput').on('change',function(){
    $(this).removeClass('has-error');
    $(this).siblings('.error-message').remove();
});
$(document).on('click','#confirmUpload',function(){
    $('.error-message').remove();
    if(!($('#singleworksheet').is(':checked')) && !($('#multipleworksheet').is(':checked')))
    {
        $('#singleworksheet').parent('.row').append('<span class="error-message">Please Select Worksheet</span>');
        return false;
    }
    if($('#cnfmandatory').prop("checked") != true)
    {
        $('#cnfmandatory').parent('.pop_up_label').after('<span class="error-message">Please Confirm</span>');
        return false;
    }
    if($('#cnfmerge').prop("checked") != true)
    {
        $('#cnfmerge').parent('.pop_up_label').after('<span class="error-message">Please Confirm</span>');
        return false;
    }
    if($('#cnflines').prop("checked") != true)
    {
        $('#cnflines').parent('.pop_up_label').after('<span class="error-message">Please Confirm</span>');
        return false;
    }
    if($('#confblank').prop("checked") != true)
    {
        $('#confblank').parent('.pop_up_label').after('<span class="error-message">Please Confirm</span>');
        return false;
    }
    if($('#confformat').prop("checked") != true)
    {
        $('#confformat').parent('.pop_up_label').after('<span class="error-message">Please Confirm</span>');
        return false;
    }
    $('#uploadForm').submit();

    
});
$('.resetform').click(function(){
    $('input[type=checkbox].nonmandatory').prop('checked',false);
});
$('.editheaderchecks').click(function(){
    var id=$(this).attr('id');
    var inputName=id.split("-");
    if($(this).prop("checked"))
    {
        $('input#'+inputName[0]+'-key').prop("disabled",false);
        $('input#'+inputName[0]+'-label').prop("disabled",false);
        $(this).siblings('#'+inputName[0]+'_editval').prop("disabled",true);
        $('input[type=checkbox]#'+inputName[0]+'-show').prop("checked",true);
        $('input[type=checkbox]#'+inputName[0]+'-show').val(1);
        $(this).val(1);
    }
    else
    {
        $(this).val(0);
        $(this).siblings('#'+inputName[0]+'_editval').prop("disabled",false);
    }
});
$('.headerchecks').click(function(){
    var id=$(this).attr('id');
    var inputName=id.split("-");
    if($(this).prop("checked"))
    {
        $('input#'+inputName[0]+'-key').prop("disabled",false);
        $('input#'+inputName[0]+'-label').prop("disabled",false);
        $('input#'+inputName[0]+'_editval').prop("disabled",false);
        $(this).val(1);
    }
    else
    {
        $(this).val(0);
        $('input#'+inputName[0]+'-key').prop("disabled",true);
        $('input#'+inputName[0]+'-label').prop("disabled",true);
        $('input#'+inputName[0]+'_editval').prop("disabled",false);
        $('input[type=checkbox]#'+inputName[0]+'-edit').prop("checked",false);
        $('input[type=checkbox]#'+inputName[0]+'-edit').val(0);
    }
});
$(document).on('change','.project_type',function()
{
    var projectname=$('#table_name').val();
    var ptype=$(this).val();
    $.ajax({
        'url':'<?php echo base_url();?>index.php/plancycle/getprojectcategories',
        'data':'ptype='+ptype+'&table_name='+projectname,
        'method':'post',
        'success':function(res){
            $('#item_category').html('');
            $('#item_category').append('<option selected>Item Category<span id="mandatory_star">*</span></option>');
            var cats=JSON.parse(res);
            cats.forEach(function(item,value){
                $('#item_category').append('<option value="'+item.item_category+'">'+item.item_category+'</option>');
            });
        }
    });
});
</script>
<style>
    .has-error{
        border:1px solid red;
    }
    .error-message{
        color:red;
        font-size:12px;
        padding:5px 0px;
    }
</style>