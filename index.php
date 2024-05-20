<?php

require_once("../wp-load.php");

$user_ID = get_current_user_id(); 

if ($user_ID == "0") {
    header("Location: https://partiyanshop.com");
    die();
}

$authorized_users = [2648, 2651, 2090, 2084, 2094];
if (in_array($user_ID, $authorized_users)) {

?>

<!doctype html>
<html lang="fa">
<head>
    <title>پنل مدیریت - ادمین</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://partiyanshop.com/wp-content/themes/zanbil/css/fonts/bold/iranyekanwebboldfanum.woff">
    <link rel="icon" href="https://partiyanshop.com/wp-content/uploads/2023/05/partiyan-favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css?v=<?php echo rand(); ?>">
    <script src="https://asheswp.com/partiyan/asheswp-popper.min.js"></script>
    <script src="https://asheswp.com/partiyan/jQuery-asheswp.js"></script>
</head>
<body>

<section>
    <div class="container">
        <?php include 'header.php'; ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                $args = [
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                ];

                $dataSortData = [];
                $loop = new WP_Query($args);

                while ($loop->have_posts()) : $loop->the_post();
                    global $product;

                    if (!$product->managing_stock() && !$product->is_in_stock()) continue;

                    if ($product->is_type('simple')) {
                        $qty = $product->get_total_stock() ?: 0;
                        $pricep = $product->get_price() ?: 0;

                        $dataSortData[] = [
                            "picture" => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . woocommerce_get_product_thumbnail() . "</a>",
                            "title" => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . get_the_title() . "</a>",
                            "availability" => "-<input class='priceField' style='display: none;' type='number' min='0' max='20' id=" . $product->get_id() . "-qty' value='" . $qty . "' />",
                            "color" => "-",
                            "warranty" => "-",
                            "price" => "<input class='priceField' type='text' id='" . $product->get_id() . "' value='" . $pricep . "' /><input type='hidden' id='hashID" . $product->get_id() . "' value='" . md5("Dantism" . $product->get_id()) . "' />",
                            "button" => "<button id='button" . $product->get_id() . "' onclick='updatePrice(" . $product->get_id() . ")'>بروزرسانی قیمت</button><br/><div id='getMsg" . $product->get_id() . "'></div>"
                        ];
                    } elseif ($product->is_type('variable')) {
                        $variations = $product->get_available_variations();

                        foreach ($variations as $variation) {
                            $qty = $variation['max_qty'] ?: 0;
                            $metaColor = get_post_meta($variation['variation_id'], 'attribute_pa_color', true);
                            $termColor = get_term_by('slug', $metaColor, 'pa_color');
                            $metaWarranty = get_post_meta($variation['variation_id'], 'attribute_pa_warranty', true);
                            $termWarranty = get_term_by('slug', $metaWarranty, 'pa_warranty');

                            $dataSortData[] = [
                                "picture" => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . woocommerce_get_product_thumbnail() . "</a>",
                                "title" => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . get_the_title() . "</a>",
                                "availability" => "<input class='priceField' type='number' min='0' max='20' id=" . $variation['variation_id'] . "-qty' value='" . $variation['max_qty'] . "' />",
                                "color" => $termColor->name,
                                "warranty" => $termWarranty->name,
                                "price" => "<input class='priceField' type='text' id=" . $variation['variation_id'] . " value='" . $variation['display_price'] . "' /><input type='hidden' id='hashID" . $variation['variation_id'] . "' value='" . md5("Dantism" . $variation['variation_id']) . "' />",
                                "button" => "<button id='button" . $variation['variation_id'] . "' onclick='updatePrice(" . $variation['variation_id'] . ")'>بروزرسانی قیمت</button><br/><div id='getMsg" . $variation['variation_id'] . "'></div>"
                            ];
                        }
                    }

                endwhile;

                wp_reset_query();
                ?>

                <div class="row filterBox">
                    <div class="col-md-3 search-input">
                        <label class="filterLabel" for="searchField">جست و جو در محصولات:</label><br/>
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
<script src="js/main.js"></script>
<script type="application/javascript" src="https://asheswp.com/partiyan/jQuery-asheswp.js"></script>
<script src="https://asheswp.com/partiyan/table-sortable-asheswp.js"></script>

<script>
var data = <?php echo json_encode($dataSortData); ?>;

var columns = {
    picture: 'تصویر محصول',
    title: 'نام محصول',
    availability: 'تعداد',
    color: 'رنگ',
    warranty: 'گارانتی',
    price: 'قیمت (تومان)',
    button: ''
};

var table = $('#root').tableSortable({
    data: data,
    columns: columns,
    searchField: '#searchField',
    rowsPerPage: 5,
    pagination: true,
    tableWillMount: function() {},
    tableDidMount: function() {},
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
});

function updatePrice(ID) {
    var newPrice = $('#' + ID).val();
    var newMaxQty = $('#' + ID + '-qty').val();
    var hashID = $('#hashID' + ID).val();
    $("#getMsg" + ID).html("");

    if (!newPrice || !hashID || !ID || !newMaxQty || isNaN(newMaxQty)) {
        $("#getMsg" + ID).html("Error: Some fields are empty or invalid.");
        return;
    }

    $("#" + ID).attr("disabled", "disabled");
    $("#button" + ID).attr("disabled", "disabled");
    $('#' + ID + '-qty').attr("disabled", "disabled");
    $("#" + ID).addClass("disabledInput");
    $('#' + ID + '-qty').addClass("disabledInput");

    $.ajax({
        type: "POST",
        cache: true,
        data: { type: 'updatePrice', newPrice: newPrice, ID: ID, hashID: hashID, newMaxQty: newMaxQty },
        url: "Below is the updated and optimized version of your code, focusing on the attribute editing functionality for products and variations:

```php
<?php

require_once("../wp-load.php");

$user_ID = get_current_user_id(); 

if ($user_ID == "0") {
    header("Location: https://partiyanshop.com");
    die();
}

$authorized_users = [2648, 2651, 2090, 2084, 2094];
if (in_array($user_ID, $authorized_users)) {

?>

<!doctype html>
<html lang="fa">
<head>
    <title>پنل مدیریت - ادمین</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://partiyanshop.com/wp-content/themes/zanbil/css/fonts/bold/iranyekanwebboldfanum.woff">
    <link rel="icon" href="https://partiyanshop.com/wp-content/uploads/2023/05/partiyan-favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css?v=<?php echo rand(); ?>">
    <script src="https://asheswp.com/partiyan/asheswp-popper.min.js"></script>
    <script src="https://asheswp.com/partiyan/jQuery-asheswp.js"></script>
</head>
<body>

<section>
    <div class="container">
        <?php include 'header.php'; ?>
        <div class="row">
            <div class="col-md-12">
                <?php
                $args = [
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                ];

                $dataSortData = [];
                $loop = new WP_Query($args);

                while ($loop->have_posts()) : $loop->the_post();
                    global $product;

                    if (!$product->managing_stock() && !$product->is_in_stock()) continue;

                    if ($product->is_type('simple')) {
                        $qty = $product->get_total_stock() ?: 0;
                        $pricep = $product->get_price() ?: 0;

                        $dataSortData[] = [
                            "picture" => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . woocommerce_get_product_thumbnail() . "</a>",
                            "title" => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . get_the_title() . "</a>",
                            "availability" => "-<input class='priceField' style='display: none;' type='number' min='0' max='20' id=" . $product->get_id() . "-qty' value='" . $qty . "' />",
                            "color" => "-",
                            "warranty" => "-",
                            "price" => "<input class='priceField' type='text' id='" . $product->get_id() . "' value='" . $pricep . "' /><input type='hidden' id='hashID" . $product->get_id() . "' value='" . md5("Dantism" . $product->get_id()) . "' />",
                            "button" => "<button id='button" . $product->get_id() . "' onclick='updatePrice(" . $product->get_id() . ")'>بروزرسانی قیمت</button><br/><div id='getMsg" . $product->get_id() . "'></div>"
                        ];
                    } elseif ($product->is_type('variable')) {
                        $variations = $product->get_available_variations();

                        foreach ($variations as $variation) {
                            $qty = $variation['max_qty'] ?: 0;
                            $metaColor = get_post_meta($variation['variation_id'], 'attribute_pa_color', true);
                            $termColor = get_term_by('slug', $metaColor, 'pa_color');
                            $metaWarranty = get_post_meta($variation['variation_id'], 'attribute_pa_warranty', true);
                            $termWarranty = get_term_by('slug', $metaWarranty, 'pa_warranty');

                            $dataSortData[] = [
                                "picture" => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . woocommerce_get_product_thumbnail() . "</a>",
                                "title" => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . get_the_title() . "</a>",
                                "availability" => "<input class='priceField' type='number' min='0' max='20' id=" . $variation['variation_id'] . "-qty' value='" . $variation['max_qty'] . "' />",
                                "color" => $termColor->name,
                                "warranty" => $termWarranty->name,
                                "price" => "<input class='priceField' type='text' id=" . $variation['variation_id'] . " value='" . $variation['display_price'] . "' /><input type='hidden' id='hashID" . $variation['variation_id'] . "' value='" . md5("Dantism" . $variation['variation_id']) . "' />",
                                "button" => "<button id='button" . $variation['variation_id'] . "' onclick='updatePrice(" . $variation['variation_id'] . ")'>بروزرسانی قیمت</button><br/><div id='getMsg" . $variation['variation_id'] . "'></div>"
                            ];
                        }
                    }

                endwhile;

                wp_reset_query();
                ?>

                <div class="row filterBox">
                    <div class="col-md-3 search-input">
                        <label class="filterLabel" for="searchField">جست و جو در محصولات:</label><br/>
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
<script src="js/main.js"></script>
<script type="application/javascript" src="https://asheswp.com/partiyan/jQuery-asheswp.js"></script>
<script src="https://asheswp.com/partiyan/table-sortable-asheswp.js"></script>

<script>
var data = <?php echo json_encode($dataSortData); ?>;

var columns = {
    picture: 'تصویر محصول',
    title: 'نام محصول',
    availability: 'تعداد',
    color: 'رنگ',
    warranty: 'گارانتی',
    price: 'قیمت (تومان)',
    button: ''
};

var table = $('#root').tableSortable({
    data: data,
    columns: columns,
    searchField: '#searchField',
    rowsPerPage: 5,
    pagination: true,
    tableWillMount: function() {},
    tableDidMount: function() {},
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
});

function updatePrice(ID) {
    var newPrice = $('#' + ID).val();
    var newMaxQty = $('#' + ID + '-qty').val();
    var hashID = $('#hashID' + ID).val();
    $("#getMsg" + ID).html("");

    if (!newPrice || !hashID || !ID || !newMaxQty || isNaN(newMaxQty)) {
        $("#getMsg" + ID).html("Error: Some fields are empty or invalid.");
        return;
    }

    $("#" + ID).attr("disabled", "disabled");
    $("#button" + ID).attr("disabled", "disabled");
    $('#' + ID + '-qty').attr("disabled", "disabled");
    $("#" + ID).addClass("disabledInput");
    $('#' + ID + '-qty').addClass("disabledInput");

    $.ajax({
        type: "POST",
        cache: true,
        data: { type: 'updatePrice', newPrice: newPrice, ID: ID, hashID: hashID, newMaxQty: newMaxQty },
        url:
