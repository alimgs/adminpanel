<?php
require_once("../wp-load.php");

$user_ID = get_current_user_id(); 

if ($user_ID == "0") {
    header("Location: https://partiyanshop.com");
    die();
}

if ($user_ID == "2648" || $user_ID == "2984" || $user_ID == "2090" || $user_ID == "2084" || $user_ID == "2094") {

//ini_set('display_errors', 1); ini_set('display_startup_errors', 0); error_reporting(E_ALL);


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
                            
                            $args = array(
                                'role'    => 'customer',
                                'orderby' => 'ID',
                                'order'   => 'DESC'
                            );
                            $users = get_users( $args );
                            
                            $dataSortData = [];
                            
                            foreach ( $users as $user ) {
        
                                $user_meta = get_user_meta ( $user->id);
                                
                                
                                $RegisterDateTimeMiladi = explode(" " , $user->user_registered);
                                $RegisterDateMiladi     = explode("-" , $RegisterDateTimeMiladi[0]);
                                
                                
                                $RegisterDatejalali     = gregorian_to_jalali($RegisterDateMiladi[0],$RegisterDateMiladi[1],$RegisterDateMiladi[2] , '/');
                                 
                                $dataSortData[] = [
                                        "ID" => $user->id,
                                        "NameFamily" => $user_meta['first_name'][0] . " " . $user_meta['last_name'][0],
                                        "UserLogin" => $user->user_login,
                                        "UserEmail" => $user->user_email,
                                        "UserRegister" => $RegisterDatejalali . " " . $RegisterDateTimeMiladi[1],
                                    ];
        
                            }                         
                            
  
                                
                            
                            wp_reset_query();
                            
                            ?>
                            
                            <div class="row filterBox">
        
                                <div class="col-md-3 search-input">
                                    <label class="filterLabel"  for="searchField">جست و جو در کاربران:</label><br/>
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
    <script type="application/javascript" src="https://bioinformaticscollege.ir/peptihub/js/jQuery.js"></script>

<script src="https://bioinformaticscollege.ir/peptihub/js/table-sortable.js"></script>    

<script>
            
                var data = <?php echo json_encode($dataSortData); ?>;
                
                
                var columns = {
                    ID : '#',
                    NameFamily   :   'نام و نام خانوادگی',
                    UserLogin                 : 'شماره تماس',
                	UserEmail                  : 'آدرس ایمیل',
                	UserRegister: 'زمان و تاریخ عضویت'
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
