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
            <h4 class="page-title">Manage User</h4>    

            <?php 
            $total_user_count= count($user);
            $plan_user_cnt=create_user_count_check($this->admin_registered_user_id);
            if($total_user_count >= $plan_user_cnt){
                ?>
                 <button class="btn btn-primary" style="float:right" onclick="alertuser();">Create New User</button>
            <?php }else{?>
                 
            <a href="<?php echo base_url();?>index.php/admin-create-user">
             <button class="btn btn-primary" style="float:right">Create New User</button>
            </a>
        <?php } ?>
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
                <th>Phone No</th>
                <th>Department</th>
                <th>Company</th>
                <th>Location</th>
                <th>Action </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($user as $row){
                $dept_row = get_department_row($row->department_id);
                if($row->company_id !=0){
                $company_row = get_company_row($row->company_id);
                $comapny_name=$company_row->company_name;
                }else{
                    $comapny_name='';
                }
                if($row->location_id !=0){
                    $location_row = get_location_row($row->location_id);
                    $location_name=$location_row->location_name;
                    }else{
                        $location_name='';
                    }
                ?>
            <tr>
                <td ><?php echo $row->firstName.' '.$row->lastName;?></td>
                <td><?php echo $row->userEmail;?></td>
                <td><?php echo $row->phone_no;?></td>
                <td><?php if($dept_row !=''){ echo $dept_row->department_name;}?></td>
                <td><?php echo $comapny_name;?></td>
                <td><?php echo $location_name;?></td>

               

                <td>
                    <a href="<?php echo base_url();?>index.php/edit-admin-user/<?php echo $row->id;?>">
                     <i class="fa fa-edit"></i>Edit
                    </a>
                    <?php $prjcnt = check_user_projects($row->id);
                    if($prjcnt > 0){?>
                    <a href="#" onclick="alertnotdelete()">
                      <i class="fa fa-trash"></i>Delete
                    </a>
                    <?php }else{
                    ?>
                    <a href="#" onclick="archiveFunction('<?php echo $row->id;?>')">
                     <i class="fa fa-trash"></i>Delete
                    </a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
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
  title: "Total Number of Users Limit exceeded. Kindly connect with Super Admin!",
  text: "",
  icon: "warning",
  buttons: true,
})

}
</script>



<script>
function alertnotdelete(){

 swal({
  title: "Your are not able to delete this user. Project in progress",
  text: "",
  icon: "warning",
  buttons: true,
})

}

</script>

<script>
    
function archiveFunction(id) {
    swal({
              title: `Are you sure ?`,
              text: "You won't be able to revert this!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
             window.location.href="<?php echo base_url();?>index.php/delete-user/"+id;
            }
          });
     
}</script>