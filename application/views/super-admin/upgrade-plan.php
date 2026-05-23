<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('super-admin/layout/header');
$this->load->view('super-admin/layout/sidebar');
$open_projects=3;
$closed_projects=5;
$cancelled_projects=2;
?>
<style>
    .card-pricing.popular {
                z-index: 1;
                border: 1px solid #007bff;
            }
            .card-pricing .list-unstyled li {
                padding: .5rem 0;
                color: #6c757d;
                font-weight: 300;
            }

            .btn{
                border-radius: 1px;
                font-weight:300;
            }
            .hvr:hover{

                color: #fff;
                background-color: #007bff;
                border: 1px solid #007bff !important;
            } 

</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4  class="page-title">Select Plan for Upgradation <?php echo $user->organisation_name;?></h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
 <div class="wrapper-carousel-fix">
 <div class="pricing  row">
 <?php foreach($plan as $row){ ?>
    <?php if(($row->validity_to < date("Y-m-d")) || ($row->validity_from > date("Y-m-d"))){ }else{?> 
        <form action="<?php echo base_url();?>index.php/upgrade-user-save" method="post" id="form-<?php echo $row->id;?>">
        <input type="hidden" value="<?php echo $user->plan_id;?>" name="plan_id">
        <input type="hidden" value="<?php echo $row->id;?>" name="upgrade_plan_id">
        <input type="hidden" value="<?php echo $user->id;?>" name="register_user_id">

        </form>         
 <div class="card card-pricing text-center px-3 mb-4 col-md-3">
                    <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white shadow-sm"><?php echo $row->title;?></span>
                    <div class="bg-transparent card-header pt-2 border-0">
                        <h3 class="mt-0"><?php echo $row->subtitle;?></h3>
                        <h1 class="h1 font-weight-normal text-primary text-center mb-0" data-pricing-value="15">₹<span class="price"><?php echo $row->amount;?></span>
                    
                        <span class="h6 text-muted ml-2">/ per <?php echo $row->time_subscription;?></span></h1>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="list-unstyled mb-4">
                            <li><?php echo $row->allowed_entities_no;?> Entites</li>
                            <li><?php echo $row->location_each_entity;?> Location Each Entity</li>
                            <li><?php echo $row->user_number_register;?> Number of Users</li>
                            <li><?php echo $row->line_item_avaliable;?> Number of Line Items to be available for verification from a singe upload</li>
                        </ul>
                        <?php if($user->plan_id==$row->id){?>
                            <button type="button" class="btn btn-primary mb-3 hvr">Current Plan for <?php echo $user->organisation_name;?></button>

                        <?php }else{?> 
                            <button type="button" class="btn btn-outline-secondary mb-3 hvr" onclick="upgradealert('form-<?php echo $row->id;?>')">Upgrade This Plan for <?php echo $user->organisation_name;?></button>
              <?php }?>
                    </div>
                </div>
    <?php }
} ?>
                
            </div>
 
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

<script>
    
function upgradealert(formid) {
// event.preventDefault(); // prevent form submit
// var form = event.target.form; // storing the form
swal({
        title: `Are you sure ?`,
        text: "You won't be able to revert this!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
        $("#"+formid).submit();
    //  window.location.href="<?php echo base_url();?>index.php/delete-user/"+id;
    }
  });
     
}
</script>