$(document).ready(inicializar);
var conditions=0;

function inicializar(){
	init();
	//**************************************All***********************************************
	$("ul.nav>li>a").click(function(e){return false;});
	//**********************************other_options.php*************************************
	$("#b_save_config").click(guardar_configuracion);
	
	//**insertar_csv.php**exportar_info.php**consultar_info.php**consultar_info_avanzada.php**
	//insertar_excel.php
	$("#tabla").change(mostrar_arc);
	
	//********************************configuracion.php***************************************
	$("#b_test").click(mostrar_save);
	$("#save").click(guardar_archivo);
	$("#dbselect").change(modificar_configuracion);
	$("#autenticacion").change(mostrar_credenciales);
	$("#b_n").click(new_configuration);
	$("#cancel").click(cancel_configuration);
	$("#ok").click(validar_login);
	$("input[name='action']").click(modificar_configuracion);
	$("#upload").change(upload_sqlite);
	
	//************************************main.php********************************************
	$("#b_insertar").click(m_insertar);
	$("#b_exportar").click(m_exportar);
	$("#b_consult").click(m_consult);
	$("#b_backup").click(m_backup);
	$("#b_option").click(m_option);
	
	//***********************************index.php********************************************
	$("#entrar").click(login);
	$("#contra_ini").keypress(function(event){if(event.which==13)login();});
	$("#usuario_ini").keypress(function(event){if(event.which==13)login();});
	
	//********************************insertar_csv.php****************************************
	$("#archivo").change(validar_archivo);
	
	//********************************insertar_excel.php**************************************
	$("#archivo_excel").change(validar_archivo_excel);
	
	//**********************************csv_tabla.php*****************************************
	$("#b_insertar_data").click(insertar_data);
	
	//**********************************excel_tabla.php*****************************************
	$("#b_insertar_data_exc").click(insertar_data_excel);
	
	//*****************************excel_tabla.php****csv_tabla.php***************************
	$("#eliminar_data").change(alert_message);
	$("select[name='if_exists']").change(if_record_exists);
	$("#set_pk_cb").change(set_pk);
	
	//*******************************insertar_sql.php*****************************************
	$("#archivo_sql").change(validar_archivo_slq);
	
	//*******************************show_slq.php*********************************************
	$("#ejecutar_sql").click(ejecutar_sql);
	$("#ejecutar_sql_sin").click(ejecutar_sql_sin);
	
	//***************************exportar_info.php********************************************
	$("#b_export").click(exportar_archivo);
	
	//***************************consultar_info.php*******************************************
	$("#b_consult").click(function(e){
		mostrar_search(); 
		mostrar_tabla();
	});
	
	//**************************consultar_info_avanzada.php***********************************
	$("#expandir").click(function(e){$("#replegar").slideToggle();});
	$("#ejecutar_condicion").click(mostrar_tabla_avanzada);
	$("#form_consulta_avanzada").submit(before_submit);
	$(".tipo").click(cambiar_avanzado);
	$("#b_export_to li a").click(function(e){change_before_submit($(this).html());});
	$("#mostrar_av").click(mostrar_arc);
	$("#m_conditions").click(add_more_conditions);
	$("#guardar_condicion").click(function(e){$("#save_query_modal").modal('show');});
	$("#save_query").click(save_query);
	$("#cancel_query").click(function(e){$("#save_query_modal").modal('hide'); $("#problem").html("");});
	
	//*************consultar_info_avanzada.php**ejecutar_queries*********************************
	$("#ejecutar_inst").click(mostrar_tabla_mas_avanzada);
	
	//*********************************password**************************************************
	$("#change").click(credenciales);
	
	//************************************join_tables.php****************************************
	$("#next1").click(show_tables_fields);
	$("#more_c").click(show_join_conditions);
	$("#next3").click(show_other_conditions);
}

