<?php
include "../conn.php";

if (isset($_POST["hd_delete_id"])) {
    $delete_ids = $_POST["hd_delete_id"];
    
    foreach ($delete_ids as $inventory_id) {
        
        $sql_delete_software = "DELETE FROM software WHERE inventory_id = ?";
        $params_delete_software = array($inventory_id);
        $stmt_delete_software = sqlsrv_query($conn, $sql_delete_software, $params_delete_software);

        if ($stmt_delete_software === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        
        $sql_delete_inventory = "DELETE FROM inventory WHERE inventory_id = ?";
        $params_delete_inventory = array($inventory_id);
        $stmt_delete_inventory = sqlsrv_query($conn, $sql_delete_inventory, $params_delete_inventory);

        if ($stmt_delete_inventory === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }
    
    
    $response = array("status" => "success", "message" => "Items deleted successfully");
    echo json_encode($response);
    header("Location: ../main/main.view.php");
    exit();
} else {
    
    $response = array("status" => "error", "message" => "No inventory IDs provided.");
    echo json_encode($response);
}
?>
