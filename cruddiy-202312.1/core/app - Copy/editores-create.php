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

$serialString = generateRandomString();											
											
echo $serialString; 
											
											
											?>">
                                        </div>
						<div class="form-group">
                                            <label for="nombre">nombre*</label>
                                            
                                            <input type="text" name="nombre" id="nombre" maxlength="100" class="form-control" value="<?php echo @$nombre; ?>">

                                        </div>
						<div class="form-group">
                                            <label for="contenido_grapesjs">contenido_grapesjs</label>
                                            <textarea name="contenido_grapesjs" id="contenido_grapesjs" class="form-control"><?php 
											
$template = base64_encode('<mjml>
<mj-body id="ib2i">
  <mj-section background-color="#f0f0f0" id="i5pi">
    <mj-column id="ityj">
      <mj-text font-style="italic" font-size="20px" color="#626262" id="ivcv">
        My Company
      </mj-text>
    </mj-column>
  </mj-section>
  <mj-section background-url="https://i.postimg.cc/nh8wX9pV/ARMY-ENGLISH.jpg" background-size="cover" background-repeat="no-repeat" id="i7af">
    <mj-column id="iayl">
      <mj-text align="center" color="#fff" font-size="40px" font-family="Helvetica Neue" id="igp1">Slogan here
      </mj-text>
      <mj-button background-color="#F63A4D" href="#" id="iaes2">
        Promotion
      </mj-button>
    </mj-column>
  </mj-section>
  <mj-section background-color="#fafafa" id="iw9f1">
    <mj-column width="400px" id="ib2wd">
      <mj-text font-style="italic" font-size="20px" font-family="Helvetica Neue" color="#626262" id="ie64w">My Awesome Text
      </mj-text>
      <mj-text color="#525252" id="ie5sa">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin rutrum enim eget magna efficitur, eu semper augue semper. Aliquam erat volutpat. Cras id dui lectus. Vestibulum sed finibus lectus, sit amet suscipit nibh. Proin nec commodo purus. Sed eget
        nulla elit. Nulla aliquet mollis faucibus.
      </mj-text>
      <mj-button background-color="#F45E43" href="#" id="ioeh9">Learn more
      </mj-button>
    </mj-column>
  </mj-section>
  <mj-wrapper border="1px solid #000000" padding="50px 30px" id="ip8na">
    <mj-section border-top="1px solid #aaaaaa" border-left="1px solid #aaaaaa" border-right="1px solid #aaaaaa" padding="20px" id="ijua7">
      <mj-column id="ig36k">
        <mj-image padding="0" src="https://via.placeholder.com/350x250/78c5d6/fff" id="ilmof">
        </mj-image>
      </mj-column>
    </mj-section>
    <mj-section border-left="1px solid #aaaaaa" border-right="1px solid #aaaaaa" padding="20px" border-bottom="1px solid #aaaaaa" id="i3yli">
      <mj-column border="1px solid #dddddd" id="icd2v">
        <mj-text padding="20px" id="irq2k"> First line of text 
        </mj-text>
        <mj-divider border-width="1px" border-style="dashed" border-color="lightgrey" padding="0 20px" id="iifri">
          <mj-text padding="20px" id="invhq"> Second line of text 
          </mj-text>
        </mj-divider>
      </mj-column>
    </mj-section>
  </mj-wrapper>
  <mj-raw>
    <div class="container">
      <img src="https://source.unsplash.com/random/200x141" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x142" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x143" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x144" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x145" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x146" alt="Example image" class="item"/>
    </div>
  </mj-raw>
  <mj-raw>
    <div class="container">
      <img src="https://source.unsplash.com/random/200x141" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x142" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x143" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x144" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x145" alt="Example image" class="item"/>
      <img src="https://source.unsplash.com/random/200x146" alt="Example image" class="item"/>
    </div>
  </mj-raw>
  <mj-section background-color="white" id="iyt74">
    <mj-column id="iyj7f">
      <mj-image width="200px" src="https://designspell.files.wordpress.com/2012/01/sciolino-paris-bw.jpg" id="ipw9c">
      </mj-image>
    </mj-column>
    <mj-column id="iug8k">
      <mj-text font-style="italic" font-size="20px" font-family="Helvetica Neue" color="#626262" id="iee1l">
        Find amazing places
      </mj-text>
      <mj-text color="#525252" id="iemlr">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin rutrum enim eget magna efficitur, eu semper augue semper. Aliquam erat volutpat. Cras id dui lectus. Vestibulum sed finibus lectus.
      </mj-text>
    </mj-column>
  </mj-section>
  <mj-section background-color="#fbfbfb" id="ireth">
    <mj-column id="iex4o">
      <mj-image width="100px" src="//191n.mj.am/img/191n/3s/x0l.png" id="ivfll">
      </mj-image>
    </mj-column>
    <mj-column id="ipmz4">
      <mj-image width="100px" src="//191n.mj.am/img/191n/3s/x01.png" id="itckf">
      </mj-image>
    </mj-column>
    <mj-column id="iggmh">
      <mj-image width="100px" src="//191n.mj.am/img/191n/3s/x0s.png" id="ixtwu">
      </mj-image>
    </mj-column>
  </mj-section>
  <mj-section background-color="#e7e7e7" id="ijo3a">
    <mj-column id="i2s9h">
      <mj-button href="#" id="ijyvf">Hello There!
      </mj-button>
      <mj-social font-size="15px" icon-size="30px" mode="horizontal" id="iapyi">
        <mj-social-element name="facebook" href="https://mjml.io/" id="ixgs7">
          Facebook
        </mj-social-element>
        <mj-social-element name="google" href="https://mjml.io/" id="ifojk">
          Google
        </mj-social-element>
        <mj-social-element name="twitter" href="https://mjml.io/" id="iuasf">
          Twitter
        </mj-social-element>
      </mj-social>
    </mj-column>
  </mj-section>
</mj-body>
</mjml>');											
											
											echo $template; 
											
											
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
                                            <input type="text" name="fecha" id="fecha" class="form-control" value="<?php 
											$timestamp = date('Y-m-d G:i:s');
											echo $timestamp; ?>">
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