//================================================================================================================
//				join_tables.php
//================================================================================================================

	join_conditions=0;
	other_conditions=0;
	
	function show_other_conditions(){
		flag=true;
		for(i=0;i<join_conditions;i++)
			if($("select[name='b_tab"+i+"']").val()==$("select[name='a_tab"+i+"']").val())	flag=false;
		if(flag){
			html="<h3 style='color:#fff'>Select other conditions</h3><div id='o_conditions' style='padding-left:70px'></div>";
			html+="<br><div style='color:#fff;padding-left:70px'>Order By: ";
			objects=$("#tablas option:selected");
			tablas=new Array(objects.size());
			i=0;
			objects.each(function(e){
				tablas[i]=$(this).html();
				i++;
			});
			html+="<select name='order_tab' class='span2'>";
			for(i=0;i<tablas.length;i++){
				html+="<option value='"+tablas[i]+"'>"+tablas[i]+"</option>";
			}
			html+="</select> <select name='order_field' class='span2'>"+$("select[name='"+tablas[0]+"[]']").html()+"</select>";
			html+=" <select name='order_sence' class='span2'><option value='asc'>Ascendent</option><option value='desc'>Descendent</option></select>"
			html+="<br><br>Limits: From <input type='number' value='0' name='first' class='span1'> to <input type='number' value='20' name='last' class='span1'></div>";
			$("#other_conditions").html(html);
			$("select[name='order_tab']").change(function(e){
				$("select[name='order_field']").html($("select[name='"+$(this).val()+"[]']").html());
			})
			add_a_condition(tablas);
			//add_a_condition(tablas);
			$("#other_conditions").slideDown();
			$("#execute_field").slideDown();
		}else{
			alert("Review your join conditions!");
		}
	}
	
	function add_a_condition(tablas){
		if(other_conditions==1){
			$("#o_conditions").prepend("<div style='width:100%;text-align:left'><label class='label label-inverse'>Match All</label> <input type='radio' name='c_option' value='0' checked> <label class='label label-inverse'>Match Any</label> <input type='radio' name='c_option' value='1'></div>");
		}
		select_table=((other_conditions!=0)?"<br>":"")+"<br><select name='c_tab"+other_conditions+"' class='span2'>";
		for(i=0;i<tablas.length;i++){
			select_table+="<option value='"+tablas[i]+"'>"+tablas[i]+"</option>";
		}
		select_table+="</select> ";
		select_table+="<select name='c_fields"+other_conditions+"' class='span2'>";
		select_table+=$("select[name='"+tablas[0]+"[]']").html();
		select_table+="</select> <select name='operador"+other_conditions+"' class='span1'><option value='0' style='background:#000'>none</option>option value='='>=</option><option value='>'>&gt;</option><option value='<'>&lt;</option><option value='>='>&gt;=</option><option value='<='>&lt;=</option><option value='<>'>Different To</option><option value='like'>Contains</option><option value='not like'>Doesn't Contain</option></select> ";
		select_table+="<input type='text' placeholder='condition' name='c_value"+other_conditions+"' class='span2'>"+((other_conditions==0)?" <input type='button' value='+' class='btn btn-success' id='m_cond'>":"");
		$("#o_conditions").append(select_table);
		if(other_conditions==0) $("#m_cond").click(function(e){add_a_condition(tablas);});
		$("select[name='c_tab"+other_conditions+"']").change(function(e){
			$("select[name='c_fields"+$(this).prop("name").replace("c_tab","")+"']").html($("select[name='"+$(this).val()+"[]']").html());
		})
		other_conditions++;
	}
	
	function show_tables_fields(){
		objects=$("#tablas option:selected");
		tablas=new Array(objects.size());
		i=0;
		objects.each(function(e){
			tablas[i]=$(this).html();
			i++;
		})
		if(objects.size()>=2){
			other_conditions=0;
			$("#join_conditions").html("");
			$("#button_next2").hide();
			$("#other_conditions").hide();
			$("#other_conditions").html("");
			$("#execute_field").hide();
			$.post("../resources/get_tables_fields.php", {"tablas":tablas}, function(e){
				$("#tables_fields").html(e);
				$("#next2").click(function(e){
					$("#join_conditions").html("");
					$("#button_next2").hide();
					join_conditions=0;
					show_join_conditions();
				});
			});
		}else{
			alert("You must select at least 2 tables!");
		}
	}
	
	function show_join_fields(number,leter){
		tab=$("select[name='"+leter+"_tab"+number+"']").val();
		$("#"+leter+"_field"+number).html($("select[name='"+tab+"[]']").html());
	}
	
	function execute_join(){
		$.post("../resources/generar_join_tabla.php", $("#form_consulta_avanzada").serialize(), function(data){
			$("#resultado").html(data);
			$("#resultado").slideDown();
			$("#replegar").slideUp();
		});
	}
	
	function show_join_conditions(){
		objects=$("#tablas").val();
		tablas=new Array(objects.length);
		select="";
		select_table="";
		for(i=0;i<objects.length;i++){
			tablas[i]=objects[i];
		}
		for(i=0;i<tablas.length;i++){
			select_table+="<option value='"+tablas[i]+"'>"+tablas[i]+"</option>";
		}
		if(join_conditions==0){
			select="<h3 style='color:#fff;' class='left'>Select the join conditions</h3>";
		}
		select+="<div style='padding:10px'><select name='a_tab"+join_conditions+"' id='a_tab"+join_conditions+"' class='span2'>"+select_table+"</select>";
		select+=" <select name='a_field"+join_conditions+"' id='a_field"+join_conditions+"' class='span2'></select> <strong class='label label-info'>=</strong> ";
		select+="<select name='b_tab"+join_conditions+"' id='b_tab"+join_conditions+"' class='span2'>"+select_table+"</select>";
		select+=" <select name='b_field"+join_conditions+"'id='b_field"+join_conditions+"' class='span2'></select></div>";
		$("#join_conditions").append(select);
		$("#a_tab"+join_conditions).change(function(e){
			number=$(this).prop("name").replace("a_tab","");
			show_join_fields(number,"a");
		});
		$("#b_tab"+join_conditions).change(function(e){
			number=$(this).prop("name").replace("b_tab","");
			show_join_fields(number,"b");
		});
		if(join_conditions==0){
			$("#button_next2").slideDown();
			$("#show_join").click(execute_join);
		}
		show_join_fields(join_conditions,"a");
		show_join_fields(join_conditions,"b");
		join_conditions++;
	}

//================================================================================================================
//				GLOBAL
//================================================================================================================

	function init(){
		$("#button_next2").hide();
		$("#other_conditions").hide();
		$("#execute_field").hide();
		$(".loader").hide();
		$(".credenciales").hide();
		$(".cover").hide();
		$(".result").hide();
		$("#menu").hide();
		$("#menu").fadeIn();
		$(".menu_final").hide();
		$("#respuesta").hide();
		$("#save").hide();
		$("#hidden_buttons").hide();
		$("#search").hide();
		$("#m_insertar").hide();
		$("#m_exportar").hide();
		$("#m_backup").hide();
		$("#m_option").hide();
		if($("#tabla").val()=="0"){
			$("#m_arch").hide();
			$("#export_option").hide();
		}
		$("#csv_tabla").hide();
		$("#subir_archivo").hide();
		$(".resultado").hide();
		$("#subir_archivo_sql").hide();
		if($("input[name='tipo']:checked").val()=="advanced"){
			$("#custom").hide();
		}else{
			$("#consulta_mas_avanzada").hide();
		}
		$(".mostrar_avanzado").hide();
		modificar_configuracion();
		$("#action").hide();
		if_record_exists();
	}


