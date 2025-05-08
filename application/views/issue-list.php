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
            <h4 class="page-title">Manage Issue</h4>
            <a href="<?php echo base_url();?>index.php/add-issue">
             <button class="btn btn-primary" style="float:right">New Issue</button>
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
            <th>Tracking id</th>
            <th>Subject Title</th>
            <th>Type of issue</th>
            <th>Status</th>
            <th>Raised By</th>
            <th>Resolved By</th>
            <th>Action </th>
            </tr>
        </thead>
        <tbody>
            <?php /* foreach($issue as $row){
                $user_row=get_user_row($row->created_by);
                ?>
            <tr>
                <td ><?php echo $row->type;?></td>
                <td ><?php echo $row->title;?></td>
                <td ><?php echo $row->title;?></td>
                <td ><?php echo $user_row->firstName.' '.$user_row->lastName;?></td>
                <td ><?php 
                if($row->staus =='0'){echo "Progress";}
                if($row->staus =='1'){echo "Resolve & Closed";}
                ?></td>
                <td >
                <select name="change_staus" id="change_status" class="form-control" style="border: 1px solid #cfcaca;padding: 4px;background: aliceblue;font-weight: bold;border-radius: 5px;">
                 <option value="0" <?php if($row->staus =='0'){echo "selected";} ?>>Progress</option>
                 <option value="1" <?php if($row->staus =='1'){echo "selected";} ?>>Resolve & Closed</option>
                </select>    
               
                <td>
                    <a href="<?php echo base_url();?>index.php/view-reply-notofication/<?php echo $row->id;?>">
                     <i class="fa fa-eye"></i>view
                    </a>
                    <!-- ||
                    <a href="#">
                     <i class="fa fa-trash"></i>Delete
                    </a> -->
                </td>
            </tr>
            <?php } */ ?>
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
  title: "Your Entity Create Limit Cross Kindly Connect with Super Admin!",
  text: "",
  icon: "warning",
  buttons: true,
})

}
</script>