<?php
require_once('config.php');
require_once('helpers.php');
require_once('config-tables-columns.php');

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Checking for upload fields
    $upload_results = array();
    $upload_errors = array();

    // Use the backup fields to look for submitted files, if any
    foreach ($_POST as $key => $value) {

        // Check for $_POST cruddiy_backup_xxx to determine $_FILES xxx
        // We don't loop through $_FILES directly to handle value backup more easily
        if (substr($key, 0, 15) === 'cruddiy_backup_') {
            $originalKey = substr($key, 15);
            // Check if a file was uploaded for this field
            if (isset($_FILES[$originalKey]) && $_FILES[$originalKey]['error'] == UPLOAD_ERR_OK) {
                // Handle the file upload
                $this_upload = handleFileUpload($_FILES[$originalKey]);
                $upload_results[] = $this_upload;

                // If the upload was successful, update $_POST
                if (!in_array(true, array_column($this_upload, 'error')) && !array_key_exists('error', $this_upload)) {
                    $_POST[$originalKey] = $this_upload['success'];

                    // And we can safely delete the previous file
                    unlink($upload_target_dir . $_POST['cruddiy_backup_' . $originalKey]);
                }
            } else {
                // No file uploaded, use the backup
                $_POST[$originalKey] = $value;
            }
        }


        // Check for cruddiy_delete_xxx and set corresponding $_POST['xxx'] to blank
        if (substr($key, 0, 15) === 'cruddiy_delete_') {
            $deleteKey = substr($key, 15);

            if (isset($_POST['cruddiy_delete_' . $deleteKey]) && $_POST['cruddiy_delete_' . $deleteKey]) {
                // Set the corresponding field to blank
                $_POST[$deleteKey] = '';

                // And we can safely delete the file
                @unlink($upload_target_dir . $_POST['cruddiy_backup_' . $deleteKey]);
            }
        }
    }

    $upload_errors = array();
    foreach ($upload_results as $result) {
        if (isset($result['error'])) {
            $upload_errors[] = $result['error'];
        }
    }

    // Check for regular fields
    if (!in_array(true, array_column($upload_results, 'error'))) {

        $id_grapesjs = trim($_POST["id_grapesjs"]);
		$name = trim($_POST["name"]);
		$content_grapesjs = $_POST["content_grapesjs"] == "" ? null : trim($_POST["content_grapesjs"]);
		$content_html = $_POST["content_html"] == "" ? null : trim($_POST["content_html"]);
		$filter = $_POST["filter"] == "" ? null : trim($_POST["filter"]);
		$flag = $_POST["flag"] == "" ? null : trim($_POST["flag"]);
		$email = $_POST["email"] == "" ? null : trim($_POST["email"]);
		$subject = $_POST["subject"] == "" ? null : trim($_POST["subject"]);
		$createdate = trim($_POST["createdate"]);
		

        // Prepare an update statement

        $stmt = $link->prepare("UPDATE `newsletters` SET `id_grapesjs`=?,`name`=?,`content_grapesjs`=?,`content_html`=?,`filter`=?,`flag`=?,`email`=?,`subject`=?,`createdate`=? WHERE `id`=?");

        try {
            $stmt->execute([ $id_grapesjs, $name, $content_grapesjs, $content_html, $filter, $flag, $email, $subject, $createdate, $id  ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = $e->getMessage();
        }

        if (!isset($error)){
            header("location: newsletters-read.php?id=$id");
        } else {
            $uploaded_files = array();
            foreach ($upload_results as $result) {
                if (isset($result['success'])) {
                    // Delete the uploaded files if there were any error while saving postdata in DB
                    unlink($upload_target_dir . $result['success']);
                }
            }
        }

    }
}
// Check existence of id parameter before processing further
$_GET["id"] = trim($_GET["id"]);
if(isset($_GET["id"]) && !empty($_GET["id"])){
    // Get URL parameter
    $id =  trim($_GET["id"]);

    // Prepare a select statement
    $sql = "SELECT * FROM `newsletters` WHERE `id` = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        // Set parameters
        $param_id = $id;

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

                // Retrieve individual field value

                $id_grapesjs = htmlspecialchars($row["id_grapesjs"] ?? "");
					$name = htmlspecialchars($row["name"] ?? "");
					$content_grapesjs = htmlspecialchars($row["content_grapesjs"] ?? "");
					$content_html = htmlspecialchars($row["content_html"] ?? "");
					$filter = htmlspecialchars($row["filter"] ?? "");
					$flag = htmlspecialchars($row["flag"] ?? "");
					$email = htmlspecialchars($row["email"] ?? "");
					$subject = htmlspecialchars($row["subject"] ?? "");
					$createdate = htmlspecialchars($row["createdate"] ?? "");
					

            } else{
                // URL doesn't contain valid id. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else{
            translate('stmt_error') . "<br>".$stmt->error;
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

}  else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php translate('Update Record') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2><?php translate('Update Record') ?></h2>
                    </div>
                    <?php print_error_if_exists(@$upload_errors); ?>
                    <?php print_error_if_exists(@$error); ?>
                    <p><?php translate('update_record_instructions') ?></p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                                            <label for="id_grapesjs">id_grapesjs*</label>
                                            <input type="text" name="id_grapesjs" id="id_grapesjs" maxlength="100" class="form-control" value="<?php echo @$id_grapesjs; ?>">
                                        </div>
						<div class="form-group">
                                            <label for="name">name*</label>
                                            
<input type="file" name="name" id="name" class="form-control">
<input type="hidden" name="cruddiy_backup_name" id="cruddiy_backup_name" value="<?php echo @$name; ?>">
<?php if (isset($name) && !empty($name)) : ?>
<div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" id="cruddiy_delete_name" name="cruddiy_delete_name" value="1">
    <label class="custom-control-label" for="cruddiy_delete_name">
<?php translate("Delete:") ?>: <a href="uploads/<?php echo $name ?>" target="_blank"><?php echo $name ?></a>    </label>
</div>
<?php endif ?>

                                        </div>
						<div class="form-group">
                                            <label for="content_grapesjs">content_grapesjs</label>
                                            <textarea name="content_grapesjs" id="content_grapesjs" class="form-control"><?php echo @$content_grapesjs; ?></textarea>
                                        </div>
						<div class="form-group">
                                            <label for="content_html">content_html</label>
                                            <textarea name="content_html" id="content_html" class="form-control"><?php echo @$content_html; ?></textarea>
                                        </div>
						<div class="form-group">
                                            <label for="filter">filter</label>
                                            <input type="text" name="filter" id="filter" maxlength="100" class="form-control" value="<?php echo @$filter; ?>">
                                        </div>
						<div class="form-group">
                                            <label for="flag">flag</label>
                                            <input type="text" name="flag" id="flag" maxlength="10" class="form-control" value="<?php echo @$flag; ?>">
                                        </div>
						<div class="form-group">
                                            <label for="email">email</label>
                                            <input type="text" name="email" id="email" maxlength="200" class="form-control" value="<?php echo @$email; ?>">
                                        </div>
						<div class="form-group">
                                            <label for="subject">subject</label>
                                            <input type="text" name="subject" id="subject" maxlength="200" class="form-control" value="<?php echo @$subject; ?>">
                                        </div>
						<div class="form-group">
                                            <label for="createdate">createdate*</label>
                                            <input type="text" name="createdate" id="createdate" class="form-control" value="<?php echo @$createdate; ?>">
                                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <p>
                            <input type="submit" class="btn btn-primary" value="<?php translate('Edit') ?>">
                            <a href="javascript:history.back()" class="btn btn-secondary"><?php translate('Cancel') ?></a>
                        </p>
                        <hr>
                        <p>
                            <a href="newsletters-read.php?id=<?php echo $_GET["id"];?>" class="btn btn-info"><?php translate('View Record') ?></a>
                            <a href="newsletters-delete.php?id=<?php echo $_GET["id"];?>" class="btn btn-danger"><?php translate('Delete Record') ?></a>
                            <a href="newsletters-index.php" class="btn btn-primary"><?php translate('Back to List') ?></a>
                        </p>
                        <p><?php translate('required_fiels_instructions') ?></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
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