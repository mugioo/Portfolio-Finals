<?php
require_once "../conn.php";

//------------SESSIONS-------------]
session_start();

if($_SESSION['admin_name'] == null){
    header("Location:../index.php");
}
$admin_name = $_SESSION['admin_name'];
//---------------------------------]
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Manager</title>
    <link rel="icon" href="../images/favicon-32x32.png">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../images/xf.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../images/nonbullet.css">
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../images/bg.css">
</head>

<!-- NAV BAR -->
<nav class="navbar navbar-expand-lg">
    <div class="navbar-glass">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <input type="text" id="search" class="ms-3 form-control" placeholder="Search">
      </ul>
      <div class="d-flex align-items-center">
      <li class="hello">Hello<span class="btn-label"><i class="bi bi-lightning-charge-fill"></i></span> <br> <?php echo $admin_name ?></li>
      </div>
    </div>
  </div>
</div>
</nav>

<!-- END OF NAV BAR -->

<!-- SIDE NAV BAR -->
<div class="sidenav nav nav-pills flex-column mb-auto">
    <h2 class="h2style">Inventory Manager</h2>
    <hr>
    <a href="" data-bs-toggle="modal" data-bs-target="#addinvModal" class="waves-effect waves-light minimal-link">
        <span class="icon"><i class="bi bi-plus-lg"></i></span>
        <span class="label">Add Inventory</span>
    <span class="ripple"></span>
    </a>

    <div class="logoutdiv fixed-bottom pb-4">
        <hr>
        <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal" class="waves-effect waves-light minimal-link">
            <span class="icon"><i class="bi bi-box-arrow-in-left"></i></span>
            <span class="label">Logout</span>
        </a>
        </hr>
    </div>
</div>

<!-- END OF SIDE NAV BAR -->

<body>
    <div class="main">
        <form action="../modify/delete.php" method="POST">
            
        <table class="table">
            <thead class="border-dark">
                <tr>
                    <th>
                    <div class="btn-group">
                        <button type="submit" name="hd_delete_multi_btn" id="hd_delete_multi_btn" class="btn btn-danger" onclick="return confirmDelete()" disabled="disabled">
                            <span class="btn-label"><i class="bi bi-trash-fill"></i></span>
                        </button>
                        <input type="checkbox" class="btn-check" id="checkAl" autocomplete="off">
                        <label class="btn btn-outline-danger" for="checkAl">
                            <span class="btn-label"><i class="bi bi-check-lg"></i></span>
                        </label>
                    </div>    
                    </th>
                    <th>Dept.</th>
                    <th>Name</th>    
                    <th>Hardware</th>
                    <th>Software</th>
                    <th class="text-center">View</th>
                </tr>
            </thead>
            <tbody id="table-data">
                <!--- DATA COMES FROM main.table.php :D --->
                <?php include "main.table.php"; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <ul class="pagination pagination-links">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li><a href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
        <div id="total-records" data-total="<?php echo $totalRecords; ?>"></div>
    </div>

    <!-- Add the form for adding new inventory items -->
<div class="modal fade" id="addinvModal" tabindex="-1" aria-labelledby="addinvModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addinvModalLabel">Input Inventory Field</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
        <form action="../modify/input.data.php" method="post">
            <!-- Employee -->
                <div class="container">
                <!-- Employee -->  
                <div class="separator">Employee Information</div>  
                    <div class="row py-1">
                        <div class="col-3">Employee: </div>
                        <div class="col input-group">
                            <input type="text" id="assignedTo" name="assignedTo" class="form-control" readonly>
                            <input type="hidden" id="assignedToId" name="assignedToId" class="form-control">
                            <button type="button" onclick="openUserPopup()" class="btn btn-success">Select User</button>
                        </div>
                    </div>

                <!-- Hardware -->
                <div class="separator">Hardware Information</div>

                    <div class="row py-1">
                        <div class="col-3">Hardware Type: </div>
                        <div class="col-9">
                            <input type="text" id="hardwareType" name="hardwareType[]" class="form-control input-group-addon" placeholder="Input Type">
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-3">Hardware Model: </div>
                        <div class="col-9">
                            <input type="text" id="hardwareModel" name="hardwareModel[]" class="form-control input-group-addon" placeholder="Input Model">
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-3">Hardware Processor: </div>
                        <div class="col-9">
                            <input type="text" id="hardwareProcessor" name="hardwareProcessor[]" class="form-control input-group-addon" placeholder="Input Processor">
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-3">Hardware RAM: </div>
                        <div class="col-9">
                            <input type="text" id="hardwareRAM" name="hardwareRAM[]" class="form-control input-group-addon" placeholder="Input RAM">
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-3">Hardware Storage: </div>
                        <div class="col-9">
                            <input type="text" id="hardwareStorage" name="hardwareStorage[]" class="form-control input-group-addon" placeholder="Input Storage">
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-3">OS Name: </div>
                        <div class="col-9">
                            <input type="text" id="osName" name="osName[]" class="form-control input-group-addon" placeholder="Input OS Name">
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-3">OS Version: </div>
                        <div class="col-9">
                            <input type="text" id="osVersion" name="osVersion[]" class="form-control input-group-addon" placeholder="Input OS Version">
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-3">OS Status: </div>
                        <div class="col-9">
                            <input type="text" id="osStatus" name="osStatus[]" class="form-control input-group-addon" placeholder="Input OS Status">
                        </div>
                    </div>
                    <div class="row py-1">
                        <div class="col-3">IP Address: </div>
                        <div class="col-9">
                            <input type="text" id="ipAddress" name="ipAddress" class="form-control input-group-addon" placeholder="Input IP" required>
                        </div>
                    </div>

                <!-- Software -->
                <div class="separator">Software Information</div>

                    <div class="row py-1">
                        <div class="col-5 text-center">Software Name</div>
                        <div class="col-7 text-center">Software Version</div>
                    </div>
                    <div class="row py-1" id="softwareContainer">
                        <div class="col input-group">
                            <input type="text" id="softwareName" name="selectedSoftwareName[]" class="form-control" placeholder="Input Software Name">
                            <input type="text" id="softwareVersion" name="selectedSoftwareVersion[]" class="form-control" placeholder="Input Software Version">
                            <button type="button" class="btn btn-outline-success" id="addSoftwareField" onclick="addSoftwareInput()"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>

                <!-- Peripherals -->
                <div class="separator">Peripherals Information</div>

                    <div class="row py-1">
                        <div class="col-4 text-center">Peripheral Type</div>
                        <div class="col-3 text-center">Peripheral Name</div>
                        <div class="col-4 text-center">Peripheral Model</div>
                    </div>
                    <div class="row py-1" id="peripheralsContainer">
                        <div class="col input-group">
                            <input type="text" id="peripheralType" name="peripheralsType[]" class="form-control" placeholder="Input Type">
                            <input type="text" id="peripheralName" name="peripheralsName[]" class="form-control" placeholder="Input Brand">
                            <input type="text" id="peripheralModel" name="peripheralsModel[]" class="form-control" placeholder="Model">
                            <button type="button" class="btn btn-outline-success" id="addPeripheralsField" onclick="addPeripheralsInput()"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="separator">Remarks</div>

                    <div>
                        <textarea class="form-control" name="remarkss" cols="5" rows="5"></textarea>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Return</button>
                <input type="submit" class="btn btn-success" value="Add to Inventory">
            </div>
        </form>
        </div>
    </div>