//================================================================================================================
//				consultar_info.php
//				consultar_info_avanzada.php
//				insertar_excel.php
//				insertar_csv.php
//				exportar_info.php
//================================================================================================================

	function mostrar_arc(){
		if($("#tabla").val()=="0"){
			$("#m_arch").slideUp();
			$("#select_c").slideUp();
			//For consultar_info_avanzada.php
			$(".mostrar_avanzado").slideUp();
			$("#export_option").slideUp();
			$("#result").slideUp();
			$("#paginador").fadeOut();
			$("#search").fadeOut();
			$("#search").html("");
		}else{
			$("#m_arch").slideDown();
			if($("#archivo_ext").val()=="pdf"||$("#archivo_ext").val()=="csv"||$("#archivo_ext").val()=="sql"||$("#archivo_ext").val()=="excel"||$("#archivo_ext").val()=="xml"){
				$.get("../resources/seleccionar_campos.php", {"tabla":$("#tabla").val()}, function(data){$("#select_c").html("<td style='text-align:right'><span class='label label-info'>Select the fields<br/>that you want<br/> to include</span></td><td>"+data+"</td>")});
				$("#select_c").slideDown();
			}
			//For consultar_info_avanzada.php
			$.get("../resources/campos.php", {"tabla":$("#tabla").val()}, function(data){$("#campos").html(data)});
			$.get("../resources/seleccionar_campos.php", {"tabla":$("#tabla").val()}, function(data){$("#seleccionar_campos").html(data)});
			$.get("../resources/seleccionar_campo_unico.php", {"tabla":$("#tabla").val()}, function(data){$("#order_field").html(data+" <select id='asc-desc' name='asc-desc' class='span2'><option value='asc'>Ascendent</option><option value='desc'>Descendent</option></select>")});
			$(".mostrar_avanzado").delay(500).fadeIn("slow");
			$("#export_option").fadeIn();
		}
	}

//===========================================================================================================//
//				configuracion.php
//============================================================================================================//
	var show_credentials=0;
	
	function upload_sqlite(){
		filename = ($("#upload").val().substring($("#upload").val().lastIndexOf("\\"))).toLowerCase().replace('\\',"");
		extension = ($("#upload").val().substring($("#upload").val().lastIndexOf("."))).toLowerCase();
		if(extension=='.s3db'){
			$("input[name='dbname']").val(filename);
			$("#formulario_config").submit();
		}else{
			alert("Select a valid SQLite(.s3db) file");
			$("#upload").val("");
		}
	}
	
	function cancel_configuration(){
		show_credentials=0;
		$("#hidden_buttons").fadeOut(); 
		$("#buttons").fadeIn(); 
		mostrar_configuracion(); 
		$("#c_name").fadeIn(); 
		$(".credenciales").fadeOut();
	}
	
	function modificar_configuracion(){
		switch($("#dbselect").val()){
			case "postgres":
				$("#show_port").slideDown();
				$("#show_autenticacion").hide();
				$("#show_host").fadeIn();
				$("#action").hide();
				$("#upload_file").hide();
				$("#dbname").fadeIn();
			break;
			case "sqlserver":
				$("#show_port").hide();
				$("#show_autenticacion").fadeIn();
				$("#show_host").fadeIn();
				$("#action").hide();
				$("#upload_file").hide();
				$("#dbname").fadeIn();
			break;
			case "mysql":
				$("#show_port").hide();
				$("#show_autenticacion").hide();
				$("#show_host").fadeIn();
				$("#action").hide();
				$("#upload_file").hide();
				$("#dbname").fadeIn();
			break;
			case "sqlite":
				$("#show_port").hide();
				$("#show_autenticacion").hide();
				$("#show_host").hide();
				$("#action").fadeIn();
				if($("input[name='action']:checked").val()=="upload"){
					$("#upload_file").fadeIn();
					$("#dbname").hide();
				}else{
					$("#upload_file").hide();
					$("#dbname").fadeIn();
				}
			break;
		}
		mostrar_credenciales();
	}
	
	function mostrar_configuracion(){
		$.post("../resources/db_config.php", {"file":$("#configs").val()}, function(a){
			$("#my_config").fadeOut('fast', function(e){
				$("#my_config").html(a); 
				modificar_configuracion();
				$(".credenciales").hide();
				$("#my_config").fadeIn();
				$("#dbselect").change(modificar_configuracion);
				$("#autenticacion").change(mostrar_credenciales);
				if($("#configs").val()=='0')
					$("input[name='nam']").val("");
				$("#action").hide();
			});
		}); 
	}
	
	function mostrar_credenciales(){
		if(show_credentials==1){
			if(($("#dbselect").val()=="sqlserver")||($("#dbselect").val()=="sqlite")){
				if($("#autenticacion").val()!="windows"){
					$(".credenciales").fadeIn();
				}else{
					$(".credenciales").hide();
				}
			}else
				$(".credenciales").fadeIn();
		}
	}
	
	function mostrar_save(){
		cont=true;
		if($("input[name='dbname']").val()!=""){
			if($("#dbselect").val()!="sqlite"){
				if($("#dbselect").val()=="sqlserver"){
					if($("#autenticacion").val()!="windows"){
						if(($("#usuario").val()=="")||($("#contra").val()==""))
							cont=false;
					}
				}else{
					if(($("#usuario").val()=="")||($("#contra").val()==""))
							cont=false;
				}
			}
			if(cont){
				$.post("../resources/test.php", $("#formulario_config").serialize(), function(data){
					if(data=="true"){
						$("#problema").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>Success!</div>");
						$("#save").fadeIn();
						$("#problema").fadeIn();
					}else{
						$("#problema").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Error. Review your information<br>"+data+"</div>");
						$("#problema").fadeIn();
					}
				});
			}else{
				alert("You must write an User and Password!");
			}
		}else{
			alert("You must write a database name!");
		}
	}
	
	function guardar_archivo(){
		if($("input[name='nam']").val().toLowerCase()!=""){
			if(($("input[name='nam']").val().toLowerCase()!="config")&&($("input[name='nam']").val().toLowerCase()!="other_config")){
				$.get("../resources/guardar_archivo.php", $("#formulario_config").serialize(), function(data){
					alert("Information Saved");
					document.location.href="";
				})
			}else{
				alert("Select another Configuration Name");
			}
		}else{
			alert("Write a Configuration Name");
		}
	}
	
	function new_configuration(){
		$("#c_name").fadeOut();
		$("input[name='nam']").val("");
		$("input[name='host']").val("");
		$("input[name='port']").val("");
		$("input[name='upload']").val("");
		$("input[name='dbname']").val("");
		$("input[name='nam']").removeAttr("readonly");
		$("input[name='host']").removeAttr("readonly");
		$("input[name='port']").removeAttr("readonly");
		$("input[name='dbname']").removeAttr("readonly");
		$("#dbselect").removeAttr("disabled");
		$("#autenticacion").removeAttr("disabled");
		$("#hidden_buttons").fadeIn();
		$("#buttons").fadeOut();
		show_credentials=1;
		mostrar_credenciales();
		if($("#dbselect").val()=="sqlite")	$("#action").fadeIn();
	}
	
	function delete_configuration(){
		$.get("../resources/login.php", {"usuario":$("#usua2").val(), "contra":$("#cont2").val()}, function(e){
					if(!String.prototype.trim){
					  String.prototype.trim = function(){
						return this.replace(/^\s+|\s+$/g, ''); 
					  } 
					}
					if(e.trim().toLowerCase()=="true"){
						$.get("../resources/usar_configuracion.php", {"delete":$("#configs").val()}, function(e){});
						$.get("../resources/eliminar_archivo.php", {"dir":"../config/", "file":$("#configs").val()+".php"}, function(e){
							alert($("#configs").val()+" deleted"); 
							$("#modal2").modal('hide');	
							document.location.href="";
						});
					}else{
						$("#problem").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Incorrect user or password</div>");
					}
			});
	}
	
	function validar_login(){
		$.get("../resources/login.php", {"usuario":$("#usua").val(), "contra":$("#cont").val()}, function(e){
					if(!String.prototype.trim){
					  String.prototype.trim = function(){
						return this.replace(/^\s+|\s+$/g, ''); 
					  } 
					}
					if(e.trim().toLowerCase()=="true"){
						$.get("../resources/usar_configuracion.php", {"file":$("#configs").val()}, function(e){alert($("#configs").val()+" is now on use"); document.location.href="../main.php";});
					}else{
						$("#problem2").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Incorrect user or password</div>");
					}
			});
	}

