
<?php



$user_ID = get_current_user_id(); 

if ($user_ID == "0") {
    header("Location: https://partiyanshop.com");
    die();
}

include 'jdf.php';

//ini_set('display_errors', 1); ini_set('display_startup_errors', 0); error_reporting(E_ALL);


?>

<?php
    $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $uriLastSegment = end($uriSegments);
    
    // Count the number of users with the role "customer"
    $user_count = count_users();
    $customer_count = isset($user_count['avail_roles']['customer']) ? $user_count['avail_roles']['customer'] : 0;
    
    
    // Count all in-process orders
    $orders1 = wc_get_orders(array(
        'posts_per_page' => -1,
        'status' => array('processing', 'completed')
    ));
    $in_process_count = count($orders1);
    

    
    
    // Get the count of all available products and their variants
    $product_args = array(
        'status' => 'publish',
        'limit'  => -1,
    );
    $products = wc_get_products($product_args);
    
    $product_count = count($products);
    
    // Count the number of variants for each product
    $variant_count = 0;
    foreach ($products as $product) {
        $product_id = $product->get_id();
        $variants = get_children(array(
            'post_parent' => $product_id,
            'post_type'   => 'product_variation',
            'post_status' => 'publish',
            'numberposts' => -1,
        ));
        $variant_count += count($variants);
    }
    
    $total_count = $product_count + $variant_count;

?>

<div class="row justify-content-center">
	<div class="col-md-6 text-center mb-4 header">
	    <a href="https://partiyanshop.com" target="_blank"><img src="https://partiyanshop.com/wp-content/uploads/2021/09/IMG_6352.jpg" alt="لوکو پارتیان" /></a>
		<h2 class="heading-section">پنل مدیریت اختصاصی پارتیان <span id="partiyanVersion">(نسخه ۳.۲)</span></h2>
	</div>
</div>

<div class="row headerBox">
    
    <div class="col-sm-4 <?php echo ($uriLastSegment == 'index.php') ? 'active' : ''; ?>">
        <a href="https://partiyanshop.com/report/index.php">
            <i class="fa fa-dollar"></i>
            <br/>
            تغییر قیمت/موجودی محصولات
            <br/>
            <span>
                تعداد کل محصولات سایت: <?php echo $product_count . "/" . $variant_count; ?>
            </span>
        </a>
    </div>

    
    <div class="col-sm-4 <?php echo ($uriLastSegment == 'orders.php') ? 'active' : ''; ?>">
        <a href="https://partiyanshop.com/report/orders.php">
            <i class="fa fa-shopping-basket"></i>
            <br/>
            مشاهده سفارشات ثبت شده
            <br/>
            <span>
                تعداد سفارشات ثبت شده: <?php echo $in_process_count; ?>
            </span>
        </a>
    </div>
    
    <div class="col-sm-4 <?php echo ($uriLastSegment == 'users.php') ? 'active' : ''; ?>">
        <a href="https://partiyanshop.com/report/users.php">
            <i class="fa fa-users"></i>
            <br/>
            مشاهده کاربران سایت
            <br/>
            <span>
                تعداد مشتریان سایت: <?php echo $customer_count; ?>
            </span>
        </a>
    </div>

	<?php /*
    <div class="col-sm-3 <?php echo ($uriLastSegment == 'track.php') ? 'active' : ''; ?>">
        <a href="https://partiyanshop.com/report/track.php">
            <i class="fa fa-area-chart"></i>
            <br/>
            پیگیری سفارشات
            <br/>
            <span>(در حال توسعه)</span>
        </a>
    </div>
	*/ ?>

    
</div>
