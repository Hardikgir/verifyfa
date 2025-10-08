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
            <h4 class="page-title">Issue For Me</h4>           
       </div>   

        <!-- section vise   -->
        <div class="col-lg-12 mt-4 mb-4" style="width: 100%;text-align: center;">
        <!-- tushar -->
       <div class="btn-group mb-3">
        <?php 
        $slugs = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
        
        if(isset($slugs[4])){
        // exit();
        ?>
    <a href="<?php echo base_url('index.php/issue-for-me/groupadmin'); ?>" 
       class="btn <?php echo ((isset($slugs[4]) && $slugs[4] == 'groupadmin')) ? 'btn-primary' : ''; ?> mx-2">
        Group Admin
    </a>
    <a href="<?php echo base_url('index.php/issue-for-me/manager'); ?>" 
       class="btn <?php echo ((isset($slugs[4]) && $slugs[4] == 'manager')) ? 'btn-primary' : ''; ?> mx-2">
        Manager
    </a>
    <?php } ?>
</div>
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
            <th>Type of Issue</th>
            <th>Project Id</th>
            <th>Status</th>
            <th>Status Type</th>
            <th>Action </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($issue as $row){
                $user_row=get_user_row($row->created_by);
                ?>
            <tr>
                <td ><?php echo $row->tracking_id;?></td>
                <td ><?php echo $row->issue_title;?></td>
                <td ><?php echo $row->issue_type;?></td>
                <td >
                    <?php echo $row->project_code; ?>
                </td>
                <td ><?php 
                if($row->status =='1'){echo "Open";}
                if($row->status =='0'){echo "Closed";}
                ?></td>
                <td >
              <?php 
                if($row->status_type =='1'){echo "New";}
                if($row->status_type =='2'){echo "Escalted";}
                ?>  
               </td>
                <td>
                    <a href="<?php echo base_url();?>index.php/view-issue/<?php echo $row->id;?>">
                     <i class="fa fa-eye"></i>view
                    </a>
                    <!-- ||
                    <a href="#">
                     <i class="fa fa-trash"></i>Delete
                    </a> -->
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
  title: "Your Entity Create Limit Cross Kindly Connect with Super Admin!",
  text: "",
  icon: "warning",
  buttons: true,
})

}
</script>