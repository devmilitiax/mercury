<?php
require_once('config.php');
require_once('helpers.php');
require_once('config-tables-columns.php');

// Check existence of id parameter before processing further
$_GET["id"] = trim($_GET["id"]);
if(isset($_GET["id"]) && !empty($_GET["id"])){
    // Prepare a select statement
    $sql = "SELECT `newsletters`.* 
            FROM `newsletters` 
            WHERE `newsletters`.`id` = ?
            GROUP BY `newsletters`.`id`;";

    if($stmt = mysqli_prepare($link, $sql)){
        // Set parameters
        $param_id = trim($_GET["id"]);

        // Bind variables to the prepared statement as parameters
		if (is_int($param_id)) $__vartype = "i";
		elseif (is_string($param_id)) $__vartype = "s";
		elseif (is_numeric($param_id)) $__vartype = "d";
		else $__vartype = "b"; // blob
        mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else{
            echo translate('stmt_error') . "<br>".$stmt->error;
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php translate('View Record') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="page-header">
                        <h1><?php translate('View Record') ?></h1>
                    </div>

                    									<?php
									// Check if the column is file upload
									// echo '<pre>';
									// print_r($tables_and_columns_names['newsletters']["columns"]['id_grapesjs']);
									// echo '</pre>';
									$has_link_file = isset($tables_and_columns_names['newsletters']["columns"]['id_grapesjs']['is_file']) ? true : false;
									if ($has_link_file){
									    $is_file = $tables_and_columns_names['newsletters']["columns"]['id_grapesjs']['is_file'];
									    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['id_grapesjs']) .'" target="_blank" class="uploaded_file" id="link_id_grapesjs">' : '';
									    $end_link_file = $is_file ? "</a>" : "";
									}
									?>
									<div class="form-group">
									    <h4>id_grapesjs*</h4>
									    <?php if ($has_link_file): ?>
									        <p class="form-control-static"><?php echo $link_file ?><?php echo htmlspecialchars($row["id_grapesjs"] ?? ""); ?><?php echo $end_link_file ?></p>
									    <?php endif ?>
									</div>									<?php
									// Check if the column is file upload
									// echo '<pre>';
									// print_r($tables_and_columns_names['newsletters']["columns"]['name']);
									// echo '</pre>';
									$has_link_file = isset($tables_and_columns_names['newsletters']["columns"]['name']['is_file']) ? true : false;
									if ($has_link_file){
									    $is_file = $tables_and_columns_names['newsletters']["columns"]['name']['is_file'];
									    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['name']) .'" target="_blank" class="uploaded_file" id="link_name">' : '';
									    $end_link_file = $is_file ? "</a>" : "";
									}
									?>
									<div class="form-group">
									    <h4>name*</h4>
									    <?php if ($has_link_file): ?>
									        <p class="form-control-static"><?php echo $link_file ?><?php echo htmlspecialchars($row["name"] ?? ""); ?><?php echo $end_link_file ?></p>
									    <?php endif ?>
									</div>									<?php
									// Check if the column is file upload
									// echo '<pre>';
									// print_r($tables_and_columns_names['newsletters']["columns"]['content_grapesjs']);
									// echo '</pre>';
									$has_link_file = isset($tables_and_columns_names['newsletters']["columns"]['content_grapesjs']['is_file']) ? true : false;
									if ($has_link_file){
									    $is_file = $tables_and_columns_names['newsletters']["columns"]['content_grapesjs']['is_file'];
									    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['content_grapesjs']) .'" target="_blank" class="uploaded_file" id="link_content_grapesjs">' : '';
									    $end_link_file = $is_file ? "</a>" : "";
									}
									?>
									<div class="form-group">
									    <h4>content_grapesjs</h4>
									    <?php if ($has_link_file): ?>
									        <p class="form-control-static"><?php echo $link_file ?><?php echo nl2br(htmlspecialchars($row["content_grapesjs"] ?? "")); ?><?php echo $end_link_file ?></p>
									    <?php endif ?>
									</div>									<?php
									// Check if the column is file upload
									// echo '<pre>';
									// print_r($tables_and_columns_names['newsletters']["columns"]['content_html']);
									// echo '</pre>';
									$has_link_file = isset($tables_and_columns_names['newsletters']["columns"]['content_html']['is_file']) ? true : false;
									if ($has_link_file){
									    $is_file = $tables_and_columns_names['newsletters']["columns"]['content_html']['is_file'];
									    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['content_html']) .'" target="_blank" class="uploaded_file" id="link_content_html">' : '';
									    $end_link_file = $is_file ? "</a>" : "";
									}
									?>
									<div class="form-group">
									    <h4>content_html</h4>
									    <?php if ($has_link_file): ?>
									        <p class="form-control-static"><?php echo $link_file ?><?php echo nl2br(htmlspecialchars($row["content_html"] ?? "")); ?><?php echo $end_link_file ?></p>
									    <?php endif ?>
									</div>									<?php
									// Check if the column is file upload
									// echo '<pre>';
									// print_r($tables_and_columns_names['newsletters']["columns"]['filter']);
									// echo '</pre>';
									$has_link_file = isset($tables_and_columns_names['newsletters']["columns"]['filter']['is_file']) ? true : false;
									if ($has_link_file){
									    $is_file = $tables_and_columns_names['newsletters']["columns"]['filter']['is_file'];
									    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['filter']) .'" target="_blank" class="uploaded_file" id="link_filter">' : '';
									    $end_link_file = $is_file ? "</a>" : "";
									}
									?>
									<div class="form-group">
									    <h4>filter</h4>
									    <?php if ($has_link_file): ?>
									        <p class="form-control-static"><?php echo $link_file ?><?php echo htmlspecialchars($row["filter"] ?? ""); ?><?php echo $end_link_file ?></p>
									    <?php endif ?>
									</div>									<?php
									// Check if the column is file upload
									// echo '<pre>';
									// print_r($tables_and_columns_names['newsletters']["columns"]['flag']);
									// echo '</pre>';
									$has_link_file = isset($tables_and_columns_names['newsletters']["columns"]['flag']['is_file']) ? true : false;
									if ($has_link_file){
									    $is_file = $tables_and_columns_names['newsletters']["columns"]['flag']['is_file'];
									    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['flag']) .'" target="_blank" class="uploaded_file" id="link_flag">' : '';
									    $end_link_file = $is_file ? "</a>" : "";
									}
									?>
									<div class="form-group">
									    <h4>flag</h4>
									    <?php if ($has_link_file): ?>
									        <p class="form-control-static"><?php echo $link_file ?><?php echo htmlspecialchars($row["flag"] ?? ""); ?><?php echo $end_link_file ?></p>
									    <?php endif ?>
									</div>									<?php
									// Check if the column is file upload
									// echo '<pre>';
									// print_r($tables_and_columns_names['newsletters']["columns"]['email']);
									// echo '</pre>';
									$has_link_file = isset($tables_and_columns_names['newsletters']["columns"]['email']['is_file']) ? true : false;
									if ($has_link_file){
									    $is_file = $tables_and_columns_names['newsletters']["columns"]['email']['is_file'];
									    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['email']) .'" target="_blank" class="uploaded_file" id="link_email">' : '';
									    $end_link_file = $is_file ? "</a>" : "";
									}
									?>
									<div class="form-group">
									    <h4>email</h4>
									    <?php if ($has_link_file): ?>
									        <p class="form-control-static"><?php echo $link_file ?><?php echo htmlspecialchars($row["email"] ?? ""); ?><?php echo $end_link_file ?></p>
									    <?php endif ?>
									</div>									<?php
									// Check if the column is file upload
									// echo '<pre>';
									// print_r($tables_and_columns_names['newsletters']["columns"]['subject']);
									// echo '</pre>';
									$has_link_file = isset($tables_and_columns_names['newsletters']["columns"]['subject']['is_file']) ? true : false;
									if ($has_link_file){
									    $is_file = $tables_and_columns_names['newsletters']["columns"]['subject']['is_file'];
									    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['subject']) .'" target="_blank" class="uploaded_file" id="link_subject">' : '';
									    $end_link_file = $is_file ? "</a>" : "";
									}
									?>
									<div class="form-group">
									    <h4>subject</h4>
									    <?php if ($has_link_file): ?>
									        <p class="form-control-static"><?php echo $link_file ?><?php echo htmlspecialchars($row["subject"] ?? ""); ?><?php echo $end_link_file ?></p>
									    <?php endif ?>
									</div>									<?php
									// Check if the column is file upload
									// echo '<pre>';
									// print_r($tables_and_columns_names['newsletters']["columns"]['createdate']);
									// echo '</pre>';
									$has_link_file = isset($tables_and_columns_names['newsletters']["columns"]['createdate']['is_file']) ? true : false;
									if ($has_link_file){
									    $is_file = $tables_and_columns_names['newsletters']["columns"]['createdate']['is_file'];
									    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['createdate']) .'" target="_blank" class="uploaded_file" id="link_createdate">' : '';
									    $end_link_file = $is_file ? "</a>" : "";
									}
									?>
									<div class="form-group">
									    <h4>createdate*</h4>
									    <?php if ($has_link_file): ?>
									        <p class="form-control-static"><?php echo $link_file ?><?php echo htmlspecialchars($row["createdate"] ?? ""); ?><?php echo $end_link_file ?></p>
									    <?php endif ?>
									</div>
                    <hr>
                    <p>
                        <a href="newsletters-update.php?id=<?php echo $_GET["id"];?>" class="btn btn-warning"><?php translate('Update Record') ?></a>
                        <a href="newsletters-delete.php?id=<?php echo $_GET["id"];?>" class="btn btn-danger"><?php translate('Delete Record') ?></a>
                        <a href="newsletters-create.php" class="btn btn-success"><?php translate('Add New Record') ?></a>
                        <a href="newsletters-index.php" class="btn btn-primary"><?php translate('Back to List') ?></a>
                    </p>
                    <?php
                    

                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </body>
</html>