<?php
// Load WordPress
require_once("../../../wp-load.php");

// Check if user is logged in
if (!is_user_logged_in()) {
    header("Location: https://partiyanshop.com");
    die();
}

$user_ID = get_current_user_id();

if (in_array($user_ID, array(2648, 2651, 2090, 2084, 2094))) {
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
</head>

<body>
    <section>
        <div class="container">
            <?php include 'header.php'; ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'product_cat'    => ''
                    );

                    $dataSortData = [];
                    $colorOptions = [];
                    $warrantyOptions = [];

                    // Fetch color and warranty options from the database
                    $products = new WP_Query($args);
                    if ($products->have_posts()) {
                        while ($products->have_posts()) {
                            $products->the_post();
                            $product_id = get_the_ID();
                            $color_terms = get_the_terms($product_id, 'pa_color');
                            $warranty_terms = get_the_terms($product_id, 'pa_warranty');
                            if (!empty($color_terms)) {
                                foreach ($color_terms as $term) {
                                    $colorOptions[$term->slug] = $term->name;
                                }
                            }
                            if (!empty($warranty_terms)) {
                                foreach ($warranty_terms as $term) {
                                    $warrantyOptions[$term->slug] = $term->name;
                                }
                            }
                        }
                        wp_reset_postdata();
                    }

                    // Function to generate color options dropdown
                    function getColorOptions($colorOptions)
                    {
                        $options = '';
                        foreach ($colorOptions as $slug => $name) {
                            $options .= "<option value='$slug'>$name</option>";
                        }
                        return $options;
                    }

                    // Function to generate warranty options dropdown
                    function getWarrantyOptions($warrantyOptions)
                    {
                        $options = '';
                        foreach ($warrantyOptions as $slug => $name) {
                            $options .= "<option value='$slug'>$name</option>";
                        }
                        return $options;
                    }

                    $loop = new WP_Query($args);

                    while ($loop->have_posts()) : $loop->the_post();

                        global $product;

                        if (!$product->managing_stock() && !$product->is_in_stock())
                            continue;

                        if ($product->is_type('simple')) {

                            // Get the availability (stock quantity) for simple products
                            $qty = $product->get_stock_quantity();

                            $pricep = 0;
                            if ($product->get_price() != "") {
                                $pricecp = $product->get_price();
                            }

                            $dataSortData[] = [
                                "picture"       => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . woocommerce_get_product_thumbnail() . "</a>",
                                "title"         => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . get_the_title() . "</a>",
                                "availability"  => "<input class='priceField' type='number' min='0' max='20' id=" . $product->get_id() . '-qty' . " value='" . $qty . "' />",
                                "color"         => "<select multiple name='color[]'>" . getColorOptions($colorOptions) . "</select>",
                                "warranty"      => "<select multiple name='warranty[]'>" . getWarrantyOptions($warrantyOptions) . "</select>",
                                "price"         => "<input class='priceField' type='text' id=" . $product->get_id() . "' value='" . $pricep . "' /><input type='hidden' id='hashID" . $product->get_id() . "' value='" . md5("Dantism" . $product->get_id()) . "' />",
                                "button"        => "<button id='button" . $product->get_id() . "' onclick='updatePrice(" . $product->get_id() . ")'>بروزرسانی قیمت</button><br/><div id='getMsg" . $product->get_id() . "'></div>"
                            ];
                        } elseif ($product->is_type('variable')) {

                            $variations = $product->get_available_variations();

                            foreach ($variations as $variation) {

                                // Get the availability (stock quantity) for variable products
                                $qty = $variation['max_qty'];

                                $dataSortData[] = [
                                    "picture"       => "<a target='_blank' href='" . get
                               _permalink($product->get_id()) . "'>" . woocommerce_get_product_thumbnail() . "</a>",
                                "title"         => "<a target='_blank' href='" . get_permalink($product->get_id()) . "'>" . get_the_title() . "</a>",
                                "availability"  => "<input class='priceField' type='number' min='0' max='20' id=" . $variation['variation_id'] . '-qty' . " value='" . $qty . "' />",
                                "color"         => "<select multiple name='color[]'>" . getColorOptions($colorOptions) . "</select>",
                                "warranty"      => "<select multiple name='warranty[]'>" . getWarrantyOptions($warrantyOptions) . "</select>",
                                "price"         => "<input class='priceField' type='text' id=" . $variation['variation_id'] . " value='" . $variation['display_price'] . "' /><input type='hidden' id='hashID" . $variation['variation_id'] . "' value='" . md5("Dantism" . $variation['variation_id']) . "' />",
                                "button"        => "<button id='button" . $variation['variation_id'] . "' onclick='updatePrice(" . $variation['variation_id'] . ")'>بروزرسانی قیمت</button><br/><div id='getMsg" . $variation['variation_id'] . "'></div>"
                            ];
                        }
                    endwhile;
                    wp_reset_query();
                    ?>

                    <div class="row filterBox">
                        <div class="col-md-3 search-input">
                            <label class="filterLabel" for="searchField">جست و جو در محصولات:</label><br />
                            <input type="text" class="form-control" placeholder="" id="searchField">
                        </div>
                        <div class="col-md-2">
                            <label class="filterLabel" for="rowsPerPage">تعداد سطرها:</label><br />
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
            tableWillMount: function () {
                //console.log('table will mount')
            },
            tableDidMount: function () {
                //console.log('table did mount')
            },
            tableWillUpdate: function () {},
            tableDidUpdate: function () {},
            tableWillUnmount: function () {},
            tableDidUnmount: function () {},
            onPaginationChange: function (nextPage, setPage) {
                setPage(nextPage);
            }
        });

        $('#changeRows').on('change', function () {
            table.updateRowsPerPage(parseInt($(this).val(), 10));
        });

        $('#rerender').click(function () {
            table.refresh(true);
        });

        $('#distory').click(function () {
            table.distroy();
        });

        $('#refresh').click(function () {
            table.refresh();
        });

        $('#setPage2').click(function () {
            table.setPage(1);
        });

        function updatePrice(ID) {
            var newPrice = $('#' + ID).val();
            var newMaxQty = $('#' + ID + '-qty').val();
            var hashID = $('#hashID' + ID).val();

            jQuery("#getMsg" + ID).html("");

            var flag = 0;

            if (newPrice == "" || hashID == "" || ID == "" || newMaxQty == "") {
                jQuery("#getMsg").html("Error: Some Thing is empty.");
                flag = 1;
            }

            if (isNaN(newMaxQty)) {
                flag = 1;
            }

            if (flag) {
                return;
            }

            jQuery("#" + ID).attr("disabled", "disabled");
            jQuery("#button" + ID).attr("disabled", "disabled");
            jQuery('#' + ID + '-qty').attr("disabled", "disabled");

            jQuery("#" + ID).addClass("disabledInput");
            jQuery('#' + ID + '-qty').addClass("disabledInput");

            jQuery.ajax({
                type: "POST",
                cache: true,
                data: {
                    type: 'updatePrice',
                    newPrice: newPrice,
                    ID: ID,
                    hashID: hashID,
                    newMaxQty: newMaxQty
                },
                url: "helper.php",
                dataType: "html",
                success: function (response) {
                    jQuery("#" + ID).attr("disabled", false);
                    jQuery("#button" + ID).attr("disabled", false);
                    jQuery('#' + ID + '-qty').attr("disabled", false);
                    jQuery("#" + ID).removeClass("disabledInput");
                    jQuery('#' + ID + '-qty').removeClass("disabledInput");
                    jQuery("#getMsg" + ID).html(response);
                    setTimeout(function () {
                        jQuery("#getMsg" + ID).html("");
                    }, 2000);
                },
            });
        }
    </script>
</body>

</html>
<?php
}
?>
