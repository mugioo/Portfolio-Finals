<?php
include "../conn.php";

//------------SESSIONS-------------]
session_start();
if($_SESSION['admin_name'] == null){
    header("Location:../newlogin/index.php");
  }
$admin_name = $_SESSION['admin_name'];
//---------------------------------]

$query = "SELECT * FROM inventory
JOIN peripherals ON peripherals.inventory_id = inventory.inventory_id
JOIN employee ON inventory.employee_id = employee.employee_id
JOIN hardware ON hardware.inventory_id = inventory.inventory_id
JOIN software ON software.inventory_id = inventory.inventory_id ";

$stmt = sqlsrv_query($conn, $query);

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

$inventory_id        = $_POST['inventory_id'];

$employee_id        = $_POST['employee_id'];
$employee_name      = $_POST['employee_name'];

$hardware_id        = $_POST['hardware_id'];
$hardware_type      = $_POST['hardware_type'];
$hardware_model     = $_POST['hardware_model'];
$hardware_processor = $_POST['hardware_processor'];
$hardware_ram       = $_POST['hardware_ram'];
$hardware_storage   = $_POST['hardware_storage'];
$os_name            = $_POST['os_name'];
$os_version         = $_POST['os_version'];
$os_status          = $_POST['os_status'];
$ip                 = $_POST['ip_address'];

$selected_software_id = isset($_POST['software_id']) ? $_POST['software_id'] : array();
$selected_software_name = isset($_POST['software_name']) ? $_POST['software_name'] : array();
$selected_software_version = isset($_POST['software_version']) ? $_POST['software_version'] : array();
// $software_id        = $_POST['software_id'];
// $software_name      = $_POST['software_name'];
// $software_version   = $_POST['software_version'];
// $software_name      = $_POST['software_name_' . $row['software_id']];
// $software_version   = $_POST['software_version_' . $row['software_id']];

$selected_peripherals_id = isset($_POST['peripherals_id']) ? $_POST['peripherals_id'] : array();$selected_peripherals_name = isset($_POST['peripherals_name']) ? $_POST['peripherals_name'] : array();
$selected_peripherals_type = isset($_POST['peripherals_type']) ? $_POST['peripherals_type'] : array();
$selected_peripherals_model = isset($_POST['peripherals_model']) ? $_POST['peripherals_model'] : array();
// $peripherals_id     = $_POST['peripherals_id'];
// $peripherals_name   = $_POST['peripherals_name'];
// $peripherals_type   = $_POST['peripherals_type'];
// $peripherals_model  = $_POST['peripherals_model'];
// $peripherals_name   = $_POST['peripherals_name_' . $row['peripherals_id']];
// $peripherals_type   = $_POST['peripherals_type_' . $row['peripherals_id']];
// $peripherals_model  = $_POST['peripherals_model_' . $row['peripherals_id']];

$remarks = $_POST['remarkss'];
}

$update_remarks = "UPDATE inventory
                   SET remarks = '$remarks'
                   WHERE inventory_id = '$inventory_id' ";

$up_rem = sqlsrv_query($conn, $update_remarks);
if($up_rem === false){
  throw new Exception("Error updating remarks: " . print_r(sqlsrv_errors(), true));
}

$query = "UPDATE inventory
          SET modified_by_admin_name = ?, modified_at = GETDATE()
          WHERE inventory_id = ?";
$params = array($admin_name, $inventory_id);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    throw new Exception("Error updating inventory data: " . print_r(sqlsrv_errors(), true));
}

$update_employee = "UPDATE employee SET
                    employee_name = '$employee_name'
                    WHERE employee_id = '$employee_id' ";

$up_emp = sqlsrv_query($conn, $update_employee);

if($up_emp === false){
  throw new Exception("Error updating employee: " . print_r(sqlsrv_errors(), true));
}


$update_hardware = "UPDATE hardware SET 
                    hardware_type = '$hardware_type',
                    hardware_model = '$hardware_model',
                    hardware_processor = '$hardware_processor',
                    hardware_ram = '$hardware_ram',
                    hardware_storage = '$hardware_storage',
                    os_name = '$os_name',
                    os_version = '$os_version',
                    os_status = '$os_status',
                    ip = '$ip'
                    WHERE hardware_id = '$hardware_id' ";

$up_hard = sqlsrv_query($conn, $update_hardware);

if($up_hard === false){
  throw new Exception("Error updating hardware: " . print_r(sqlsrv_errors(), true));
}

try {
  foreach($selected_software_id as $sf_key => $software_id){
    $software_name = $selected_software_name[$sf_key];
    $software_version = $selected_software_version[$sf_key];

    $update_software = "UPDATE software SET
                        software_name = '$software_name',
                        software_version = '$software_version'
                        WHERE software_id = '$software_id' ";

    $up_soft = sqlsrv_query($conn, $update_software);

    if($up_soft === false){
    throw new Exception("Error updating software: " . print_r(sqlsrv_errors(), true));
    }
  }
  echo "Software records updated successfully!";
} catch (Exception $e) {
  echo "An error occurred: " . $e->getMessage();
}

try{
  foreach($selected_peripherals_id as $ph_key => $peripherals_id){
    $peripherals_name = $selected_peripherals_name[$ph_key];
    $peripherals_type = $selected_peripherals_type[$ph_key];
    $peripherals_model = $selected_peripherals_model[$ph_key];

    $update_peripherals = "UPDATE peripherals SET
                          peripherals_name = '$peripherals_name',
                          peripherals_type = '$peripherals_type',
                          peripherals_model = '$peripherals_model'
                          WHERE peripherals_id = '$peripherals_id' ";

    $up_peri = sqlsrv_query($conn, $update_peripherals);
    if($up_peri === false){
      throw new Exception("Error updating peripherals: " . print_r(sqlsrv_errors(), true));
    }
  }
echo "Software records updated successfully!";
} catch (Exception $e) {
  echo "An error occurred: " . $e->getMessage();
}

header("Location: ../main/main.view.php");
?>