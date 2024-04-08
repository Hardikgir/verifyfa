<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('super-admin/layout/header');
$this->load->view('super-admin/layout/sidebar');
?>
<style>
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    width: 100% !important;
    margin: 0 !important;
    margin-bottom: 20px!important;

}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">Subscription View</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
 <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
            <?php foreach($plan as $row) { ?>
               <input type="hidden" name="id" value="<?php echo $row->id;?>">
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Title</label>
                    <?php echo $row->title;?>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Sub-Title</label>
                    <?php echo $row->subtitle;?>
                </div>
                </div><br>
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Breif Description</label>
                    <?php echo $row->description;?>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Entities allowed to be added</label>
                    <?php echo $row->allowed_entities_no;?>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Locations under each entity allowed to be added</label>
                    <?php echo $row->location_each_entity;?>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Users associated with Registered User</label>
                    <?php echo $row->user_number_register;?>
                </div>

                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Line Items to be available for verification from a singe upload</label>
                    <?php echo $row->line_item_avaliable;?>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Highlights</label>
                    <?php echo $row->highlights;?>
                </div>
                </div>
              

                <div class="row">
                <div class="col-md-4 form-row">
                    <label class="form-label"> Validity From (Date)</label>
                    <?php echo date('d-M-Y',strtotime($row->validity_from));?> 
                </div>

                <div class="col-md-4 form-row">
                    <label class="form-label">Validity To (Date)</label>
                    <?php echo date('d-M-Y',strtotime($row->validity_to));?>
                </div>

                <div class="col-md-4 form-row">
                    <label class="form-label">Subscription Type </label>
                    <?php echo ucfirst($row->time_subscription);?>
                 
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Amount </label>
                    RS. <?php echo $row->amount;?>
                </div>
                </div>
              
              <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">About Payment</label>
                    <?php echo $row->about_payment;?>
                 </div>
                </div>
              
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Terms and Conditions:</label>
                    <?php echo $row->terms_condition;?>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Footnotes:</label>
                    <?php echo $row->foot_notes;?>
                </div>
                </div>
              <?php } ?>
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
$this->load->view('super-admin/layout/scripts');
// $this->load->view('super-admin/layouts/dashboard_script');
$this->load->view('super-admin/layout/footer');
?>
