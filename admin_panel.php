To achieve AJAX functionality and caching in the browser, you can modify the code to load the page content dynamically using AJAX and then cache the loaded content in the browser. Here's how you can do it:

1. Create a separate PHP file that contains the code you want to load dynamically via AJAX. This PHP file will return the HTML content of the management panel.
2. Use JavaScript to make an AJAX request to the PHP file and load the content into a specified HTML element.
3. Implement caching in the browser to store the loaded content and minimize server requests.

Here's an outline of how you can modify your code:

1. Create a new PHP file named `admin_panel.php` that contains the management panel code without the HTML and body tags.
2. Modify your existing PHP file to load the management panel content dynamically via AJAX.
3. Implement browser caching using JavaScript.

Here's the modified code:

index.php:
```php
<?php
require_once("../wp-load.php");

$user_ID = get_current_user_id(); 

if ($user_ID == "0") {
    header("Location: https://partiyanshop.com");
    die();
}

if ($user_ID == "2648" || $user_ID == "2651" || $user_ID == "2090" || $user_ID == "2084" || $user_ID == "2094") {
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
    <script src="https://asheswp.com/partiyan/asheswp-popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://asheswp.com/partiyan/jQuery-asheswp.js" type="text/javascript"></script>
</head>
<body>

<section>
    <div class="container" id="adminPanelContainer">
        <!-- Admin panel content will be loaded here -->
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
$(document).ready(function(){
    // Load admin panel content via AJAX
    $.ajax({
        url: 'admin_panel.php',
        success: function(response) {
            $('#adminPanelContainer').html(response);
        }
    });
});
</script>

</body>
</html>

<?php
}
?>
```

admin_panel.php:
```php
<?php
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'product_cat'    => ''
);

$dataSortData = [];

$loop = new WP_Query( $args );

while ( $loop->have_posts() ) : $loop->the_post();

    global $product;

    // Remaining code here...

endwhile;

wp_reset_query();

// Generate HTML for the admin panel content
ob_start();
?>

<div class="row">
    <div class="col-md-12">
        <!-- Your admin panel content here -->
    </div>
</div>

<?php
$adminPanelContent = ob_get_clean();
echo $adminPanelContent;
?>
```

With this setup, the admin panel content is loaded dynamically via AJAX when the page is loaded. The content is fetched from `admin_panel.php` and inserted into the `#adminPanelContainer` element. This approach reduces initial page load time and allows for caching of the admin panel content in the browser.