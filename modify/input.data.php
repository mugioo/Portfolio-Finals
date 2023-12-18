<?php

require_once "../conn.php";
session_start();

$admin_name = $_SESSION['admin_name'];

$hardwareModel = isset($_POST['hardwareModel']) ? $_POST['hardwareModel'] : '';
$hardwareType = isset($_POST['hardwareType']) ? $_POST['hardwareType'] : '';
$hardwareProcessor = isset($_POST['hardwareProcessor']) ? $_POST['hardwareProcessor'] : '';
$hardwareRAM = isset($_POST['hardwareRAM']) ? $_POST['hardwareRAM'] : '';
$hardwareStorage = isset($_POST['hardwareStorage']) ? $_POST['hardwareStorage'] : '';
$assignedToId = isset($_POST['assignedToId']) ? $_POST['assignedToId'] : '';
$ipAddress = isset($_POST['ipAddress']) ? $_POST['ipAddress'] : '';
$osName = isset($_POST['osName']) ? $_POST['osName'] : '';
$osVersion = isset($_POST['osVersion']) ? $_POST['osVersion'] : '';
$osStatus = isset($_POST['osStatus']) ? $_POST['osStatus'] : '';

$selectedSoftwareNames = isset($_POST['selectedSoftwareName']) ? $_POST['selectedSoftwareName'] : array();
$selectedSoftwareVersions = isset($_POST['selectedSoftwareVersion']) ? $_POST['selectedSoftwareVersion'] : array();
$peripheralsTypes = isset($_POST['peripheralsType']) ? $_POST['peripheralsType'] : array();
$peripheralsModels = isset($_POST['peripheralsModel']) ? $_POST['peripheralsModel'] : array();
$peripheralsNames = isset($_POST['peripheralsName']) ? $_POST['peripheralsName'] : array();

$remarks = isset($_POST['remarkss']) ? $_POST['remarkss'] : '';
try {
    sqlsrv_begin_transaction($conn);
    $query = "INSERT INTO inventory (employee_id, created_at, created_by_admin_name, remarks)
              OUTPUT INSERTED.inventory_id
              VALUES (?, GETDATE(), ?, ?)";
    $params = array($assignedToId, $admin_name, $remarks);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        throw new Exception("Error inserting inventory data: " . print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $inventoryId = $row['inventory_id'];

    $query = "INSERT INTO hardware (hardware_model, hardware_type, hardware_processor, hardware_ram, hardware_storage, ip, inventory_id, os_name, os_version, os_status)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($hardwareModel, $hardwareType, $hardwareProcessor, $hardwareRAM, $hardwareStorage, $ipAddress, $inventoryId, $osName, $osVersion, $osStatus);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        throw new Exception("Error inserting hardware: " . print_r(sqlsrv_errors(), true));
    }

    foreach ($selectedSoftwareNames as $key => $softwareName) {
        $softwareVersion = $selectedSoftwareVersions[$key];

        $query = "INSERT INTO software (software_name, software_version, inventory_id)
                  VALUES (?, ?, ?)";
        $params = array($softwareName, $softwareVersion, $inventoryId);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            throw new Exception("Error inserting software: " . print_r(sqlsrv_errors(), true));
        }
    }

    foreach ($peripheralsNames as $key => $peripheralsNameItem) {
        $peripheralsTypeItem = $peripheralsTypes[$key];
        $peripheralsModelItem = $peripheralsModels[$key];

        $query = "INSERT INTO peripherals (peripherals_name, peripherals_type, peripherals_model, inventory_id)
                  VALUES (?, ?, ?, ?)";
        $params = array($peripheralsNameItem, $peripheralsTypeItem, $peripheralsModelItem, $inventoryId);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            throw new Exception("Error inserting peripherals data: " . print_r(sqlsrv_errors(), true));
        }
    }

    sqlsrv_commit($conn);

    echo "Data inserted successfully!";
    header("Location: ../main/main.view.php");
} catch (Exception $e) {
    sqlsrv_rollback($conn);

    echo "Error: " . $e->getMessage();
    error_log($e->getMessage()); 
}
?>