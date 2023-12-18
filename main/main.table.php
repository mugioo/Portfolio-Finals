<?php
require_once "../conn.php";

$recordsPerPage = isset($_POST['recordsPerPage']) ? (int)$_POST['recordsPerPage'] : 10;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 0;
$offset = ($page - 1) * $recordsPerPage;

$query = "SELECT *
FROM inventory
JOIN peripherals ON peripherals.inventory_id = inventory.inventory_id
JOIN employee ON inventory.employee_id = employee.employee_id
JOIN hardware ON hardware.inventory_id = inventory.inventory_id
JOIN software ON software.inventory_id = inventory.inventory_id
";

$query_table = "SELECT *
FROM inventory
JOIN employee ON inventory.employee_id = employee.employee_id
JOIN hardware ON hardware.inventory_id = inventory.inventory_id
JOIN department ON employee.dpt_id = department.dpt_id
";

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $query_table .= " WHERE hardware_type LIKE '%$searchTerm%'
        OR hardware_model LIKE '%$searchTerm%'
        OR hardware_processor LIKE '%$searchTerm%'
        OR hardware_ram LIKE '%$searchTerm%'
        OR hardware_storage LIKE '%$searchTerm%'
        OR os_name LIKE '%$searchTerm%'
        OR os_version LIKE '%$searchTerm%'
        OR os_status LIKE '%$searchTerm%'
        OR employee_name LIKE '%$searchTerm%'
        OR ip LIKE '%$searchTerm%'
        OR remarks LIKE '%$searchTerm%'
        OR dpt_name LIKE '%$searchTerm%'
        OR dpt_acronym LIKE '%$searchTerm%'
        ";
}

$countQuery = str_replace('*', 'COUNT(*) as total', $query_table);
$countStmt = sqlsrv_query($conn, $countQuery);

