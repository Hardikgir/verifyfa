<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<style>
table th,table td{
    padding:5px;
    font-size: 0.875rem;
}
.btn-blue{
    background: #5B96CE !important;
    color: white !important;
}
</style>
	<div class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
                            
                            <?php
                            $unspecifieddata=$data[0]['unallocated']; 
                            if(isset($_SESSION['error_message']))
							{
							?>
							<div class="alert alert-danger">
								<?php echo $_SESSION['error_message']['message']; ?>
							</div>
							<?php 
							}
							?>
							<div class="card">
								
								<div class="card-header card-header-primary">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 class="card-title">Reports </h4> 
                                        </div>
                                        <div class="col-md-6">
                                            <a href="<?php echo base_url(); ?>index.php/dashboard/reports"><button class="btn btn-round pull-right">Back</button></a>
                                        </div>
                                    </div>
								</div>
								<?php 
								if($data[0]['type']=='consolidated')
								{
								?>
								<form>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="project" class="optradio" disabled> Project based </label></span>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="consolidated" class="text-center optradio" checked > Consolidated </label></span>
													</div>
												</div>
											</div>
										</div>
										
										
									</div>
									
									<div class="clearfix"></div>
								</form>
								<div class="col-md-12">
									<table border="1">
										<tr>
											<th rowspan="2">Allocated Item Category</th>
											<th colspan="2">Total as per FAR</th>
											
											<th colspan="2">Tagged</th>
											<th colspan="2">Non-Tagged</th>
											<th colspan="2">Unspecified</th>
											
										</tr>
										<tr>
											
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
                                        </tr>
                                        <tr><th colspan="9" style="text-align:left;color:green;">Allocated Projects: </th></tr>
                                        <?php
                                        $totalAmount=0;
                                        $totalItems=0;
                                        $taggedTotalAmount=0;
                                        $taggedTotalItems=0;
                                        $nontaggedTotalAmount=0;
                                        $nontaggedTotalItems=0;
                                        $unspecifiedTotalAmount=0;
                                        $unspecifiedTotalItems=0;
                                        foreach($data as $data)
                                        {
                                            $subtotalAmount=0;
                                            $subtotalItems=0;
                                            $subtaggedTotalAmount=0;
                                            $subtaggedTotalItems=0;
                                            $subnontaggedTotalAmount=0;
                                            $subnontaggedTotalItems=0;
                                            $subunspecifiedTotalAmount=0;
                                            $subunspecifiedTotalItems=0;
                                        ?>
                                            <tr><th colspan="9"  style="text-align:left;"><a href="<?php echo base_url(); ?>index.php/dashboard/downloadProjectReportAllocated/<?php echo  $data['project']->id; ?>" style="color:#5CA1E2;"><?php echo $data['project']->project_name;?>:</a></th></tr>
                                        <?php
                                            foreach($data['all'] as $allcat)
                                            {
                                                $taggedAmount=0;
                                                $taggedItems=0;
                                                $unspecifiedAmount=0;
                                                $unspecifiedItems=0;
                                                $nontaggedAmount=0;
                                                $nontaggedItems=0;
                                                $totalAmount=$totalAmount+$allcat->total_amount;
                                                $totalItems=$totalItems+$allcat->items;
                                                $subtotalAmount=$subtotalAmount+$allcat->total_amount;
                                                $subtotalItems=$subtotalItems+$allcat->items;
                                                foreach($data['tagged'] as $tagged)
                                                {
                                                    if($tagged->item_category==$allcat->item_category)
                                                    {
                                                        $taggedAmount=$tagged->total_amount;
                                                        $taggedItems=$tagged->items;
                                                        $taggedTotalAmount=$taggedTotalAmount+$taggedAmount;
                                                        $taggedTotalItems=$taggedTotalItems+$taggedItems;
                                                        $subtaggedTotalAmount=$subtaggedTotalAmount+$taggedAmount;
                                                        $subtaggedTotalItems=$subtaggedTotalItems+$taggedItems;
                                                    }
                                                   
                                                }
                                                foreach($data['nontagged'] as $nontagged)
                                                {
                                                    if($nontagged->item_category==$allcat->item_category)
                                                    {
                                                        $nontaggedAmount=$nontagged->total_amount;
                                                        $nontaggedItems=$nontagged->items;
                                                        $nontaggedTotalAmount=$nontaggedTotalAmount+$nontaggedAmount;
                                                        $nontaggedTotalItems=$nontaggedTotalItems+$nontaggedItems;
                                                        $subnontaggedTotalAmount=$subnontaggedTotalAmount+$nontaggedAmount;
                                                        $subnontaggedTotalItems=$subnontaggedTotalItems+$nontaggedItems;
                                                    }
                                                    
                                                }
                                                foreach($data['unspecified'] as $unspecified)
                                                {
                                                    if($unspecified->item_category==$allcat->item_category)
                                                    {
                                                        $unspecifiedAmount=$unspecified->total_amount;
                                                        $unspecifiedItems=$unspecified->items;
                                                        $unspecifiedTotalAmount=$unspecifiedTotalAmount+$unspecifiedAmount;
                                                        $unspecifiedTotalItems=$unspecifiedTotalItems+$unspecifiedItems;
                                                        $subunspecifiedTotalAmount=$subunspecifiedTotalAmount+$unspecifiedAmount;
                                                        $subunspecifiedTotalItems=$subunspecifiedTotalItems+$unspecifiedItems;
                                                    }
                                                   
                                                }
										
										?>
										<tr>
                                            <td><?php echo $allcat->item_category; ?></td>
                                            <td><?php echo $allcat->total_amount!=0?getmoney_format(round(($allcat->total_amount/100000),2)):$allcat->total_amount; ?></td>
                                            <td><?php echo $allcat->items; ?></td>
                                            <td><?php echo $taggedAmount!=0?getmoney_format(round(($taggedAmount/100000),2)):$taggedAmount; ?></td>
                                            <td><?php echo $taggedItems; ?></td>
                                            <td><?php echo $nontaggedAmount!=0?getmoney_format(round(($nontaggedAmount/100000),2)):$nontaggedAmount; ?></td>
                                            <td><?php echo $nontaggedItems; ?></td>
                                            <td><?php echo $unspecifiedAmount!=0?getmoney_format(round(($unspecifiedAmount/100000),2)):$unspecifiedAmount; ?></td>
                                            <td><?php echo $unspecifiedItems; ?></td>
                                        </tr>
										<?php
                                            }
                                            ?>
                                            <tr>
                                            <th><?php echo "Sub Total"; ?></th>
                                            <td><?php echo $subtotalAmount!=0?getmoney_format(round(($subtotalAmount/100000),2)):$subtotalAmount; ?></td>
                                            <td><?php echo $subtotalItems; ?></td>
                                            <td><?php echo $subtaggedTotalAmount!=0?getmoney_format(round(($subtaggedTotalAmount/100000),2)):$subtaggedTotalAmount;; ?></td>
                                            <td><?php echo $subtaggedTotalItems; ?></td>
                                            <td><?php echo $subnontaggedTotalAmount!=0?getmoney_format(round(($subnontaggedTotalAmount/100000),2)):$subnontaggedTotalAmount;; ?></td>
                                            <td><?php echo $subnontaggedTotalItems; ?></td>
                                            <td><?php echo $subunspecifiedTotalAmount!=0?getmoney_format(round(($subunspecifiedTotalAmount/100000),2)):$subunspecifiedTotalAmount;; ?></td>
                                            <td><?php echo $subunspecifiedTotalItems; ?></td>
                                        </tr>
                                        <?php
                                        }
                                        if(count($unspecifieddata['all'])>0)
                                        {
                                            $subtotalAmount=0;
                                            $subtotalItems=0;
                                            $subtaggedTotalAmount=0;
                                            $subtaggedTotalItems=0;
                                            $subnontaggedTotalAmount=0;
                                            $subnontaggedTotalItems=0;
                                            $subunspecifiedTotalAmount=0;
                                            $subunspecifiedTotalItems=0;
                                        ?>
                                            <tr><th colspan="9"  style="text-align:left;"><a href="<?php echo base_url(); ?>index.php/dashboard/downloadProjectReportUnallocated/<?php echo  $data['project']->id; ?>" style="color:#5CA1E2;"><?php echo 'Unallocated';?>:</a></th></tr>
                                        <?php
                                            
                                        foreach($unspecifieddata['all'] as $allcat)
                                        {
                                         $taggedAmount=0;
                                         $taggedItems=0;
                                            $unspecifiedAmount=0;
                                            $unspecifiedItems=0;
                                            $nontaggedAmount=0;
                                            $nontaggedItems=0;
                                            $totalAmount=$totalAmount+$allcat->total_amount;
                                            $totalItems=$totalItems+$allcat->items;
                                            $subtotalAmount=$subtotalAmount+$allcat->total_amount;
                                            $subtotalItems=$subtotalItems+$allcat->items;
                                            
                                            
                                            
                                            foreach($unspecifieddata['tagged'] as $tagged)
                                            {
                                                if($tagged->item_category==$allcat->item_category)
                                                {
                                                    $taggedAmount=$tagged->total_amount;
                                                    $taggedItems=$tagged->items;
                                                    $taggedTotalAmount=$taggedTotalAmount+$taggedAmount;
                                                    $taggedTotalItems=$taggedTotalItems+$taggedItems;
                                                    $subtaggedTotalAmount=$subtaggedTotalAmount+$taggedAmount;
                                                    $subtaggedTotalItems=$subtaggedTotalItems+$taggedItems;
                                                }
                                                
                                            }
                                            foreach($unspecifieddata['nontagged'] as $nontagged)
                                            {
                                                if($nontagged->item_category==$allcat->item_category)
                                                {
                                                    $nontaggedAmount=$nontagged->total_amount;
                                                    $nontaggedItems=$nontagged->items;
                                                    $nontaggedTotalAmount=$nontaggedTotalAmount+$nontaggedAmount;
                                                    $nontaggedTotalItems=$nontaggedTotalItems+$nontaggedItems;
                                                    $subnontaggedTotalAmount=$subnontaggedTotalAmount+$nontaggedAmount;
                                                    $subnontaggedTotalItems=$subnontaggedTotalItems+$nontaggedItems;
                                                }
                                                
                                            }
                                            foreach($unspecifieddata['unspecified'] as $unspecified)
                                            {
                                                if($unspecified->item_category==$allcat->item_category)
                                                {
                                                    $unspecifiedAmount=$unspecified->total_amount;
                                                    $unspecifiedItems=$unspecified->items;
                                                    $unspecifiedTotalAmount=$unspecifiedTotalAmount+$unspecifiedAmount;
                                                    $unspecifiedTotalItems=$unspecifiedTotalItems+$unspecifiedItems;
                                                    $subunspecifiedTotalAmount=$subunspecifiedTotalAmount+$unspecifiedAmount;
                                                    $subunspecifiedTotalItems=$subunspecifiedTotalItems+$unspecifiedItems;
                                                }
                                                
                                            }
                                    
                                        ?>
                                        <tr>
											<td><?php echo $allcat->item_category; ?></td>
											<td><?php echo $allcat->total_amount!=0?getmoney_format(round(($allcat->total_amount/100000),2)):$allcat->total_amount; ?></td>
											<td><?php echo $allcat->items; ?></td>
											<td><?php echo $taggedAmount!=0?getmoney_format(round(($taggedAmount/100000),2)):$taggedAmount; ?></td>
											<td><?php echo $taggedItems; ?></td>
											<td><?php echo $nontaggedAmount!=0?getmoney_format(round(($nontaggedAmount/100000),2)):$nontaggedAmount; ?></td>
											<td><?php echo $nontaggedItems; ?></td>
											<td><?php echo $unspecifiedAmount!=0?getmoney_format(round(($unspecifiedAmount/100000),2)):$unspecifiedAmount; ?></td>
											<td><?php echo $unspecifiedItems; ?></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
											<th><?php echo "Sub Total"; ?></th>
											<td><?php echo $subtotalAmount!=0?getmoney_format(round(($subtotalAmount/100000),2)):$subtotalAmount; ?></td>
											<td><?php echo $subtotalItems; ?></td>
											<td><?php echo $subtaggedTotalAmount!=0?getmoney_format(round(($subtaggedTotalAmount/100000),2)):$subtaggedTotalAmount;; ?></td>
											<td><?php echo $subtaggedTotalItems; ?></td>
											<td><?php echo $subnontaggedTotalAmount!=0?getmoney_format(round(($subnontaggedTotalAmount/100000),2)):$subnontaggedTotalAmount;; ?></td>
											<td><?php echo $subnontaggedTotalItems; ?></td>
											<td><?php echo $subunspecifiedTotalAmount!=0?getmoney_format(round(($subunspecifiedTotalAmount/100000),2)):$subunspecifiedTotalAmount;; ?></td>
											<td><?php echo $subunspecifiedTotalItems; ?></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
										<tr>
											<th><?php echo "Grand Total"; ?></th>
											<th><?php echo $totalAmount!=0?getmoney_format(round(($totalAmount/100000),2)):$totalAmount; ?></th>
											<th><?php echo $totalItems; ?></th>
											<th><?php echo $taggedTotalAmount!=0?getmoney_format(round(($taggedTotalAmount/100000),2)):$taggedTotalAmount; ?></th>
											<th><?php echo $taggedTotalItems; ?></th>
											<th><?php echo $nontaggedTotalAmount!=0?getmoney_format(round(($nontaggedTotalAmount/100000),2)):$nontaggedTotalAmount; ?></th>
											<th><?php echo $nontaggedTotalItems; ?></th>
											<th><?php echo $unspecifiedTotalAmount!=0?getmoney_format(round(($unspecifiedTotalAmount/100000),2)):$unspecifiedTotalAmount; ?></th>
											<th><?php echo $unspecifiedTotalItems; ?></th>
										</tr>
										
										
									</table>
								</div>
								<?php
								}
								?>
                                <div class="col-md-12 text-center mt-3"><a href="<?php echo base_url(); ?>index.php/dashboard/reports"><button class="btn btn-round btn-blue">Back</button></a></div>
							</div>
						</div>
                    </div>
                    
				</div>
			</div>
		</div>
	</div>
	<!--seccion table--->
	</div>
	</div>
<style>
	table th,table td
	{
		text-align: center;
		height:40px;
	}
	</style>
<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/reportscript');
$this->load->view('layouts/footer');
?>