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
            <h4 class="page-title">Manage Company</h4>

            <?php 
               $admin_registered_user_id = $this->admin_registered_user_id;
               $total_entity_create_permission = get_create_entity_plan($admin_registered_user_id);
               $cntallrow=count($companydata);
               if($cntallrow >= $total_entity_create_permission ){ ?>
                 <button class="btn btn-primary" onclick="alertentity()" style="float:right">Create New Company</button>
              <?php }else{ ?>
            <a href="<?php echo base_url();?>index.php/admin-create-entity">
             <button class="btn btn-primary" style="float:right">Create New Company</button>
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
                <th>Company Name</th>
                <th>Company Shortcode</th>

                <th>Action </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($companydata as $row){ ?>
            <tr>
                <td ><?php echo $row->company_name;?></td>
                <td><?php echo $row->short_code;?></td>
               

                <td>
                    <a href="<?php echo base_url();?>index.php/edit-entity/<?php echo $row->id;?>">
                     <i class="fa fa-edit"></i>Edit
                    </a>
                    ||
                    <?php $prjcnt = check_company_projects($row->id);
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
function alertentity(){

 swal({
  title: "Total Number of Companies Limit exceeded. Kindly connect with Super Admin!",
  text: "",
  icon: "warning",
  buttons: true,
})

}
</script>



<script>
function alertnotdelete(){

 swal({
  title: "Your are not able to delete this company. Project in progress",
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
             window.location.href="<?php echo base_url();?>index.php/delete-entity/"+id;
            }
          });
     
}</script>