if ($countStmt === false) {
    $error = sqlsrv_errors();
    $errorMessage = isset($error[0]['message']) ? $error[0]['message'] : "Unknown error occurred.";
    echo "<tr><td colspan='5'>Error counting total records: $errorMessage</td></tr>";
} else {
    $totalResult = sqlsrv_fetch_array($countStmt);
    $totalRecords = $totalResult['total'];

    $totalPages = ceil($totalRecords / $recordsPerPage);

    if ($page > $totalPages) {
        $page = $totalPages;
        $offset = ($page - 1) * $recordsPerPage;
    }

    // $query_table .= " ORDER BY inventory.inventory_id OFFSET " . max($offset, 0) . " ROWS FETCH NEXT $recordsPerPage ROWS ONLY";
    if($offset >= 0){
        $query_table .= " ORDER BY inventory.inventory_id OFFSET $offset ROWS FETCH NEXT 
        $recordsPerPage ROWS ONLY";
    }
    $stmt = sqlsrv_query($conn, $query_table);
    
    date_default_timezone_set('Asia/Singapore');

    if ($stmt === false) {
        $error = sqlsrv_errors();
        $errorMessage = isset($error[0]['message']) ? $error[0]['message'] : "Unknown error occurred.";
        echo "<tr><td colspan='6'>Error executing query: $errorMessage</td></tr>";
    } else {
        if (sqlsrv_has_rows($stmt)) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo '<tr>';

                echo '<td><input type="checkbox" id="hd_delete_' . $row['inventory_id'] . '" name="hd_delete_id[]" value="' . $row['inventory_id'] . '" class="checkboxSize"></td>';
                echo '</form>';
                echo '<td>' . $row['dpt_acronym'] . '</td>';
                echo '<td>' . $row['employee_name'] . '</td>';

                echo '<td>';
                echo $row['hardware_type'] . ' - ' . $row['hardware_model'];
                echo '</td>';

                echo '<td>';
                echo $row['os_name'] . ' - ' . $row['os_version'] . ' - ' . $row['os_status'];
                echo '</td>';

                echo '<td class="text-center"><a href="#" data-bs-toggle="modal" data-bs-target="#' . $row['inventory_id'] . 'Modal"><span class="btn btn-outline-dark"><i class="bi bi-eye-fill"></i></span></a></td>';

                echo '</tr>';

                // Modal for each employee
                echo '<div class="modal fade" id="' . $row['inventory_id'] . 'Modal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">';
                echo '  <div class="modal-dialog modal-dialog-scrollable">';
                echo '    <div class="modal-content">';
                echo '      <div class="modal-header">';
                echo '        <h1 class="modal-title fs-5" id="viewModalLabel">Information</h1>';
                echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                echo '      </div>';
                echo '      <div class="modal-body">';
                echo '        <div class="container text-start">';
                ?>

                <div class="container">
                    <div class="row py-1">
                        <div class="col-6">Employee ID:</div>
                        <div class="col-6">
                            <?php echo $row['employee_id'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">Employee Name:</div>
                        <div class="col-6">
                            <?php echo $row['employee_name'] ?>
                        </div>
                    </div>
                
                    <hr>

                    <div class="row py-1">
                        <div class="col-6">Hardware ID:</div>
                        <div class="col-6">
                            <?php echo $row['hardware_id'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">Hardware Type:</div>
                        <div class="col-6">
                            <?php echo $row['hardware_type'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">Hardware Model:</div>
                        <div class="col-6">
                            <?php echo $row['hardware_model'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">Hardware Processor:</div>
                        <div class="col-6">
                            <?php echo $row['hardware_processor'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">Hardware RAM:</div>
                        <div class="col-6">
                            <?php echo $row['hardware_ram'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">Hardware Storage:</div>
                        <div class="col-6">
                            <?php echo $row['hardware_storage'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">OS Name:</div>
                        <div class="col-6">
                            <?php echo $row['os_name'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">OS Version:</div>
                        <div class="col-6">
                            <?php echo $row['os_version'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">OS Status:</div>
                        <div class="col-6">
                            <?php echo $row['os_status'] ?>
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">IP Address:</div>
                        <div class="col-6">
                            <?php echo $row['ip'] ?>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">Software Applications</div>
                    <div class="row py-1">
                        <div class="col-6 text-center border border-dark">Software Name</div>
                        <div class="col-6 text-center border border-dark">Software Version</div>
                    </div>
                    <?php 
                    $query4 = "SELECT * FROM software 
                    WHERE inventory_id = $row[inventory_id]";
                    $stmt4 = sqlsrv_query($conn, $query4);
                    while($row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC)){
                    ?>

                    <div class="row py-1">
                        <div class="col-6 text-center"><?php echo $row4['software_name']?></div>
                        <div class="col-6 text-center"><?php echo $row4['software_version']?></div>
                    </div>
                    
                    <?php } ?>

                    <hr>

                    <div class="text-center">Peripherals</div>
                    <div class="row py-1">
                        <div class="col-4 text-center border border-dark">Peripheral Name</div>
                        <div class="col-4 text-center border border-dark">Peripheral Type</div>
                        <div class="col-4 text-center border border-dark">Peripheral Model</div>
                    </div>
                    <?php 
                    $query4 = "SELECT * FROM peripherals 
                    WHERE inventory_id = $row[inventory_id]";
                    $stmt4 = sqlsrv_query($conn, $query4);
                    while($row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC)){
                    ?>
                    
                    <div class="row py-1">
                        <div class="col-4 text-center"><?php echo $row4['peripherals_name']?></div>
                        <div class="col-4 text-center"><?php echo $row4['peripherals_type']?></div>
                        <div class="col-4 text-center"><?php echo $row4['peripherals_model']?></div>
                    </div>
                    
                    <?php } ?>

                    <hr>

                    <div class="text-center">Remarks</div>
                    <div>
                        <p><?php echo $row['remarks'] ?></p>
                    </div>

                    <hr>
                    
                    <div class="row py-1">
                        <div class="col-6">Created By: </div>
                        <div class="col-6"><?php echo $row['created_by_admin_name']?></div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">Date Created: </div>
                        <div class="col-6"><?php echo $row['created_at']->format('Y-m-d H:i:s'); ?> GMT+8</div>
                    </div>
                    <div class="row py-1">
                        <div class="col-6">Modified By: </div>
                        <div class="col-6">
                            <?php
                            if ($row['modified_by_admin_name'] !== null) {
                                echo $row['modified_by_admin_name'];
                            } else {
                                echo "N/A";
                            }
                            ?></div>
                                        </div>
                                        <div class="row py-1">
                                            <div class="col-6">Date Modified: </div>
                                            <div class="col-6"><?php
                            if ($row['modified_at'] !== null) {
                                echo $row['modified_at']->format('Y-m-d H:i:s') . " GMT+8";
                            } else {
                                echo "N/A";
                            }
                            ?> 
                        </div>
                    </div>
                </div>
                <?php
                echo '        </div>';
                echo '      </div>';
                echo '      <div class="modal-footer">';
                echo '        <a class="link-underline link-underline-opacity-0" href="#" data-bs-toggle="modal" data-bs-target="#' . $row['inventory_id'] . 'editModal">';
                echo '          <input class="btn btn-primary" type="button" value="Edit">';
                echo '        </a>';
                echo '        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><span class="btn-label"><i class="bi bi-arrow-return-left"></i></span> Return</button>';
                echo '      </div>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';

                ?>
                <!----------EDIT MODAL----------->
                <div class="modal fade" id="<?php echo $row['inventory_id']?>editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editModalLabel">Edit Information</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                    <form action="../modify/edit.php" method="POST">

                        <div class="container">
                            <div class="row py-1">
                                <div class="col-4" hidden>Inventory ID:</div>
                                <div class="col-8" hidden><input type="text" class="form-control" value="<?php echo $row['inventory_id'] ?>" readonly name="inventory_id"></div>
                            </div>
                            <!---- EMPLOYEE ---->
                            <div class="row py-1">
                                <div class="col-4" hidden>Employee ID:</div>
                                <div class="col-8" hidden><input type="text" class="form-control" value="<?php echo $row['employee_id'] ?>" readonly name="employee_id"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">Employee Name:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['employee_name'] ?>" readonly name="employee_name"></div>
                            </div>

                            <hr>

                            <!---- HARDWARE ---->
                            <div class="row py-1">
                                <div class="col-4" hidden>Hardware ID:</div>
                                <div class="col-8" hidden><input type="text" class="form-control" value="<?php echo $row['hardware_id'] ?>" readonly name="hardware_id"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">Hardware Type:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['hardware_type'] ?>" required name="hardware_type"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">Hardware Model:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['hardware_model'] ?>" required name="hardware_model"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">Hardware Processor:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['hardware_processor'] ?>" required name="hardware_processor"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">Hardware RAM:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['hardware_ram'] ?>" required name="hardware_ram"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">Hardware Storage:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['hardware_storage'] ?>" required name="hardware_storage"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">OS Name:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['os_name'] ?>" required name="os_name"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">OS Version:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['os_version'] ?>" required name="os_version"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">OS Status:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['os_status'] ?>" required name="os_status"></div>
                            </div>
                            <div class="row py-1">
                                <div class="col-4">IP Address:</div>
                                <div class="col-8"><input type="text" class="form-control" value="<?php echo $row['ip'] ?>" required name="ip_address"></div>
                            </div>

                            <!---- SOFTWARE ---->
                            <hr>

                            <div class="row">
                                <div class="col">Software Applications:</div>
                                <div class="" hidden>Software ID:</div>
                                <div class="col text-center">Name</div>
                                <div class="col text-center">Version</div>
                            </div>
                            <?php 
                            $query3 = "SELECT * FROM software 
                            WHERE inventory_id = $row[inventory_id]";
                            $stmt3 = sqlsrv_query($conn, $query3);
                            while($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)){
                                ?>
                                <div class="row py-1">
                                    <div class="col-4"></div>
                                <div class="col-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?php echo $row3['software_id'] ?>" readonly name="software_id[]" hidden>
                                    <input type="text" class="form-control" value="<?php echo $row3['software_name'] ?>" required name="software_name[]">
                                    <input type="text" class="form-control" value="<?php echo $row3['software_version'] ?>" required name="software_version[]">
                                    <br>
                                </div>
                                </div>
                                </div>
                            <?php } ?>

                            <hr>

                            <!---- PERIPHERALS ---->
                            <div class="row py-1">
                                
                                <div class="col-8" hidden><input type="text" class="form-control" value="<?php echo $row['peripherals_id'] ?>" readonly name="peripherals_id"></div>
                            </div>
                            <div class="row">
                                <div class="col">Peripherals:</div>
                                <div class="" hidden>Peripherals ID:</div>
                                <div class="col text-center">Name</div>
                                <div class="col text-center">Type</div>
                                <div class="col text-center">Model</div>
                            </div>
                            <?php 
                            $query2 = "SELECT * FROM peripherals 
                            WHERE inventory_id = $row[inventory_id]";
                            $stmt2 = sqlsrv_query($conn, $query2);
                            while($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)){
                                ?>
                                <div class="row py-1">
                                    <div class="col-3"></div>
                                <div class="col-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?php echo $row2['peripherals_id'] ?>" readonly name="peripherals_id[]" hidden>
                                    <input type="text" class="form-control" value="<?php echo $row2['peripherals_name'] ?>" required name="peripherals_name[]">
                                    <input type="text" class="form-control" value="<?php echo $row2['peripherals_type'] ?>" required name="peripherals_type[]">
                                    <input type="text" class="form-control" value="<?php echo $row2['peripherals_model'] ?>" required name="peripherals_model[]">
                                    <br>
                                </div>
                                </div>
                                </div>
                            <?php } ?>

                            <!-- Remarks -->
                            <div class="text-center">Remarks:</div>
                            <textarea name="remarkss" cols="90" rows="5"><?php echo $row['remarks'] ?></textarea>
                                
                        </div>

                    </div>
                    <div class="modal-footer">
                    <input class="btn btn-success" type="submit" value="Confirm Edit">
                    </form>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#<?php echo $row['inventory_id']?>Modal"><span class="btn-label"><i class="bi bi-arrow-return-left"></i></span> Return</button>
                    </div>
                    </div>
                </div>
                </div>
                <?php
            }
        } else {
            echo "<tr><td colspan='6'>There's nothing here.</td></tr>";
        }
        sqlsrv_free_stmt($stmt);
    }

    echo "<script>var totalRecords = $totalRecords; var currentPage = $page; var totalPages = $totalPages;</script>";
}
sqlsrv_close($conn);
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    var sendbtn = document.getElementById('hd_delete_multi_btn');

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var checkedCount = document.querySelectorAll('input[name="hd_delete_id[]"]:checked').length;
            sendbtn.disabled = checkedCount === 0;
        });
    });

    function confirmDelete() {
        return confirm("Are you sure you want to delete the selected items?\nThis action cannot be undone.");
    }

    $("#checkAl").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>