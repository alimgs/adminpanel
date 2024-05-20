<?php
require_once("../wp-load.php");


$user_ID = get_current_user_id(); 

if ($user_ID == "0") {
    header("Location: https://partiyanshop.com");
    die();
}



if ($user_ID == "2648" || $user_ID == "2984" || $user_ID == "2090" || $user_ID == "2084" || $user_ID == "2094") {



if ($_GET["order_id"] == "" || $_GET['hCode'] == "") {
    header("Location: https://partiyanshop.com");
    die();
}

if ($_GET['hCode'] != md5($_GET["order_id"] . " dantism")) {
    header("Location: https://partiyanshop.com");
    die();
}

include 'jdf.php';



?>

<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
	<!--meta tag-->
	<meta charset="UTF-8"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Type" content="text/html;">
	<title>چاپ فاکتور</title>
		    <link rel="icon" href="https://partiyanshop.com/wp-content/uploads/2023/05/partiyan-favicon.png" type="image/x-icon">

	<style type="text/css">
	@font-face {
				font-family: "IRANSans";
				font-style: normal;
				font-weight: 500;
				font-display:block;
				src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-medium.eot");
				src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-medium.eot?#iefix") format("embedded-opentype"), 
				url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-medium.woff") format("woff"), 
				url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-medium.ttf") format("truetype");
			}
			@font-face {
				font-family: "IRANSans";
				font-style: normal;
				font-weight: normal;
				font-display:block;
				src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans.eot");
				src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans.eot?#iefix") format("embedded-opentype"), 
				url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans.woff") format("woff"), 
				url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans.ttf") format("truetype");
			}
			@font-face {
				font-family: "IRANSansnum";
				font-style: normal;
				font-weight: 500;
				font-display:block;
				src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-medium-fanum.eot");
				src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-medium-fanum.eot?#iefix") format("embedded-opentype"), 
				url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-medium-fanum.woff") format("woff"), 
				url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-medium-fanum.ttf") format("truetype");
			}
			@font-face {
				font-family: "IRANSansnum";
				font-style: normal;
				font-weight: normal;
				font-display:block;
				src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-fanum.eot");
				src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-fanum.eot?#iefix") format("embedded-opentype"), 
				url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-fanum.woff") format("woff"), 
				url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/IRANSans-fanum.ttf") format("truetype");
			}@font-face {
			font-family: "Font Awesome 6";
			font-style: normal;
			font-weight: 300;
			font-display: swap;
			src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/fa-light-300.woff2") format("woff2"),
			url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/fa-light-300.ttf") format("truetype"); 
		}
		@font-face {
			font-family: "Font Awesome 6 Brands";
			font-style: normal;
			font-weight: 400;
			font-display: swap;
			src: url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/fa-brands-400.woff2") format("woff2"),
			url("https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/fonts/fa-brands-400.ttf") format("truetype");
		}
		:root {--mainfont: IRANSans;--mainfontnum: IRANSansnum;}	</style>
	<link href="https://partiyanshop.com/wp-content/themes/mweb-digiland-pro/assets/css/invoice.css" rel="stylesheet" media="screen,print" />

</head>
<body>
<div class="container">


<?php
global $wpdb;

$user_id    = get_post_meta($_GET["order_id"], '_customer_user', true);
$user_meta  = get_user_meta ( $user_id );

$order_detail       = wc_get_order( $_GET["order_id"] );
$order_detail_data  = $order_detail->get_items();   




$order_date = $order_detail->get_date_created();

$OrderDateTimeMiladi = explode(" " , $order_date->date('Y-m-d H:i:s'));
$OrderDateMiladi     = explode("-" , $OrderDateTimeMiladi[0]);


$OrderDatejalali     = gregorian_to_jalali($OrderDateMiladi[0],$OrderDateMiladi[1],$OrderDateMiladi[2] , '/');

// Get the billing address fields
$billing_address_1 = $order_detail->get_billing_address_1();
$billing_address_2 = $order_detail->get_billing_address_2();
$billing_city = $order_detail->get_meta('_billing_city');


$billing_state = $order_detail->get_billing_state();
$billing_postcode = $order_detail->get_billing_postcode();
$billing_country = $order_detail->get_billing_country();


$billing_first_name = $order_detail->get_billing_first_name();
$billing_last_name = $order_detail->get_billing_last_name();
$billing_phone = $order_detail->get_billing_phone();

$billing_complete_address = $billing_address_1 . " " . $billing_address_2;

$order_comment = $order_detail->get_customer_note();


$payment_tracking_code =  $order_detail->get_transaction_id();



#echo "<pre>";
#print_r($order_detail);
#echo "</pre>";

$product_name = "";
$priceTotall = 0;


?>

<header>
	<div class="hd_order_detail">
		<div class="hd_right">
												<img class="invoice_logo" src="https://partiyanshop.com/wp-content/uploads/2023/05/partiyan-logo.jpg">
									</div>
		<div class="hd_left">
			<div class="order_detail_item has_barcode"><strong>شناسه فاکتور : </strong><span><i><?php echo $_GET["order_id"]; ?></i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGMAAAAZAQMAAAAfTAJxAAAABlBMVEUAAAD///+l2Z/dAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAHElEQVQYlWP4f7qy23BZT+bszV32DxhGeVTlAQCNX70qpPmBnQAAAABJRU5ErkJggg==" /></span></div>
			<div class="order_detail_item"><strong>تاریخ سفارش : </strong><span><?php echo $OrderDatejalali . " ساعت " . $OrderDateTimeMiladi[1]; ?></span></div>
		</div>
	</div>
</header>


<table class="order_info">
	<thead>
		<tr>
			<th>فروشنده</th>
			<th>خریدار</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<span class="inc_name"><b>فروشنده</b>فروشگاه پارتیان</span>
				<span class="inc_state"><b>استان</b>تهران</span>
				<span class="inc_city"><b>شهر</b>تهران</span>
				<span class="inc_postcode"><b>کدپستی</b>1138947474</span>
				<span class="inc_phone"><b>شماره تماس</b>۰۲۱۶۶۳۴۷۱۰۶</span>
				<span class="inc_address"><b>آدرس</b>تهران، خیابان حافظ، بازار موبایل ایران، طبقه زیر همکف، پلاک 139 و 140</span>
			</td>
			<td>
				<span class="inc_name"><b>خریدار</b><?php echo $billing_first_name . " " . $billing_last_name; ?></span>
				<span class="inc_state"><b>استان</b> <?php echo WC()->countries->get_states( $billing_country )[$billing_state]; ?></span>
				<span class="inc_city"><b>شهر</b> <?php echo $billing_city; ?></span>
				<span class="inc_postcode"><b>کدپستی</b> <?php echo $billing_postcode; ?></span>
				<span class="inc_phone"><b>شماره تماس</b><?php echo $billing_phone; ?></span>
				<span class="inc_address"><b>آدرس</b> <?php echo $billing_complete_address; ?></span>
			</td>
		</tr>
	</tbody>
</table>

<table class="order_details">
	<thead>
		<tr>
			<th class="num">ردیف</th>
			<th class="product">محصول</th>
			<th class="quantity">تعداد</th>
			<th class="item_price">مبلغ واحد</th>
			<th class="total_price">مبلغ کل</th>
		</tr>
	</thead>
	<tbody>
	    
	    <?php
	        $i = 1;
    	    foreach ( $order_detail_data as $itemOrder ) {
    	        
    	        
                ?>
        		<tr>
        			<td><?php echo $i; ?></td>
        			<td><?php echo $itemOrder->get_name(); ?></td>
        			<td><?php echo $itemOrder->get_quantity(); ?></td>
        			<td><span class="woocommerce-Price-amount amount"><bdi><?php echo number_format($itemOrder->get_total()/$itemOrder->get_quantity()); ?> &nbsp;<span class="woocommerce-Price-currencySymbol">تومان</span></bdi></span></td>
        			<td><span class="woocommerce-Price-amount amount"><bdi><?php echo number_format($itemOrder->get_total()); ?> &nbsp;<span class="woocommerce-Price-currencySymbol">تومان</span></bdi></span></td>
        		</tr>

                <?php
                
                $totallPrice = $order_detail->get_total(); // Mablagh nahaee pardakht
                $shipment_title = $order_detail->get_shipping_method();
                $discount_price = $order_detail->get_discount_total();


                
                $i++;
            }    
	    ?>
	    

			</tbody>
	<tfoot>
		<tr>
			<td colspan="4"><b>مبلغ کل :</b></td>
			<td><span class="woocommerce-Price-amount amount"><bdi><?php echo number_format($totallPrice + $discount_price); ?>&nbsp;<span class="woocommerce-Price-currencySymbol">تومان</span></bdi></span></td>
		</tr>
		<tr>
			<td colspan="4"><b>تخفیف :</b></td>
			<td><span class="woocommerce-Price-amount amount"><bdi><?php echo number_format($discount_price); ?>&nbsp;<span class="woocommerce-Price-currencySymbol">تومان</span></bdi></span></td>
		</tr>
		<tr>
			<td colspan="4"><b>حمل و نقل :</b> <small><?php echo $shipment_title; ?></small></td>
			<td><span class="woocommerce-Price-amount amount"><bdi>0&nbsp;<span class="woocommerce-Price-currencySymbol">تومان</span></bdi></span></td>
		</tr>
		<tr>
			<td colspan="4"><b>مبلغ نهایی :</b> <small>پرداخت امن زرین پال</small></td>
			<td><span class="woocommerce-Price-amount amount"><bdi><?php echo number_format($totallPrice); ?>&nbsp;<span class="woocommerce-Price-currencySymbol">تومان</span></bdi></span></td>
		</tr>
		
		<tr>
		    <td colspan="5">
		        <b>کد پیگیری پرداخت: </b>
		        <?php 
		            echo $payment_tracking_code;
		        ?>
		    </td>
		</tr>
		
		<tr>
		    <td colspan="5">
		        <b>توضیحات: </b>
		        <?php 
		            if ($order_comment == "") {
		                echo " ندارد";
		            } else {
		                echo $order_comment;
		            }
		        ?>
		    </td>
		</tr>
	</tfoot>
</table>


<footer>
    <div style="    text-align: center;
    font-size: 11px;
    letter-spacing: 8px;">
        www.partiyanshop.com
    </div>
</footer>


</div>

<a href="#" onclick="window.print();" class="print_btn" title="چاپ">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M96 160H64V64C64 28.7 92.7 0 128 0H357.5c17 0 33.3 6.7 45.3 18.7l26.5 26.5c12 12 18.7 28.3 18.7 45.3V160H416V90.5c0-8.5-3.4-16.6-9.4-22.6L380.1 41.4c-6-6-14.1-9.4-22.6-9.4H128c-17.7 0-32 14.3-32 32v96zm352 64H64c-17.7 0-32 14.3-32 32V384H64V352c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32v32h32V256c0-17.7-14.3-32-32-32zm0 192v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V416H32c-17.7 0-32-14.3-32-32V256c0-35.3 28.7-64 64-64H448c35.3 0 64 28.7 64 64V384c0 17.7-14.3 32-32 32H448zM96 352l0 128H416V352H96zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>
</a>
<a href="#" onclick="window.close();" class="close_btn" title="بستن صفحه">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M324.5 411.1c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6L214.6 256 347.1 123.5c6.2-6.2 6.2-16.4 0-22.6s-16.4-6.2-22.6 0L192 233.4 59.5 100.9c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6L169.4 256 36.9 388.5c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L192 278.6 324.5 411.1z"/></svg>
</a>
</body>
</html>

<?php

}

?>
