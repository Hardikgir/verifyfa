<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
?>


<link href="<?php echo base_url(); ?>assets/multipledropdown/multiselect.css" rel="stylesheet"/>
<script src="<?php echo base_url(); ?>assets/multipledropdown/multiselect.min.js"></script>
<style>
    #selectUserType_multiSelect {
        width: 100%;
    }
    .multiselect-wrapper ul li.active label{
        color: white;
    }
    .multiselect-count {
        background-color: #5ca1e2;
        color:white;
    }
</style>
<style>
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    width: 100% !important;
}
.usertypeselection {
    margin: 15px 0px;
}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid">

		<div class="container-fluid content-new">

		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">View Issue</h4>
       </div>

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->

                        <div class="row">
                            <div class="col-12">

                            <table class="table table-bordered">
                                <tr>
                                    <th>Subject</th>
                                    <td><?php echo $issue_result->issue_title ?></td>
                                </tr>
                                 <tr>
                                    <th>Description</th>
                                    <td><?php echo $issue_result->issue_description ?></td>
                                </tr>
                            </table>
                                <?php
                                // echo '<pre>';
                                // print_r($issue_result);
                                // echo '</pre>';
                                // exit(); 
                                ?>
                            </div>
                        </div>
              
				</div></section>
				</div>
			</div>
			<!-- Section: Testimonials v.2 -->

		</div>
			<!-- Section: Testimonials v.2 -->

		</div>

			</div>
		</div>
	</div>

</div>
<?php
    $this->load->view('layouts/scripts');
    // $this->load->view('super-admin/layouts/dashboard_script');
    $this->load->view('layouts/footer');
?>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
