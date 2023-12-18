<!DOCTYPE html>
<html>
<head>
    <title>Employee List</title>
    <link rel="icon" href="../images/favicon-32x32.png">
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        h2 {
            margin-top: 20px;
        }

        #searchBox {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .department-header {
            background: rgb(0,0,0);
            background: linear-gradient(156deg, rgba(0,0,0,1) 70%, rgba(185,0,0,1) 70%, rgba(255,2,2,1) 77%, rgba(24,80,129,1) 77%, rgba(56,111,191,1) 85%, rgba(53,94,59,1) 85%, rgba(75,145,86,1) 100%, rgba(0,0,0,1) 100%);
            color: #fff;
            padding: 5px 10px;
            margin: 10px 0;
        }

        .employee-row {
            background-color: #fff;
            border: 1px solid #ccc;
            margin: 5px 0;
            padding: 0;
            border-radius: 5px;
            transition: background-color 0.3s;
            cursor: pointer;
            overflow: hidden;
        }

        .employee-row-content {
            padding: 10px;
        }

        .employee-row a {
            text-decoration: none;
            color: #333;
        }

        .employee-row:hover {
            background-color: #f0f0f0;
        }

        .table-bordered {
            border: 1px solid #ccc;
            border-collapse: collapse;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    </head>
<body>
    <div class="container">
    <h2>Employee Selection</h2>

    <input type="text" id="searchBox" placeholder="Search for users">

    <ul id="userList">
        <?php
        require_once "../conn.php";
        $query = "SELECT * FROM employee JOIN department ON department.dpt_id = employee.dpt_id JOIN position ON position.position_id = employee.position_id JOIN division ON division.div_id = employee.div_id ORDER BY department.dpt_name";
        $stmt = sqlsrv_query($conn, $query);

        if ($stmt === false) {
            die("Error: " . print_r(sqlsrv_errors(), true));
        }

        $currentDepartment = null;

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if ($currentDepartment !== $row['dpt_name']) {
                if ($currentDepartment !== null) {
                    echo '</tbody></table></div></div>';
                }
                echo '<div class="mb-4"><h4 class="department-header">' . $row['dpt_name'] . '</h4><div class="table-responsive"><table class="table table-bordered"><tbody>';
                echo '<tr><th>Employee Name</th><th>Position</th></tr>';
                $currentDepartment = $row['dpt_name'];
            }
            echo '<tr class="employee-row"><td><div class="employee-row-content"><a href="#" class="user-link" data-user-id="' . $row['employee_id'] . '" data-user-name="' . $row['employee_name'] . '">' . $row['employee_name'] . '</a></div></td><td>' . $row['position_name'] . '</td></tr>';
        }

        if ($currentDepartment !== null) {
            echo '</tbody></table></div></div>';
        }

        sqlsrv_free_stmt($stmt);
        ?>
    </ul>
    </div>
    <script>
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('user-link')) {
                e.preventDefault();
                var userId = e.target.dataset.userId;
                var userName = e.target.dataset.userName;
                window.opener.setAssignedTo(userId, userName);
                window.close();
            }
        });

        document.getElementById('searchBox').addEventListener('input', function(e) {
            var searchTerm = e.target.value.toLowerCase();
            var userList = document.getElementById('userList');
            var rows = userList.getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var rowContent = rows[i].innerText.toLowerCase();
                if (rowContent.includes(searchTerm)) {
                    rows[i].style.display = 'table-row';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
