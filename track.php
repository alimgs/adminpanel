<?php
require_once("../wp-load.php");

$user_ID = get_current_user_id(); 

if ($user_ID == "0") {
    header("Location: https://partiyanshop.com");
    die();
}

if ($user_ID == "2648" || $user_ID == "2984" || $user_ID == "2090" || $user_ID == "2084" || $user_ID == "2094") {


?>

<!doctype html>
<html lang="fa">
  <head>
  	<title>پنل مدیریت - ادمین</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	    <link rel="icon" href="https://partiyanshop.com/wp-content/uploads/2023/05/partiyan-favicon.png" type="image/x-icon">

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/custom.css?v=<?php echo rand(); ?>">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>


	</head>
	<body>
	    

	<section>
		<div class="container">

			
			
	    
	        <?php include 'header.php'; ?>
			
			<div class="row">
				<div class="col-md-12">

						      
						<?php
                            global $wpdb;
                        
                            $customer_orders = get_posts( array(
                                'numberposts' => -1,
                                'post_type'   => wc_get_order_types(),
                                'post_status' => array('wc-processing', 'wc-completed'),
                            ) );
                            
                            /*
                            echo "<pre>";
                            print_r($customer_orders);
                            echo "</pre>";
                            */
                            
                            $dataSortData = [];
                            

                            
    
                            foreach ($customer_orders as $item) {
                                
                                $order_id = $item->ID;
                                
                                $user_id    = get_post_meta($item->ID, '_customer_user', true);
                                $user_meta  = get_user_meta ( $user_id );
    
                                $order_detail       = wc_get_order( $item->ID );
                                $order_detail_data  = $order_detail->get_items();   
                                
                                $billing_first_name = $order_detail->get_billing_first_name();
                                $billing_last_name = $order_detail->get_billing_last_name();
                                
                                $payment_tracking_code =  $order_detail->get_transaction_id();

                                $order_date          = $order_detail->get_date_created();
                                $OrderDateTimeMiladi = explode(" " , $order_date->date('Y-m-d H:i:s'));
                                $OrderDateMiladi     = explode("-" , $OrderDateTimeMiladi[0]);
                                $OrderDatejalali     = gregorian_to_jalali($OrderDateMiladi[0],$OrderDateMiladi[1],$OrderDateMiladi[2] , '/');

                                $getSendStatus = $wpdb->get_results("
                                    SELECT * FROM `dtm_track_send` WHERE order_id = $order_id
                                "); 
                                $getSendNumber = $getSendStatus[0]->send_type;
                                
                                $sendPartiyanText = "";
                                if ($getSendNumber == "1") {
                                    $sendPartiyanText = "تحویل حضوری";
                                } else if ($getSendNumber == "2") {
                                    $sendPartiyanText = "ماهکس";
                                } else if ($getSendNumber == "3") {
                                    $sendPartiyanText = "پیک موتوری";
                                } else if ($getSendNumber == "4") {
                                    $sendPartiyanText = "تیپاکس";
                                } else if ($getSendNumber == "5") {
                                    $sendPartiyanText = "چاپار";
                                } else {
                                    $sendPartiyanText = "نامشخص";
                                }
                                

                                
                                #echo "<pre>";
                                #print_r($order_detail_data);
                                #echo "</pre>";
                                
                                $hCode = md5($order_id . " dantism");
                                
                                $product_name = "";
                                $price = 0;
                                
                                foreach ( $order_detail_data as $itemOrder ) {
                                    
                                    $product_name .= $itemOrder->get_name() . "<br/>";
                                    $product_id = $itemOrder->get_product_id();
                                    $product_variation_id = $itemOrder->get_variation_id();
                                    $price  +=   $itemOrder->get_total();
                                    

    
                                }    
                                
                                $order_stage = $wpdb->get_row("SELECT * FROM dtm_track_orders WHERE order_id = $order_id");
                                
                                $currentStateValue = "";
                                
                                if ($order_stage->track_stage == "1") {
                                    $currentStateValue = "<div id='currentStageText".$order_id."' class='isstate1 statesame'>در حال بررسی</div>";
                                } else if ($order_stage->track_stage == "2") {
                                    $currentStateValue = "<div id='currentStageText".$order_id."' class='isstate2 statesame'>بسته بندی</div>";
                                } else if ($order_stage->track_stage == "3") {
                                    $currentStateValue = "<div id='currentStageText".$order_id."' class='isstate3 statesame'>آماده ارسال</div>";
                                } else if ($order_stage->track_stage == "4") {
                                    $currentStateValue = "<div id='currentStageText".$order_id."' class='isstate4 statesame'>ارسال شده</div>";
                                }

                                // selected='".($order_stage->track_stage == "0")? 'selected' : '1'."'
                                
                                $dataSortData[] = [
                                        "OrderID" => $order_id,
                                        "NameFamily" => $billing_first_name . " " . $billing_last_name,
                                        "TotallPrice" => number_format($price) . " تومان",
                                        'OrderDate' => $OrderDatejalali . "<br/>" . $OrderDateTimeMiladi[1],
                                        "PostCode" => "<div class='postCode'><input type='text' value='".$order_stage->code_posti."' id='postCode".$order_id."'/></div>",
                                        "Actions" => "
                                            <div class='trackList'>
                                                <select name='trackThis' id='".$order_id."'>
                                                    <option value='0'>-</option>
                                                    <option value='1'>در حال پردازش</option>
                                                    <option value='2'>بسته بندی</option>
                                                    <option value='3'>آماده ارسال</option>
                                                    <option value='4'>ارسال شده</option>
                                                </select>
                                            </div>
                                            <div id='getMsg".$order_id."'></div>",
                                        
                                        "CurrentState" => $currentStateValue,
                                        "PartiyanSend" => "
                                            <div class='partiyanSendList'>
                                                <select name='partiyanSend' id='".$order_id."'>
                                                    <option value='0'>-</option>
                                                    <option value='1'>تحویل حضوری</option>
                                                    <option value='2'>ماهکس (پس کرایه)</option>
                                                    <option value='3'>پیک موتوری</option>
                                                    <option value='4'>تیپاکس</option>
                                                    <option value='5'>چاپار</option>
                                                </select>
                                            </div>
                                            <div id='getMsgSend".$order_id."'></div>
                                            <div class='currentSendType'>
                                            <b>وضعیت فعلی: <b/>
                                            ".$sendPartiyanText."</div>
                                            ",
                                        "Factor" => "<a class='invoiceBTN' target='_blank' href='https://partiyanshop.com/report/invoice.php?order_id=".$order_id."&hCode=".$hCode."'>مشاهده فاکتور</a>"
                                    ];
                                
                            }
                
                        ?>
                            
                            <div class="row filterBox">
        
                                <div class="col-md-3 search-input">
                                    <label class="filterLabel"  for="searchField">جست و جو در سفارشات:</label><br/>
                                    <input type="text" class="form-control" placeholder="" id="searchField">
                                </div>
                                <div class="col-md-2">
                                    <label class="filterLabel" for="rowsPerPage">تعداد سطرها:</label><br/>
                                    <div class="d-flex justify-content-end">
                                        <select class="custom-select" name="rowsPerPage" id="changeRows">
                                            <option value="1">1</option>
                                            <option value="5" selected>5</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                            
                            
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div id="root"></div>
                                </div>
        
                            </div>

                </div>
			</div>
		</div>
	</section>


    <footer>
        <p>کلیه حقوق مادی و معنوی برای این سایت محفوظ می باشد و هرگونه کپی برداری شامل پیگرد قانونی می باشد.</p>
    </footer>


	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js?v=<?php echo rand(); ?>"></script>
    <script type="application/javascript" src="https://bioinformaticscollege.ir/peptihub/js/jQuery.js"></script>

<script src="https://bioinformaticscollege.ir/peptihub/js/table-sortable.js"></script>    

<script>
            
                var data = <?php echo json_encode($dataSortData); ?>;
                
                
                var columns = {
                    OrderID : 'شماره سفارش',
                    NameFamily   :   'نام و نام خانوادگی',
                	TotallPrice                  : 'مبلغ پرداختی',
                	OrderDate: ' زمان سفارش',
                	PostCode : 'توضیحات سفارش',
                	Actions : 'تغییر وضعیت سفارش',
                	
                	CurrentState : 'وضعیت فعلی سفارش',
                	PartiyanSend: 'روش ارسال پارتیان',
                	Factor: ''

                }       
        
        	
                var table = $('#root').tableSortable({
                    data: data,
                    columns: columns,
                    searchField: '#searchField',
                    rowsPerPage: 5,
                    pagination: true,
                    tableWillMount: function() {
                        //console.log('table will mount')
                    },
                    tableDidMount: function() {
                        //console.log('table did mount')
                    },
                    tableWillUpdate: function() {},
                    tableDidUpdate: function() {},
                    tableWillUnmount: function() {},
                    tableDidUnmount: function() {},
                    onPaginationChange: function(nextPage, setPage) {
                        setPage(nextPage);
                    }
                });
        
                $('#changeRows').on('change', function() {
                    table.updateRowsPerPage(parseInt($(this).val(), 10));
                })
        
                $('#rerender').click(function() {
                    table.refresh(true);
                })
        
                $('#distory').click(function() {
                    table.distroy();
                })
        
                $('#refresh').click(function() {
                    table.refresh();
                })
        
                $('#setPage2').click(function() {
                    table.setPage(1);
                })                
                
             
            </script>
	</body>
</html>

<?php
}
?>
