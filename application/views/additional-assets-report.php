<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<style>
    .tooltip-arrow,
.red-tooltip + .tooltip > .tooltip-inner {background-color: #f00;}
#example_wrapper{ text-align: left;}
.buttons-excel{
    background: #5ca1e2 !important;
    color: #fff !important;
    font-size: 20px;
}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">Additional Assets List Report</h4>

        
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
 <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
 <table id="example" class="display" style="width:100%">
        <thead>
            
            <tr>
                <th>Project Name</th>
                <th>Project ID</th>
                <th>Asset Category</th>
                <th>Asset Classification</th>
                <th>Asset Description</th>
                <th>Quantity Verified</th>
                <th>Location</th>
                <th>Condition Assets</th>
                <th style="display: none;">Make</th>
                <th style="display: none;">Model</th>
                <th  style="display: none;"> Serial No</th>
                <th>Temporary Verification ID/ Ref</th>
                <th  style="display: none;">Expected Unit Cost</th>
                <th  style="display: none;">Any Other Detail</th>
                <th>Verifier Name</th>
                <th> Verified on (Date & Time)</th>
               
            </tr>
            
           
        </thead>
        <tbody>
        
       
            <?php foreach($data as $row){ 
               $prjrow= get_project_row($row->project_id);
                ?>
            <tr>
           
               

                <td><?php echo $prjrow->project_name;?></td>
                <td><?php echo $prjrow->project_id;?></td>
                <td ><?php echo $row->asset_category;?></td>
                <td><?php echo $row->asset_classification;?></td>
                <td><?php echo $row->description_of_asset;?></td>
                <td><?php echo $row->qty_verified;?></td>

                <td><?php echo $row->current_location;?></td>
                <td><?php echo $row->condition_of_assets;?></td>
                <td  style="display: none;"><?php echo $row->make;?></td>
                <td  style="display: none;"><?php echo $row->model;?></td>
                <td  style="display: none;"><?php echo $row->serial_no;?></td>
                <td><?php echo $row->temp_verifiction_id_ref;?></td>
                <td  style="display: none;"><?php echo $row->expected_unit_cost;?></td>
                <td  style="display: none;"><?php echo $row->any_other_details_unit_cost;?></td>
                <td><?php echo $row->verified_name;?></td>
                <td><?php echo date("d-M-Y G:i:a",strtotime($row->updated_at));?></td>
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
		$('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [{
                    extend: 'excel',
                    text: 'Download Report',
                    filename: '<?php echo $comapny_row->company_name;?>-<?php echo $location_row->location_name;?>-additional-data',
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                       $('c[r=A1] t', sheet).text( '<?php echo $comapny_row->company_name;?> \n <?php echo $location_row->location_name;?> \n List of Additional Assets Verified' );
                    //    $('c[r=A1] t', sheet).text( 'Custom text' );
                    //    $('c[r=A1] t', sheet).text( 'Custom text' );
 
                        // Loop over the cells in column `F`
                        $('row c[r^="G"] ', sheet).each( function () {
                            // Get the value and strip the non numeric characters
 
                               if ( $( this).text() !== "needed Adjustment" ) {
                $(this).attr( 's', '20' );
            }
 
                        });
                    }
 
 
                        }]
    } );

    // $(".buttons-excel").html("Download Report");
	});
</script>
