<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<style>
    .tooltip-arrow,
.red-tooltip + .tooltip > .tooltip-inner {background-color: #f00;}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">Manage User Role</h4>    

           
            <a href="<?php echo base_url();?>index.php/add-user-role">
             <button class="btn btn-primary" style="float:right">Add User Role</button>
            </a>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
 <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
 <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email Id</th>
                <th>Designation/Department Code/Company Code</th>
                <th>Mapped Company/Shortcode</th>
                <th>Mapped Location/Shortcode</th>
                <th>Mapped User Role</th>
                <th>Action </th>
            </tr>
        </thead>

        <?php  
        $entity_code=$this->admin_registered_entity_code;
        $user_id=$this->user_id;
        $user_role_addmin_cnt=get_user_role_cnt_admin($user_id,$entity_code);

        if(($user_role_addmin_cnt > 0)){                ?>
        <tbody>
            <?php foreach($user_role as $row){
                $userid=$row->user_id;
                $userrow= get_user_row($userid);
                $company_id=$row->company_id;
                $user_company_id=$userrow->company_id;
                $company_row=get_company_row($company_id);
               if($user_company_id !=""){ $user_company_row=get_company_row($user_company_id); $usercom=$user_company_row->short_code;}else{$usercom="";}
                $location_id=$row->location_id;
                $location_row= get_location_row($location_id);
                $user_role=$row->user_role;
                $user_role_exp=explode(",",$user_role);
               if($userrow->department_id !=""){  $departmentrow= get_department_row($userrow->department_id); 
                  $department_shortcode= $departmentrow->department_shortcode;}else{$department_shortcode="";}
                  if($user_role !=''){
                ?>
            <tr>
                <td ><?php echo $userrow->firstName.' '.$userrow->lastName;?></td>
                <td><?php echo $userrow->userEmail;?></td>
                <td><?php echo $userrow->designation;?> / <?php echo $department_shortcode;?> / <?php echo $user_company_row->short_code;?></td>
                <?php if($row->company_id=='0'){ ?>
                    <td>All</td>
                <td>All</td>
                    <?php }else{ ?>
                <td><?php echo $company_row->company_name;?>/<?php echo $company_row->short_code;?></td>
                <td><?php echo $location_row->location_name;?>/<?php echo $location_row->location_shortcode;?></td>
                <?php } ?>
 <td>
<?php 
if($row->company_id=='0'){ 
    echo "Group Admin, ";
}else{
     if (in_array("5", $user_role_exp))
      {
      echo "Group Admin, ";
      }
      if (in_array("4", $user_role_exp))
      {
      echo "Sub Admin, ";
      }
    if (in_array("0", $user_role_exp))
      {
      echo "Manager, ";
      }

      if (in_array("2", $user_role_exp))
      {
      echo "Process Owner, ";
      }
      if (in_array("3", $user_role_exp))
      {
      echo "Entity Owner, ";
      }
      if (in_array("1", $user_role_exp))
      {
      echo "Verifier";
      }
    }
  ?>

                </td>
               

                <td>
                <?php if($userid == $this->user_id){ ?>
                    <a disabled>
                     <i class="fa fa-edit"></i>Edit
                    </a>
                    <?php }else{ ?>
                    <a href="<?php echo base_url();?>index.php/edit-user-role/<?php echo $row->id;?>">
                     <i class="fa fa-edit"></i>Edit
                    </a>
                    <?php } ?>
                   
                
                </td>
            </tr>
            <?php } } ?>
        </tbody>
        <?php }else{
            $company_id_n =get_user_role_company($this->user_id);
            $location_id_n =get_user_role_location($this->user_id);
            $user_role =get_user_role_row($company_id_n,$location_id_n);
        //    $user_role =get_user_role_row($company_id_n,$location_id_n);
           
            ?>

            <tbody>
            <?php foreach($user_role as $row){



                 $user_row=get_user_row($row->user_id);
                // forusercom//
                 $usercom=get_company_row($user_row->company_id);
                 $role_row =  get_user_role_rowsubadmin($user_row->id,$row->company_id,$row->location_id);
                 $department_row= get_department_row($user_row->department_id);
                //  for mapped user//
                $cnt_role=count($role_row);
                    // if($cnt_role!=0){
                 $user_role_exp=explode(",",$row->user_role);
                 $usermappedcom=get_company_row($row->company_id);
                 $usermappedloc=get_location_row($row->location_id);
                ?>
            <tr>
                <td ><?php echo $user_row->firstName.' '.$user_row->lastName;?></td>
                <td><?php echo $user_row->userEmail;?></td>
                <td><?php echo $user_row->designation;?> / <?php echo $department_row->department_shortcode;?> / <?php echo $usercom->short_code;?></td>
                <td><?php echo $usermappedcom->company_name;?>/<?php echo $usermappedcom->short_code;?></td>
                <td><?php echo $usermappedloc->location_name;?>/<?php echo $usermappedloc->location_shortcode;?></td>
 <td>
<?php 
     if (in_array("5", $user_role_exp))
      {
    //   echo "Group Admin, ";
      }
      if (in_array("4", $user_role_exp))
      {
      echo "Sub Admin, ";
      }
    if (in_array("0", $user_role_exp))
      {
      echo "Manager, ";
      }

      if (in_array("2", $user_role_exp))
      {
      echo "Process Owner, ";
      }
      if (in_array("3", $user_role_exp))
      {
      echo "Entity Owner, ";
      }
      if (in_array("1", $user_role_exp))
      {
      echo "Verifier";
      }
  ?>

                </td>
               

                <td>
                    <?php if($row->user_id == $this->user_id){ ?>
                    <a disabled>
                     <i class="fa fa-edit"></i>Edit
                    </a>
                    <?php }else{ ?>
                    <a href="<?php echo base_url();?>index.php/edit-user-role/<?php echo $row->id;?>">
                     <i class="fa fa-edit"></i>Edit
                    </a>
                    <?php } ?>
                   
                
                </td>
            </tr>
            <?php 
            // }
         } }?>
        </tbody>
       
      <?php //} ?>

        </table>
					</div>			
				</section>
				</div>
			</div>
			<!-- Section: Testimonials v.2 -->
		
		</div>	
		
			</div>
		</div>
	</div>
	
</div>


<?php
$this->load->view('layouts/scripts');
// $this->load->view('layouts/layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
<script>
	$(document).ready(function () {
		$('#example').DataTable();
	});
</script>
<script>
    $(".destitle").click(function(){
        var heading =$(this).html();
        var content =$(this).attr('data');
        $("#content").html(content);
        $("#headingtitle").html(heading);
        $("#subsdes").trigger("click");
    })
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    function alertuser(){

 swal({
  title: "Your User Create Limit Cross Kindly Connect with Super Admin!",
  text: "",
  icon: "warning",
  buttons: true,
})

}
</script>