//================================================================================================================
//				consultar_info.php
//				consultar_info_avanzada.php
//				join_tables.php
//				consultar_list.php
//================================================================================================================

function change_before_submit(value){
	switch(value){
		case "To a CSV file":
			$("#form_consulta_avanzada").attr("action", "../resources/exportar_csv.php");
		break;
		case "To a PDF file":
			$("#form_consulta_avanzada").attr("action", "../resources/exportar_pdf.php");
		break;
		case "To a SQL file":
			$("#form_consulta_avanzada").attr("action", "../resources/exportar_sql.php");
		break;
		case "To a Excel file":
			$("#form_consulta_avanzada").attr("action", "../resources/exportar_excel.php");
		break;
		case "To a XML file":
			$("#form_consulta_avanzada").attr("action", "../resources/exportar_xml.php");
		break;
	}
	$('#form_consulta_avanzada').submit();
}

//================================================================================================================
//				consultar_info_avanzada.php
//================================================================================================================

function before_submit(){
	if($("input[name='tipo']:checked").val()!="easy"){
		if($("#inst_sql").val()==""){
			alert("You must write a sql instruction");
			return false;
		}
	}
}

function cambiar_avanzado(){
	$("#resultado").slideUp();
	if($("input[name='tipo']:checked").val()=="advanced"){
		$("#custom").slideUp();
		$("#consulta_mas_avanzada").slideDown();
		$("#export_option").delay(1000).fadeIn("slow");
	}else{
		if($("#tabla").val()!='0'){
			$("#export_option").hide();
		}
		//$(".mostrar_avanzado").hide();
		$("#custom").slideDown();
		$("#consulta_mas_avanzada").slideUp();
	}
}


function add_more_conditions(){
	fields=$("select[name='campos']").html();
	html="<br><br><select id='campos"+conditions+"' class='span1' name='campos"+conditions+"'>"+fields+"</select> <select id='operador"+conditions+"' name='operador"+conditions+"' class='span1'><option value='none' style='background:#000'>none</option><option value='='>=</option><option value='>'>&gt;</option><option value='<'>&lt;</option><option value='>='>&gt;=</option><option value='<='>&lt;=</option><option value='<>'>Different To</option><option value='like'>Contains</option><option value='not like'>Doesn't Contain</option></select>  <input type='text' name='condicion"+conditions+"' id='condicion"+conditions+"' placeholder='Search' class='input-medium'>";
	$("#conditions").append(html);
	if(conditions==0)
		$("#conditions").prepend("<div style='width:100%;text-align:left'><label class='label label-inverse'>Match All</label> <input type='radio' name='c_option' value='0' checked> <label class='label label-inverse'>Match Any</label> <input type='radio' name='c_option' value='1'></div><br>");
	conditions++;
}

//===========================================================================================================//
//			consultar_info_avanzada.php
//			consultar_list.php
//============================================================================================================//

function mostrar_tabla_avanzada(){
	$.post("../resources/generar_tabla_avanzada.php", $("#form_consulta_avanzada").serialize(), function(data){
		$("#resultado").html(data);
		$("#resultado").slideDown();
		$("#replegar").slideUp();
	});
}

//===========================================================================================================//
//			consultar_info_avanzada.php
//			ejecutar_sql.php
//============================================================================================================//

		function mostrar_tabla_mas_avanzada(){
			if($("#inst_sql").val()!=""){
				$.post("../resources/generar_tabla_libre.php", {"sql":$("#inst_sql").val(), "enable":$("#ejecutar_inst").val()}, function(data){
				$("#resultado").html(data);
				$("#resultado").slideDown();
				$("#replegar").slideUp();
				});
			}else{
				alert("Empty consult");
			}
		}
		
		function save_query(){
			$.post("../resources/save_queries.php", $("#form_consulta_avanzada").serialize(), function(e){
					if(e.trim().toLowerCase()=="true"){
						alert("Query saved!");
						$("#save_query_modal").modal('hide');
						$("#query_name").val("");
					}else{
						$("#problem").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>"+e.replace(/^\s+|\s+$/g, '')+"</div>");
					}
			});
		}

