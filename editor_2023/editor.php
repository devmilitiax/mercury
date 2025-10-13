<?php 
require_once('../mysqli.inc.php');
$id_editor = $_GET["id_editor"];

$sqlSelect ="SELECT * from newsletters where id='".$id_editor."'";
$conexion=new mysqli($cfg_servidor,$cfg_usuario,$cfg_password,$cfg_basephp);
mysqli_set_charset($conexion,"utf8");
if ($resultado = $conexion->query($sqlSelect)) {
  $row =$resultado->fetch_assoc();  
}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Mercury Launcher</title>

<meta http-equiv="Expires" content="0">
<meta http-equiv="Last-Modified" content="0">
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="Pragma" content="no-cache">

  <script src="jquery.min.js"></script>

<!-- partial:index.partial.html -->
<link href="grapes.min.css" rel="stylesheet"/>
<script src="grapes.min.js"></script>
<script src="grapesjs-mjml.js"></script>
<style>body, html{ height: 100%; margin: 0;}</style>

<link href="emojilib/css/emoji.css" rel="stylesheet">
</head>
<body>
<!-- VENTANA GUARDAR-->
<form id="test-form" style="display:none">
	  <input type="text" name="id_editor" value="<?php echo($id_editor); ?>">
    <input type="text" name="campa">
    <input type="text" name="email">
</form>
<!-- VENTANA GUARDAR -->
<div id="gjs" style="max-height:calc(100vh - 56px); height:0; width:100%; overflow:hidden">
<?php echo base64_decode($row['content_grapesjs']) ?>
</div>

<script type="text/javascript">

  // -- SETUP
  var editor = grapesjs.init({
    height: '100%',
    container : '#gjs',
    fromElement: true,
    
    storageManager:{
      id: 'gjs-',             // Prefix identifier that will be used on parameters
      type: 'local',          // Type of the storage
      autosave: true,         // Store data automatically
      autoload: 0,         // Autoload stored data on init
      stepsBeforeSave: 1,     // If autosave enabled, indicates how many changes are necessary before store method is triggered
    },
	assetManager: {
	  upload: 0,
	  //uploadText: 'Uploading is not available in this version',
	},

    plugins: [
      'grapesjs-mjml'
    ],
    pluginsOpts: {
      'grapesjs-mjml': {
        resetDevices: false // so we can use the device buttons
      }
    }

  });

      // Let's add in this demo the possibility to save newsletters
      var pnm = editor.Panels;
      var cmdm = editor.Commands;
      var testContainer = document.getElementById("test-form");
      var contentEl = testContainer.querySelector('input[name=campa]');
      var contentEmail = testContainer.querySelector('input[name=email]');
      //var md = editor.Modal;
      cmdm.add('send-test', {
        run(editor, sender) {

          var Html = editor.getHtml();
          //var Css = editor.getCss();
          //var cmdGetCode = Html += Css;
          contentEl.value = Html;

          var Email = editor.runCommand('mjml-code-to-html').html;
          contentEmail.value = Email;

          $.ajax({
                url: 'A_update_newsletter.php',
                type: 'POST',
                data: $('#test-form').serialize(),
                success: function (result) {
					          alert('Newsletter guardado!');
                }
            });

        }
      });
      pnm.addButton('options', {
        id: 'send-test',
        className: 'fa fa-floppy-o',
        command: 'send-test',
        attributes: {
          'title': 'Test Newsletter',
          'data-tooltip-pos': 'bottom',
        },
      });

</script>
<!-- partial -->

<script>
$(document).ready(function () {
        $("#btn_enviar").click(function () {
            $.ajax({
                url: 'C_send_newsletter.php',
                type: 'POST',
                data: $('#test-form').serialize(),
                success: function (result) {
                    md.close();
					alert('Mensaje enviado!');
                }
            });
        });
});

//Save

$(document).ready(function () {
        $("#btn_guardar").click(function () {
            $.ajax({
                url: 'A_update_newsletter.php',
                type: 'POST',
                data: $('#test-form').serialize(),
                success: function (result) {
                    md.close();
					alert('Newsletter guardado!');
                }
            });
        });
});
</script>
    <script src="emojilib/js/config.js"></script>
    <script src="emojilib/js/util.js"></script>
    <script src="emojilib/js/jquery.emojiarea.js"></script>
    <script src="emojilib/js/emoji-picker.js"></script>
	
    <script> 
	$(function() {
		window.emojiPicker = new EmojiPicker({
		  emojiable_selector: '[data-emojiable=true]',
		  assetsPath: 'emojilib/img/',
		  popupButtonClasses: 'fa fa-smile-o'
		});
		window.emojiPicker.discover();
	});
    </script>

</body>
</html>
