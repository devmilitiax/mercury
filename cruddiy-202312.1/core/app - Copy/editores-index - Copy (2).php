<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>newsletters3</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6b773fe9e4.js" crossorigin="anonymous"></script>
    <style type="text/css">
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 5px;
        }
        body {
            font-size: 14px;
        }
    </style>
</head>
<?php require_once('config.php'); ?>
<?php require_once('config-tables-columns.php'); ?>
<?php require_once('helpers.php'); ?>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <?php
                        // Prevent crash if $str contains single quotes
                        $str = <<<'EOD'
                        editores
                        EOD;
                        ?>
                        <h2 class="float-left"><?php translate('%s Details', true, $str) ?></h2>
                        <a href="editores-create.php" class="btn btn-success float-right"><?php translate('Add New Record') ?></a>
                        <a href="editores-index.php" class="btn btn-info float-right mr-2"><?php translate('Reset View') ?></a>
                        <a href="javascript:history.back()" class="btn btn-secondary float-right mr-2"><?php translate('Back') ?></a>
                    </div>

                    <div class="form-row">
                        <form action="editores-index.php" method="get">
                        <div class="col">
                          <input type="text" class="form-control" placeholder="<?php translate('Search this table') ?>" name="search">
                        </div>
                    </div>
                        </form>
                    <br>

                    <?php
                    //Get current URL and parameters for correct pagination
                    $script   = $_SERVER['SCRIPT_NAME'];
                    $parameters   = $_GET ? $_SERVER['QUERY_STRING'] : "" ;
                    $currenturl = $domain. $script . '?' . $parameters;

                    //Pagination
                    if (isset($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }

                    //$no_of_records_per_page is set on the index page. Default is 10.
                    $offset = ($pageno-1) * $no_of_records_per_page;

                    $total_pages_sql = "SELECT COUNT(*) FROM `editores`";
                    $result = mysqli_query($link,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);

                    //Column sorting on column name
                    $columns = array('id', 'id_grapesjs', 'nombre', 'filtro', 'indice', 'email', 'asunto', 'fecha');
                    // Order by primary key on default
                    $order = 'id';
                    if (isset($_GET['order']) && in_array($_GET['order'], $columns)) {
                        $order = $_GET['order'];
                    }

                    //Column sort order
                    $sortBy = array('asc', 'desc'); $sort = 'asc';
                    if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {
                          if($_GET['sort']=='asc') {
                            $sort='asc';
                            }
                    else {
                        $sort='desc';
                        }
                    }

                    //Generate WHERE statements for param
                    $where_columns = array_intersect_key($_GET, array_flip($columns));
                    $get_param = "";
                    $where_statement = " WHERE 1=1 ";
                    foreach ( $where_columns as $key => $val ) {
                        $where_statement .= " AND `$key` = '" . mysqli_real_escape_string($link, $val) . "' ";
                        $get_param .= "&$key=$val";
                    }

                    if (!empty($_GET['search'])) {
                        $search = mysqli_real_escape_string($link, $_GET['search']);
                        if (strpos('`editores`.`id`, `editores`.`id_grapesjs`, `editores`.`nombre`, `editores`.`filtro`, `editores`.`indice`, `editores`.`email`, `editores`.`asunto`, `editores`.`fecha`', ',')) {
                            $where_statement .= " AND CONCAT_WS (`editores`.`id`, `editores`.`id_grapesjs`, `editores`.`nombre`, `editores`.`filtro`, `editores`.`indice`, `editores`.`email`, `editores`.`asunto`, `editores`.`fecha`) LIKE '%$search%'";
                        } else {
                            $where_statement .= " AND `editores`.`id`, `editores`.`id_grapesjs`, `editores`.`nombre`, `editores`.`filtro`, `editores`.`indice`, `editores`.`email`, `editores`.`asunto`, `editores`.`fecha` LIKE '%$search%'";
                        }

                    } else {
                        $search = "";
                    }

                    $order_clause = !empty($order) ? "ORDER BY `$order` $sort" : '';
                    $group_clause = !empty($order) && $order == 'id' ? "GROUP BY `editores`.`$order`" : '';

                    // Prepare SQL queries
                    $sql = "SELECT `editores`.* 
                            FROM `editores` 
                            $where_statement
                            $group_clause
                            $order_clause
                            LIMIT $offset, $no_of_records_per_page;";
                    $count_pages = "SELECT COUNT(*) AS count FROM `editores` 
                            $where_statement";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            $number_of_results = mysqli_fetch_assoc(mysqli_query($link, $count_pages))['count'];
                            $total_pages = ceil($number_of_results / $no_of_records_per_page);
                            translate('total_results', true, $number_of_results, $pageno, $total_pages);
                            ?>

                            <table class='table table-bordered table-striped'>
                                <thead class='thead-light'>
                                    <tr>
                                        <?php 									$columnname = "id";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "asc" ? "desc" : "asc";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "desc" ? "asc" : $sort_link;
									echo "<th><a href=?search=$search&order=id&sort=".$sort_link.">id</a></th>";
									$columnname = "id_grapesjs";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "asc" ? "desc" : "asc";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "desc" ? "asc" : $sort_link;
									echo "<th><a href=?search=$search&order=id_grapesjs&sort=".$sort_link.">id_grapesjs</a></th>";
									$columnname = "nombre";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "asc" ? "desc" : "asc";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "desc" ? "asc" : $sort_link;
									echo "<th><a href=?search=$search&order=nombre&sort=".$sort_link.">nombre</a></th>";
									$columnname = "filtro";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "asc" ? "desc" : "asc";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "desc" ? "asc" : $sort_link;
									echo "<th><a href=?search=$search&order=filtro&sort=".$sort_link.">filtro</a></th>";
									$columnname = "indice";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "asc" ? "desc" : "asc";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "desc" ? "asc" : $sort_link;
									echo "<th><a href=?search=$search&order=indice&sort=".$sort_link.">indice</a></th>";
									$columnname = "email";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "asc" ? "desc" : "asc";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "desc" ? "asc" : $sort_link;
									echo "<th><a href=?search=$search&order=email&sort=".$sort_link.">email</a></th>";
									$columnname = "asunto";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "asc" ? "desc" : "asc";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "desc" ? "asc" : $sort_link;
									echo "<th><a href=?search=$search&order=asunto&sort=".$sort_link.">asunto</a></th>";
									$columnname = "fecha";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "asc" ? "desc" : "asc";
									$sort_link = isset($_GET["order"]) && $_GET["order"] == $columnname && $_GET["sort"] == "desc" ? "asc" : $sort_link;
									echo "<th><a href=?search=$search&order=fecha&sort=".$sort_link.">fecha</a></th>";
 ?>
                                        <th><?php translate('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_array($result)): ?>
                                        <tr>
                                            <?php echo "<td>" . htmlspecialchars($row['id'] ?? "") . "</td>";
																					echo "<td>";
											// Check if the column is file upload
											// echo '<pre>';
											// print_r($tables_and_columns_names['editores']["columns"]['id_grapesjs']);
											// echo '</pre>';
											$has_link_file = isset($tables_and_columns_names['editores']["columns"]['id_grapesjs']['is_file']) ? true : false;
											if ($has_link_file){
											    $is_file = $tables_and_columns_names['editores']["columns"]['id_grapesjs']['is_file'];
											    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['id_grapesjs']) .'" target="_blank" class="uploaded_file" id="link_id_grapesjs">' : '';
											    echo $link_file;
											}
											echo nl2br(htmlspecialchars($row['id_grapesjs'] ?? ""));
											if ($has_link_file){
											    echo $is_file ? "</a>" : "";
											}
											echo "</td>"."\n\t\t\t\t\t\t\t\t\t\t\t\t";											echo "<td>";
											// Check if the column is file upload
											// echo '<pre>';
											// print_r($tables_and_columns_names['editores']["columns"]['nombre']);
											// echo '</pre>';
											/* 
											
$v = generateRandomString();
$action = "../editor_2023/editor.php?id_editor={col_name}&v=$v";//{another example of adding url {} text will be replaced by the primary key value											
											
											*/																					
											
											$has_link_file = isset($tables_and_columns_names['editores']["columns"]['nombre']['is_file']) ? true : false;
											if ($has_link_file){
											    $is_file = $tables_and_columns_names['editores']["columns"]['nombre']['is_file'];
												$v777 = generateRandomString();
											    $link_file = $is_file ? '<a href="../../../editor_2023/editor.php?id_editor='. htmlspecialchars($row['id_grapesjs']) .'&v='.$v777.'" target="_blank" class="uploaded_file" id="link_nombre">' : '';
											    echo $link_file;
											}
											echo nl2br(htmlspecialchars($row['nombre'] ?? ""));
											if ($has_link_file){
											    echo $is_file ? "</a>" : "";
											}
											echo "</td>"."\n\t\t\t\t\t\t\t\t\t\t\t\t";											echo "<td>";
											// Check if the column is file upload
											// echo '<pre>';
											// print_r($tables_and_columns_names['editores']["columns"]['filtro']);
											// echo '</pre>';
											$has_link_file = isset($tables_and_columns_names['editores']["columns"]['filtro']['is_file']) ? true : false;
											if ($has_link_file){
											    $is_file = $tables_and_columns_names['editores']["columns"]['filtro']['is_file'];
											    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['filtro']) .'" target="_blank" class="uploaded_file" id="link_filtro">' : '';
											    echo $link_file;
											}
											echo nl2br(htmlspecialchars($row['filtro'] ?? ""));
											if ($has_link_file){
											    echo $is_file ? "</a>" : "";
											}
											echo "</td>"."\n\t\t\t\t\t\t\t\t\t\t\t\t";											echo "<td>";
											// Check if the column is file upload
											// echo '<pre>';
											// print_r($tables_and_columns_names['editores']["columns"]['indice']);
											// echo '</pre>';
											$has_link_file = isset($tables_and_columns_names['editores']["columns"]['indice']['is_file']) ? true : false;
											if ($has_link_file){
											    $is_file = $tables_and_columns_names['editores']["columns"]['indice']['is_file'];
											    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['indice']) .'" target="_blank" class="uploaded_file" id="link_indice">' : '';
											    echo $link_file;
											}
											echo nl2br(htmlspecialchars($row['indice'] ?? ""));
											if ($has_link_file){
											    echo $is_file ? "</a>" : "";
											}
											echo "</td>"."\n\t\t\t\t\t\t\t\t\t\t\t\t";											echo "<td>";
											// Check if the column is file upload
											// echo '<pre>';
											// print_r($tables_and_columns_names['editores']["columns"]['email']);
											// echo '</pre>';
											$has_link_file = isset($tables_and_columns_names['editores']["columns"]['email']['is_file']) ? true : false;
											if ($has_link_file){
											    $is_file = $tables_and_columns_names['editores']["columns"]['email']['is_file'];
											    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['email']) .'" target="_blank" class="uploaded_file" id="link_email">' : '';
											    echo $link_file;
											}
											echo nl2br(htmlspecialchars($row['email'] ?? ""));
											if ($has_link_file){
											    echo $is_file ? "</a>" : "";
											}
											echo "</td>"."\n\t\t\t\t\t\t\t\t\t\t\t\t";											echo "<td>";
											// Check if the column is file upload
											// echo '<pre>';
											// print_r($tables_and_columns_names['editores']["columns"]['asunto']);
											// echo '</pre>';
											$has_link_file = isset($tables_and_columns_names['editores']["columns"]['asunto']['is_file']) ? true : false;
											if ($has_link_file){
											    $is_file = $tables_and_columns_names['editores']["columns"]['asunto']['is_file'];
											    $link_file = $is_file ? '<a href="uploads/'. htmlspecialchars($row['asunto']) .'" target="_blank" class="uploaded_file" id="link_asunto">' : '';
											    echo $link_file;
											}
											echo nl2br(htmlspecialchars($row['asunto'] ?? ""));
											if ($has_link_file){
											    echo $is_file ? "</a>" : "";
											}
											echo "</td>"."\n\t\t\t\t\t\t\t\t\t\t\t\t";echo "<td>" . htmlspecialchars($row['fecha'] ?? "") . "</td>";
										 ?>
                                            <td>
                                                <?php
                                                $column_id = 'id';
                                                if (!empty($column_id)): ?>
                                                    <a id='read-<?php echo $row['id']; ?>' href='editores-read.php?id=<?php echo $row['id']; ?>' title='<?php echo addslashes(translate('View Record', false)); ?>' data-toggle='tooltip' class='btn btn-sm btn-info'><i class='far fa-eye'></i></a>
                                                    <a id='update-<?php echo $row['id']; ?>' href='editores-update.php?id=<?php echo $row['id']; ?>' title='<?php echo addslashes(translate('Update Record', false)); ?>' data-toggle='tooltip' class='btn btn-sm btn-warning'><i class='far fa-edit'></i></a>
                                                    <a id='delete-<?php echo $row['id']; ?>' href='editores-delete.php?id=<?php echo $row['id']; ?>' title='<?php echo addslashes(translate('Delete Record', false)); ?>' data-toggle='tooltip' class='btn btn-sm btn-danger'><i class='far fa-trash-alt'></i></a>
                                                <?php else: ?>
                                                    <?php echo addslashes(translate('unsupported_no_pk')); ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>




                                <ul class="pagination" align-right>
                                <?php
                                    $new_url = preg_replace('/&?pageno=[^&]*/', '', $currenturl);
                                 ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo $new_url .'&pageno=1' ?>"><?php translate('First') ?></a></li>
                                    <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                                        <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo $new_url ."&pageno=".($pageno - 1); } ?>"><?php translate('Prev') ?></a>
                                    </li>
                                    <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                                        <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo $new_url . "&pageno=".($pageno + 1); } ?>"><?php translate('Next') ?></a>
                                    </li>
                                    <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                                        <a class="page-item"><a class="page-link" href="<?php echo $new_url .'&pageno=' . $total_pages; ?>"><?php translate('Last') ?></a>
                                    </li>
                                </ul>
<?php
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>" . translate('No records were found.') . "</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

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