//================================================================================================================
//				consultar_info.php
//================================================================================================================

	firstFlagMode=true;
	firstTime=true;
	
	function mostrar_tabla(){
		if($("#tabla").val()=='0'){
			alert("Select a valid table");
		}else{
			$.get("../resources/generar_tabla.php", {"tabla":$("#tabla").val(), "search":$("#search_value").val(), "search_field":$("#select_campo_unico").val(), "order_field":$("input[name='select_campo_orden']").val(), "sence":$("input[name='asc-desc']").val()}, function(data){
				showData(data);
				firstTime=false;
			});
		}
	}
	
	function showData(data){
		$("#result").html(data);
		if(firstTime)	$("#result").slideDown();
		$("td span[title='edit']").click(function(e){mod_row($(this).attr("class"));});
		$("td span[title='see']").click(function(e){showDetails($(this).attr("class"));});
		$(".header th.ord").click(function(e){ordenar_tabla($(this).attr('name'));});
		$(".result_tabla tr").hover(paint_row,unpaint_row);
		$(".result_tabla tr").dblclick(function(e){showDetails($(this).attr("class"));});
		$("td.control input[type='checkbox']").change(paint_row_checked);
		$("input[name='n_pagina']").val($("#paginador").find(".actual").val());
		$("#paginador").find(".pag").click(function(e){cambiar_paginador($(this).val(),  $("#paginador").find(".actual").val())});
		$("th.control input[type='checkbox']").change(selectAll);
		$(".result_tabla").attr('width', '100%');
		objects=document.getElementsByClassName("result_tabla");
		objects=objects.item(0).getElementsByTagName("td");
		for(i=0;i<objects.length;i++){
			if((objects.item(i).scrollWidth!=objects.item(i).clientWidth)||(objects.item(i).scrollHeight!=objects.item(i).clientHeight)){
				objects.item(i).setAttribute("title", objects.item(i).innerHTML);
			}
		}
		objects=document.getElementsByClassName("result_tabla");
		objects=objects.item(0).getElementsByTagName("th");
		for(i=0;i<objects.length;i++){
			if((objects.item(i).scrollWidth!=objects.item(i).clientWidth)||(objects.item(i).scrollHeight!=objects.item(i).clientHeight)){
				title=objects.item(i).innerHTML;
				if(objects.item(i).getAttribute("name")==orderField){
					title=title.replace(' <i class="icon-chevron-down icon-white"></i>','');
					title=title.replace(' <i class="icon-chevron-up icon-white"></i>','');
				}
				objects.item(i).setAttribute("title", title);
			}
		}
	}
	
	function paint_row(){
		clase=$(this).attr("class");
		$("tr."+clase+" td i").addClass("icon-white");
		$("tr."+clase+" td[style]").addClass("row_painted");
	}
	
	function unpaint_row(){
		clase=$(this).attr("class");
		if($("input[type='checkbox']."+clase).prop('checked')!=true){
			$("tr."+clase+" td i").removeClass("icon-white");
			$("tr."+clase+" td[style]").removeClass("row_painted");
		}
	}
	
	function paint_row_checked(){
		clase=$(this).attr("class");
		clase=clase.replace(' row_painted','');
		if($("input[type='checkbox']."+clase).prop('checked')==true){
			$("tr."+clase).addClass("row_painted");
			$("tr."+clase+" td[style]").addClass("row_painted");
			$("tr."+clase+" i").addClass("icon-white");
		}else{
			$("tr."+clase).removeClass("row_painted");
			$("tr."+clase+" td[style]").removeClass("row_painted");
		}
	}
	
	function selectAll(){
		nResults=$('.result_tabla tr').size()-1;
		if($("th.control input[type='checkbox']").prop('checked')==true){
			$("td.control input[type='checkbox']").prop('checked',true);
			for(i=1;i<=nResults;i++){
				$("tr."+i).addClass("row_painted");
				$("tr."+i+" td[style]").addClass("row_painted");
				$("tr."+i+" i").addClass("icon-white");
			}
		}else{
			$("td.control input[type='checkbox']").removeAttr('checked');
			for(i=1;i<=nResults;i++){
				$("tr."+i).removeClass("row_painted");
				$("tr."+i+" td[style]").removeClass("row_painted");
				$("tr."+i+" td i").removeClass("icon-white");
			}
		}
	}
	
	function ordenar_tabla(field){
		if($("input[name='select_campo_orden']").val()==field){
			if(($("input[name='asc-desc']").val()=="desc")||($("input[name='asc-desc']").val()==""))
				$("input[name='asc-desc']").val("asc");
			else
				$("input[name='asc-desc']").val("desc");
		}else
			$("input[name='asc-desc']").val("asc");
		$("input[name='select_campo_orden']").val(field);
		cambiar_paginador($("#paginador").find(".actual").val(),$("#paginador").find(".actual").val());
	}
	
	function mod_row(number){
		number=number.replace(' row_painted','');
		objetos=$("tr."+number+" td");
		var valores=new Array(objetos.size()-1);
		i=0;
		first_flag=true;
		objetos.each(function(e) {
			if(first_flag==false){
				valores[i]=$(this).html();
				i++;
			}else
				first_flag=false;
		});
		i=0;
		objetos=$("tr[class='header'] th");
		first_flag=true;
		var titulos=new Array(objetos.size()-1);
		objetos.each(function(e) {
			if(first_flag==false){
				titulos[i]=$(this).attr('name');
				i++;
			}else
				first_flag=false;
		});
		showModal("Update", titulos, valores);
	}
	
	function showDetails(classNumber){
		objects=$("tr."+classNumber+" td");
		var values=new Array(objects.size()-1);
		i=0;
		firstFlag=firstFlagMode;
		objects.each(function(e) {
			if(firstFlag==false){
				values[i]=$(this).html();
				i++;
			}else
				firstFlag=false;
		});
		i=0;
		objects=$("tr[class='header'] th");
		firstFlag=firstFlagMode;
		var titles=new Array(objects.size()-1);
		var sizes=new Array(objects.size()-1);
		objects.each(function(e) {
			if(firstFlag==false){
				titles[i]=$(this).attr("name");
				sizes[i]=$(this).width();
				i++;
			}else
				firstFlag=false;
		});
		showModal("Details",titles,values);
	}
	
	function new_row(){
		objetos=$("tr[class='header'] th");
		var titulos=new Array(objetos.size()-1);
		i=0;
		first_flag=true;
		objetos.each(function(e) {
			if(first_flag==false){
				titulos[i]=$(this).attr('name');
				i++;
			}else
				first_flag=false;
		});
		showModal("New", titulos);
	}
	
	function ins_row(titulos){
		var objetos=$("#update_modal input[type='text']");
		var n_valores=new Array(objetos.size());
		i=0;
		objetos.each(function(e) {
			n_valores[i]=$(this).val();
			i++;
		});
		$.get("../resources/insert_row.php", {"campos[]":titulos, "valores[]":n_valores, "tabla":$("#tabla").val()}, function(e){
			if(!String.prototype.trim){
			  String.prototype.trim = function(){
				return this.replace(/^\s+|\s+$/g, ''); 
			  } 
			}
			if(e.trim().toLowerCase()=="true"){
				cambiar_paginador($(".actual").val(), $(".actual").val());
				$("#update_modal").modal('hide');
				$("#update_modal").html("");
			}else{
				$("#problem").html(e.replace(/^\s+|\s+$/g, ''));
			}
		});
	}
	
	function eliminar_rows(){
		objetos=$("input[type='checkbox']");
		var filas=new Array();
		i=0;
		objetos.each(function(e) {
			if($(this).prop("checked")==true){
				filas[i]=$(this).attr("class");
				i++;
			}
		});
		if(i!=0){
			i=0;
			objetos=$("tr[class='header'] th");
			var titulos=new Array(objetos.size()-1);
			first_flag=true;
			objetos.each(function(e) {
				if(first_flag==false){
					titulos[i]=$(this).attr('name');
					i++;
				}else
					first_flag=false;
			});
			if(confirm("Do you really want to delete this rows?")){
				eliminados=0;
				for(j=0;j<filas.length;j++){
					//alert(filas[j]);
					objetos=$("tr[class='"+filas[j]+" row_painted'] td");
					var valores=new Array(objetos.size()-1);
					i=0;
					first_flag=true;
					objetos.each(function(e) {
						if(first_flag==false){
							valores[i]=$(this).html();
							i++;
						}else
							first_flag=false;
					});
					$.ajax({type:"GET", url:"../resources/delete.php", data:{"tabla":$("#tabla").val(), "valores[]":valores, "campos[]":titulos}, success:function(e){
							if(!String.prototype.trim){
							  String.prototype.trim = function(){
								return this.replace(/^\s+|\s+$/g, ''); 
							  } 
							}
							if(e.trim().toLowerCase()!="true"){
								alert(e.replace(/(?:<[^>]+>)/gi, ''));
							}else{
								eliminados++;
							}
					}, async:false});
				}
				cambiar_paginador($(".actual").val(), $(".actual").val());
				alert(eliminados+" rows deleted successfully");
			}
		}else{ 
			alert("You have to select at least a row!");
		}
	}
	
	function showMultipleUpdate(){
		objects=$("td.control input[type='checkbox']");
		var rows=new Array();
		i=0;
		objects.each(function(e) {
			if($(this).prop("checked")==true){
				rows[i]=$(this).attr("class");
				rows[i]=rows[i].replace(' row_painted','');
				i++;
			}
		});
		if(i!=0){
			i=0;
			objects=$("tr[class='header'] th");
			var titles=new Array(objects.size()-1);
			firstFlag=firstFlagMode;
			objects.each(function(e) {
				if(firstFlag==false){
					titles[i]=$(this).attr("name");
					i++;
				}else
					firstFlag=false;
			});
			var allRows=new Array(rows.length);
			for(i=0;i<rows.length;i++){
				objects=$("tr."+rows[i]+" td");
				firstFlag=firstFlagMode;
				allRowsJ=new Array(objects.length-1);
				j=0;
				objects.each(function(e) {
					if(firstFlag==false){
						allRowsJ[j]=$(this).html();
						j++;
					}else
						firstFlag=false;
				});
				allRows[i]=allRowsJ;
			}
			var equal=Array();
			for(i=0;i<allRows.length;i++){
				for(j=0;j<allRows[0].length;j++){
					if(i!=0){
						if(equal[j]!=allRows[i][j])
							equal[j]="";
					}else{
						equal[j]=allRows[i][j];
					}
				}
			}
			showModal('Update Selected Rows',titles,equal);
		}else{
			alert("You have to select at least a row!");
		}
		
	}
	
	function showModal(){
		div='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3>'+arguments[0]+'</h3></div><div class="modal-body"><div id="problem"></div><table'+((arguments[0]=="Details")?' class="dt"':'')+'>\n';
		titles=arguments[1];
		if(arguments.length==3){
			values=arguments[2];
			for(i=0;i<values.length-1;i++){
				if(arguments[0]!="Details")
					content="<input type='text' value='"+values[i].replace(/'/g,"&#39;")+"' class='"+titles[i].replace(/'/g,"&#39;")+"'>";
				else
					content=values[i].replace(/'/g,"&#39;");
				div=div+"<tr><td><strong>"+titles[i]+":</strong></td><td>"+content+"</td></tr>\n";
			}
		}else{
			for(i=0;i<titles.length-1;i++)
				div=div+"<tr><td>"+titles[i]+"</td><td><input type='text' id='"+titles[i].replace(/'/g,"&#39;")+"'></td></tr>\n";
		}
		div=div+"</table></div><div class='modal-footer'>"+
			((arguments[0]!="Details")?"<input type='button' value='Save' class='updButton btn btn-success'>":"")+
			" <input type='button' value='Cancel' class='cancButton btn btn-warning'></div>";
		$("#update_modal").html(div);
		$("#update_modal").modal('show');
		if(arguments.length==3){
			if(arguments[0]=="Update"){
				$(".updButton").click(function(e){upd_row(values, titles)});
			}else if(arguments[0]!="Details")
				$(".updButton").click(function(e){updateSelectedRows(values, titles)});
		}else
			$(".updButton").click(function(e){ins_row(titles)});
		$(".cancButton").click(function(e){
			$("#update_modal").modal('hide'); 
			$("#update_modal").html("");
		});
	}
	
	function updateSelectedRows(values,titles){
		objects=$("td.control input[type='checkbox']");
		var rows=new Array();
		i=0;
		objects.each(function(e) {
			if($(this).prop("checked")==true){
				rows[i]=$(this).attr("class");
				i++;
			}
		});
		i=0;
		objects=$("#update_modal input[type='text']");
		newValues=new Array(objects.length);
		objects.each(function(e) {
			newValues[i]=$(this).val();
			i++;
		});
		next=false;
		j=0;
		for(i=0;i<newValues.length;i++){
			if(values[i]!=newValues[i]){
				next=true;
				j++;
			}
		}
		if(next){
			if(confirm("Do you really want to change these values?")){
				changes=new Array(j);
				j=0;
				for(i=0;i<newValues.length;i++){
					if(values[i]!=newValues[i]){
						changes[j]=i;
						j++;
					}
				}
				problems="";
				success=0;
				for(i=0;i<rows.length;i++){
					objects=$("tr[class='"+rows[i]+" row_painted'] td");
					var values=new Array(objects.size()-1);
					j=0;
					firstFlag=firstFlagMode;
					objects.each(function(e) {
						if(firstFlag==false){
							values[j]=$(this).html();
							j++;
						}else
							firstFlag=false;
					});
					newValues2=new Array(newValues.length);
					for(j=0;j<newValues.length;j++){newValues2[j]=values[j];}
					for(j=0;j<changes.length;j++){newValues2[changes[j]]=newValues[changes[j]];}
					$.ajax({type:"GET", url:"../resources/update.php", data:{"campos[]":titles, "valores[]":values, "tabla":$("#tabla").val(), "n_valores[]":newValues2}, success:function(e){
						if(!String.prototype.trim){
						  String.prototype.trim = function(){
							return replace(/^\s+|\s+$/g, ''); 
						  } 
						}
						if((e.trim().toLowerCase()!="nada")&&(e.trim().toLowerCase()!="true")){
							problems+="At row "+rows[i]+": "+e;
						}else	success++;
					}, async:false});
				}
				if(problems!=""){
					$("#problem").html(problems+"<div><div class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>×</button>"+success+" rows changed!</div>");
				}else{
					$("#update_modal").modal('hide');
					$("#update_modal").html("");
					cambiar_paginador($(".actual").val(), $(".actual").val());
				}
			}
		}else{
			$("#problem").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>You haven't changed anything!</div>");
		}
	}
	
	function upd_row(valores, titulos){
		var objetos=$("#update_modal input[type='text']");
		var n_valores=new Array(objetos.size()-1);
		i=0;
		objetos.each(function(e) {
			n_valores[i]=$(this).val();
			i++;
		});
		$.get("../resources/update.php", {"campos[]":titulos, "valores[]":valores, "tabla":$("#tabla").val(), "n_valores[]":n_valores}, function(e){
				if(!String.prototype.trim){
				  String.prototype.trim = function(){
					return this.replace(/^\s+|\s+$/g, ''); 
				  } 
				}
				if(e.trim().toLowerCase()=="true"){
					cambiar_paginador($(".actual").val(), $(".actual").val());
					$("#update_modal").modal('hide');
					$("#update_modal").html("");
				}else if(e.trim().toLowerCase()=="nada"){
					$("#problem").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>You haven't changed anything!</div>");
				}else{
					$("#problem").html(e);
				}
			});
	}
	
	function mostrar_search(){
		$("#search").fadeOut().html("");
		if($("#tabla").val()!='0'){
			$.get("../resources/seleccionar_campo_unico.php", {"tabla":$("#tabla").val()}, function(data){
				fields="<table style='width:100%'><tr><td><input type='text' id='search_value' name='search_value' placeholder='search'> "+data+" <input type='button' id='buscar' value='search' class='btn btn-info'></td><td class='right'><input type='button' value='new' id='new' class='btn btn-info'> <input type='button' id='updt_b' class='btn btn-info' value='Update'> <input type='button' value='delete' id='del' class='btn btn-warning'></td></tr></table>";
				$("#search").fadeIn('slow').html(fields);
				$("#search").find("#buscar").click(function(e){cambiar_paginador($(".actual").val(),$(".actual").val());});
				$("#del").click(eliminar_rows);
				$("#new").click(new_row);
				$("#updt_b").click(showMultipleUpdate);
			});
		}
	}
	
	function cambiar_paginador(actual, anterior){
		if((actual=="<<")||(actual==">>")){
			$.get("../resources/generar_tabla.php", {"tabla":$("#tabla").val(), "actual":actual, "anterior":anterior, "search":$("#search_value").val(), "search_field":$("#select_campo_unico").val(), "order_field":$("input[name='select_campo_orden']").val(), "sence":$("input[name='asc-desc']").val()}, function(data){
				showData(data);
			});
		}else{
			$.get("../resources/generar_tabla.php", {"tabla":$("#tabla").val(), "actual":actual, "search":$("#search_value").val(), "search_field":$("#select_campo_unico").val(), "order_field":$("input[name='select_campo_orden']").val(), "sence":$("input[name='asc-desc']").val()}, function(data){
				showData(data);
			});
		}
	}

//================================================================================================================
//				show_sql.php
//================================================================================================================

	function ejecutar_sql(){
		cover();
		$.get("../resources/execute_sql.php", {"archivo":$("#archivo").val()}, function(data){
			$(".result").html(data);
			$("#ocultar").slideUp();
			$(".result").fadeIn();
			$(".menu_final").fadeIn();
			cover();
		});
	}
	
	function ejecutar_sql_sin(){
		$.get("../resources/execute_sql.php", {"sql":$("#instrucciones").val()}, function(data){
			$(".result").html(data);
			$(".result").fadeIn();
		});
	}

//================================================================================================================
//				csv_tabla.php
//				excel_tabla.php
//================================================================================================================

	function alert_message(){
		if($("#eliminar_data").prop('checked')==true){
			if(confirm("If you select this option all the data in the table will be deleted")==false){
				$("#eliminar_data").removeAttr("checked");
			}
		}
	}
	
	function if_record_exists(){
		if($("select[name='if_exists']").val()!="Ignore"){
			$("#update_fields").slideDown();
			$("#pk_field_space").slideDown();
			$("#select_pk").hide();
			$("#set_pk_cb").prop('checked', 'true');
		}else{
			$("#update_fields").hide();
			$("#select_pk").hide();
		}
	}
	
	function set_pk(){
		if($("#set_pk_cb").prop('checked')==false){
			$("#select_pk").slideDown();
		}else{
			$("#select_pk").slideUp();
		}
	}

//================================================================================================================
//				csv_tabla.php
//================================================================================================================

	function insertar_data(){
		cover();
		$.get("../resources/insert.php", $("#form_csv_table").serialize(), function(data){
			$("#respuesta").html(data);	
			$("#match").slideUp();
			$("#respuesta").fadeIn();
			cover();
		});
	}

//================================================================================================================
//				excel_tabla.php
//================================================================================================================

	function insertar_data_excel(){
		cover();
		$.get("../resources/insert_excel.php", $("#form_excel_table").serialize(), function(data){
			$("#respuesta").html(data);	
			$("#match").slideUp();
			$("#respuesta").fadeIn();
			cover();
		});
	}

//================================================================================================================
//				insertar_csv.php
//================================================================================================================

	function validar_archivo(){
		extension = ($("#archivo").val().substring($("#archivo").val().lastIndexOf("."))).toLowerCase();
		if(extension=='.csv'){
			$("#subir_archivo").slideDown();
		}else{
			$("#subir_archivo").slideUp();
			alert("Select a valid CSV file");
			$("#archivo").val("");
		}
	}

//================================================================================================================
//				insertar_excel.php
//================================================================================================================

	function validar_archivo_excel(){
		extension = ($("#archivo_excel").val().substring($("#archivo_excel").val().lastIndexOf("."))).toLowerCase();
		if((extension=='.xls')||(extension=='.xlsx')){
			$("#subir_archivo").slideDown();
		}else{
			$("#subir_archivo").slideUp();
			alert("Select a valid Excel file");
			$("#archivo_excel").val("");
		}
	}

//================================================================================================================
//				insertar_sql.php
//================================================================================================================

	function validar_archivo_slq(){
		extension = ($("#archivo_sql").val().substring($("#archivo_sql").val().lastIndexOf("."))).toLowerCase();
		if(extension=='.sql'){
			$("#subir_archivo_sql").slideDown();
		}else{
			$("#subir_archivo_sql").slideUp();
			alert("Select a valid SQL file");
			$("#archivo_sql").val("");
		}
	}

//================================================================================================================
//				index.php
//================================================================================================================

	function login(){
		$.get("files/resources/login.php", $("#form_login").serialize(), function(data){
			if(!String.prototype.trim){
			  String.prototype.trim = function(){
				return this.replace(/^\s+|\s+$/g, ''); 
			  } 
			}
			if(data.trim().toLowerCase()=="true"){
				$("#login").fadeOut('fast');
				$.get("files/resources/keep_alive.php", $("#form_login").serialize(), function(e){document.location.href="files/main.php";});
			}else{
				alert("Error. The user or password is incorrect!");
			}
		});
	}

//================================================================================================================
//				exportar_info.php
//================================================================================================================

	function exportar_archivo(){
		if($("#tabla").val()=='0'){
			alert("Select a valid table");
		}else{
			flag=false;
			 $("#select_campos option:selected").each(function() {flag=true;});
			 if(flag){
				$("#form_exp_tab").submit();
			 }else{
				alert("You must select at least a Field");
			 }
		}
	}

//================================================================================================================
//				main.php
//================================================================================================================

	function m_insertar(){
		$("#m_insertar").slideToggle();
		$("#m_consult").slideUp();
		$("#m_option").slideUp();
		$("#m_exportar").slideUp();
		$("#m_backup").slideUp();
	}
	
	function m_consult(){
		$("#m_consult").slideToggle();
		$("#m_insertar").slideUp();
		$("#m_option").slideUp();
		$("#m_exportar").slideUp();
		$("#m_backup").slideUp();
	}
	
	function m_exportar(){
		$("#m_exportar").slideToggle();
		$("#m_consult").slideUp();
		$("#m_insertar").slideUp();
		$("#m_option").slideUp();
		$("#m_backup").slideUp();
	}
	
	function m_backup(){
		$("#m_exportar").slideUp();
		$("#m_consult").slideUp();
		$("#m_insertar").slideUp();
		$("#m_option").slideUp();
		$("#m_backup").slideToggle();
	}
	
	function m_option(){
		$("#m_exportar").slideUp();
		$("#m_consult").slideUp();
		$("#m_insertar").slideUp();
		$("#m_backup").slideUp();
		$("#m_option").slideToggle();
	}
	
	function cover(){
		$(".cover").height($(document).height());
		$(".cover").width($(document).width());
		$(".cover").fadeToggle('fast');
		$(".loader").fadeToggle('fast');
	}

//================================================================================================================
//				other_options.php
//================================================================================================================

	function guardar_configuracion(){
		$.post("../resources/guardar_configuraciones.php", $("#configuraciones").serialize(), function(data){alert('Configuration saved')});
	}


//================================================================================================================
//				password.php
//================================================================================================================

	function credenciales(){
		if($("#npass").val()==$("#n2pass").val()){
			$.post("../resources/login.php", {"usuario":$("#user").val(), "contra":$("#pass").val()}, function(e){
					if(!String.prototype.trim){
					  String.prototype.trim = function(){
						return this.replace(/^\s+|\s+$/g, ''); 
					  } 
					}
					if(e.trim().toLowerCase()=="true"){
						$.post("../resources/guardar_credenciales.php", {"user":$("#nuser").val(), "pass":$("#npass").val()}, function(data){
								$("#problem").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>New user and password saved "+data);
								alert("New user and password saved "+data);
								document.location.href="../../index.php";
							});
					}else{
						$("#problem").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Error. Your actual user or password is incorrect!</div>");
					}
				}); 
		}else{
			$("#problem").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>The password must be equal</div>");
			$("#npass").val("");
			$("#n2pass").val("");
		}
	}
