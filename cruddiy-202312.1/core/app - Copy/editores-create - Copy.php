<?php
require_once('config.php');
require_once('helpers.php');

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Checking for upload fields
    $upload_results = array();
    if (!empty($_FILES)) {
        foreach ($_FILES as $key => $value) {
            // Check if the file was actually uploaded
            if ($value['error'] != UPLOAD_ERR_NO_FILE) {
                // echo "Field " . $key . " is a file upload.\n";
                $this_upload = handleFileUpload($_FILES[$key]);
                $upload_results[] = $this_upload;
                // Put the filename in the POST data to save it in DB
                if (!in_array(true, array_column($this_upload, 'error')) && !array_key_exists('error', $this_upload)) {
                    $_POST[$key] = $this_upload['success'];
                }
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
		$nombre = trim($_POST["nombre"]);
		$contenido_grapesjs = $_POST["contenido_grapesjs"] == "" ? null : trim($_POST["contenido_grapesjs"]);
		$contenido_html = $_POST["contenido_html"] == "" ? null : trim($_POST["contenido_html"]);
		$filtro = $_POST["filtro"] == "" ? null : trim($_POST["filtro"]);
		$indice = $_POST["indice"] == "" ? null : trim($_POST["indice"]);
		$email = $_POST["email"] == "" ? null : trim($_POST["email"]);
		$asunto = $_POST["asunto"] == "" ? null : trim($_POST["asunto"]);
		$fecha = trim($_POST["fecha"]);
		


        $stmt = $link->prepare("INSERT INTO `editores` (`id_grapesjs`, `nombre`, `contenido_grapesjs`, `contenido_html`, `filtro`, `indice`, `email`, `asunto`, `fecha`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        try {
            $stmt->execute([ $id_grapesjs, $nombre, $contenido_grapesjs, $contenido_html, $filtro, $indice, $email, $asunto, $fecha ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $error = $e->getMessage();
        }

        if (!isset($error)){
            $new_id = mysqli_insert_id($link);
            header("location: editores-read.php?id=$new_id");
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php translate('Add New Record') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2><?php translate('Add New Record') ?></h2>
                    </div>
                    <?php print_error_if_exists(@$upload_errors); ?>
                    <?php print_error_if_exists(@$error); ?>
                    <p><?php translate('add_new_record_instructions') ?></p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                                            <label for="id_grapesjs">id_grapesjs*</label>
                                            <input type="text" name="id_grapesjs" id="id_grapesjs" maxlength="100" class="form-control" value="<?php 
											
function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

$serialString = generateRandomString();											
											
echo $serialString; 
											
											
											?>">
                                        </div>
						<div class="form-group">
                                            <label for="nombre">nombre*</label>
                                            
<input type="file" name="nombre" id="nombre" class="form-control">
<input type="hidden" name="cruddiy_backup_nombre" id="cruddiy_backup_nombre" value="<?php echo @$nombre; ?>">
<?php if (isset($nombre) && !empty($nombre)) : ?>
<div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" id="cruddiy_delete_nombre" name="cruddiy_delete_nombre" value="1">
    <label class="custom-control-label" for="cruddiy_delete_nombre">
<?php translate("Delete:") ?>: <a href="uploads/<?php echo $nombre ?>" target="_blank"><?php echo $nombre ?></a>    </label>
</div>
<?php endif ?>

                                        </div>
						<div class="form-group">
                                            <label for="contenido_grapesjs">contenido_grapesjs</label>
                                            <textarea name="contenido_grapesjs" id="contenido_grapesjs" class="form-control"><?php 
											
											
											echo @$contenido_grapesjs; 
											
											
											?></textarea>
                                        </div>
						<div class="form-group">
                                            <label for="contenido_html">contenido_html</label>
                                            <textarea name="contenido_html" id="contenido_html" class="form-control"><?php echo @$contenido_html; ?></textarea>
                                        </div>
						<div class="form-group">
                                            <label for="filtro">filtro</label>
                                            <input type="text" name="filtro" id="filtro" maxlength="100" class="form-control" value="<?php echo @$filtro; ?>">
                                        </div>
						<div class="form-group">
                                            <label for="indice">indice</label>
                                            <input type="text" name="indice" id="indice" maxlength="10" class="form-control" value="<?php echo @$indice; ?>">
                                        </div>
						<div class="form-group">
                                            <label for="email">email</label>
                                            <input type="text" name="email" id="email" maxlength="200" class="form-control" value="<?php echo @$email; ?>">
                                        </div>
						<div class="form-group">
                                            <label for="asunto">asunto</label>
                                            <input type="text" name="asunto" id="asunto" maxlength="200" class="form-control" value="<?php echo @$asunto; ?>">
                                        </div>
						<div class="form-group">
                                            <label for="fecha">fecha*</label>
                                            <input type="text" name="fecha" id="fecha" class="form-control" value="<?php echo @$fecha; ?>">
                                        </div>

                        <input type="submit" class="btn btn-primary" value="<?php translate('Create') ?>">
                        <a href="editores-index.php" class="btn btn-secondary"><?php translate('Cancel') ?></a>
                    </form>
                    <p><small><?php translate('required_fiels_instructions') ?></small></p>
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