</div>

<!----------LOGOUT MODAL----------->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Logout Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to Log Out?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><span class="btn-label"><i class="bi bi-arrow-return-left"></i></span> Return</button>
        <button onclick="window.location.href='../logout.redirect.php'" type="button" class="btn btn-danger"><span class="btn-label"><i class="bi bi-box-arrow-in-left"></i></span> Log Out</button>
      </div>
    </div>
  </div>
</div>
<!-------END OF LOGOUT MODAL------->

</div> <!--- End of <div class="main"> --->

    <!-- JavaScript functions -->
    <script>
        
        function openUserPopup() {
            window.open('../modify/popup.user.php', '_blank', 'width=800,height=800');
        }

        function setAssignedTo(userId, userName) {
            document.getElementById('assignedTo').value = userName;
            document.getElementById('assignedToId').value = userId;
        }

        let peripheralsCounter = 1;

        function addPeripheralsInput() {
            peripheralsCounter++;
            const peripheralsContainer = document.getElementById('peripheralsContainer');

            const newDiv = document.createElement('div');
            newDiv.className = 'input-group';

            const peripheralNameInput = document.createElement('input');
            peripheralNameInput.type = 'text';
            peripheralNameInput.name = 'peripheralsName[]';
            peripheralNameInput.className = 'form-control';
            peripheralNameInput.placeholder = 'Input Brand';

            const peripheralTypeInput = document.createElement('input');
            peripheralTypeInput.type = 'text';
            peripheralTypeInput.name = 'peripheralsType[]';
            peripheralTypeInput.className = 'form-control';
            peripheralTypeInput.placeholder = 'Input Type';

            const peripheralModelInput = document.createElement('input');
            peripheralModelInput.type = 'text';
            peripheralModelInput.name = 'peripheralsModel[]';
            peripheralModelInput.className = 'form-control';
            peripheralModelInput.placeholder = 'Input Peripheral Model';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-outline-danger';
            removeButton.innerHTML = '<i class="bi bi-dash-lg"></i>';
            removeButton.onclick = function() {
                peripheralsContainer.removeChild(newDiv);
            };

            newDiv.appendChild(peripheralNameInput);
            newDiv.appendChild(peripheralTypeInput);
            newDiv.appendChild(peripheralModelInput);
            newDiv.appendChild(removeButton);

            peripheralsContainer.appendChild(newDiv);
        }

        let softwareCounter = 1;

        function addSoftwareInput() {
            softwareCounter++;
            const softwareContainer = document.getElementById('softwareContainer');

            const newDiv = document.createElement('div');
            newDiv.className = 'input-group';

            const softwareNameInput = document.createElement('input');
            softwareNameInput.type = 'text';
            softwareNameInput.name = 'selectedSoftwareName[]';
            softwareNameInput.className = 'form-control';
            softwareNameInput.placeholder = 'Input Software Name';

            const softwareVersionInput = document.createElement('input');
            softwareVersionInput.type = 'text';
            softwareVersionInput.name = 'selectedSoftwareVersion[]';
            softwareVersionInput.className = 'form-control';
            softwareVersionInput.placeholder = 'Input Software Version';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-outline-danger';
            removeButton.innerHTML = '<i class="bi bi-dash-lg"></i>';
            removeButton.onclick = function() {
                softwareContainer.removeChild(newDiv);
            };

            newDiv.appendChild(softwareNameInput);
            newDiv.appendChild(softwareVersionInput);
            newDiv.appendChild(removeButton);

            softwareContainer.appendChild(newDiv);
        }
        document.querySelectorAll('.minimal-link').forEach(link => {
    link.addEventListener('click', e => {
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        const rect = link.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        link.appendChild(ripple);
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});
    </script>
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
</body>
</html>