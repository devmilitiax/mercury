<?php
	include("../config/config.php");
	if(isset($_REQUEST['file']))
		$file=$_REQUEST['file'];
	if(isset($file))
		@include("../config/".$file.".php");
		
	echo '<table id="config_t">';?>
    
    	<tr><td><span class="label label-inverse">Configuration Name: </span></td><td><input data-original-title="Configuration Name" data-toggle="popover" data-placement="right" data-content='Write a name for the new setup configuration' type="text" name="nam" placeholder="name" maxlength="25" onkeypress="return ValName(event);" value="<?php if(isset($file)){echo $file;} ?>" readonly></td></tr>
    
        <tr><td><span class="label label-inverse">DB Type: </span></td><td>
        <select id="dbselect" name="dbselect" disabled>
            <option value="mysql" <?php if(isset($db)){if($db=="mysql") echo "selected";} ?>>MySQL</option>
            <option value="postgres" <?php if(isset($db)){if($db=="postgres") echo "selected";} ?>>PostgreSQL</option>
            <option value="sqlserver" <?php if(isset($db)){if($db=="sqlserver") echo "selected";} ?>>SQL Server</option>
			<option value="sqlite" <?php if(isset($db)){if($db=="sqlite") echo "selected";} ?>>SQLite</option>
		</select></td></tr>
        
        <tr id='show_host'><td><span class="label label-inverse">Host: </span></td><td><input data-original-title="Host" data-toggle="popover" data-placement="right" data-content='This field specifies the location of the database. If you are running the web service locally this field will usually be "localhost".' type="text" id="host" name="host" placeholder="localhost" value="<?php if(isset($host)){echo $host;} ?>" readonly></td></tr>
        
        <tr id="show_port"><td><span class="label label-inverse">Port: </span></td><td><input data-original-title="Port" data-toggle="popover" data-placement="right" data-content='For PostgreSQL, besides the name you must also specify the port that PostgreSQL is using. By default this port is 5432' type="text" name="port" id="port" placeholder="5432" value="<?php if(isset($port)){echo $port;} ?>" readonly></td></tr>
        <tr id="action"><td><span class="label label-inverse">Action: </span></td><td> <span class='label label-info'>Use existent DB:</span> <input type="radio" name="action" value="create"> <span class='label label-info'>Upload DB:</span> <input type="radio" value="upload" name="action"></td></tr>
        <tr id="dbname"><td><span class="label label-inverse">DB Name: </span></td><td><input data-original-title="Database Name" data-toggle="popover" data-placement="right" data-content='Here you have to write the name of your database (For SQLite: use this field if you already have uploaded your DB file to the folder "sqlite")' type="text" placeholder="DB name" id="dbname" name="dbname" value="<?php if(isset($dbname)){echo $dbname;} ?>" readonly></td></tr>
        <tr id="upload_file"><td><span class="label label-inverse">Select file: </span></td><td><input class="btn btn-info" type="file" id="upload" name="upload"></td></tr>
        <tr id="show_autenticacion"><td><span class="label label-inverse">Authentication<br/>mode: </span></td><td><select name="autenticacion" id="autenticacion" disabled><option value="windows" <?php if(isset($autenticacion)){if($autenticacion=="windows") echo "selected";} ?>>Windows Authentication</option><option value="sqlserver" <?php if(isset($autenticacion)){if($autenticacion=="sqlserver") echo "selected";} ?>>SQL Server Authentication</option></select></td></tr>

        <tr class="credenciales"><td><span class="label label-inverse">User Name: </span></td><td><input data-original-title="User Name" data-toggle="popover" data-placement="right" data-content='User that you use for connect you to your database' type="text" id="usuario" name="usuario" placeholder="User"></td></tr>
        
        <tr class="credenciales"><td><span class="label label-inverse">Password: </span></td><td><input data-original-title="Password" data-toggle="popover" data-placement="right" data-content='Password that you use for connect you to your database' type="password" id="contra" name="contra"></td></tr>
        </table>