<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f6f7f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        header {
            background-color: #2e5bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 32px;
        }
        footer {
            background-color: #2e5bff;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
        footer p {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #e0e6ef;
            text-align: left;
        }
        th {
            background-color: #f0f3f7;
            color: #4a566b;
            font-weight: bold;
            text-transform: uppercase;
        }
        caption {
            font-size: 1.2em;
            margin-bottom: 10px;
            color: #4a566b;
        }
        form {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #e0e6ef;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            color: #333;
        }
        input[type="submit"], input[type="reset"] {
            padding: 12px 24px;
            background-color: #2e5bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            font-size: 14px;
            text-transform: uppercase;
        }
        input[type="submit"]:hover, input[type="reset"]:hover {
            background-color: #1e4cff;
        }
        .error-message {
            color: #ff4d4f;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .action-links a {
            text-decoration: none;
            color: #2e5bff;
            margin-right: 10px;
            transition: color 0.3s ease;
        }
        .action-links a:hover {
            color: #1e4cff;
        }
    </style>
</head>
<body>
    <header>
        <h1>Student Management System</h1>
    </header>
    <div class="container">
        <form method="GET">
            <input type="text" name="search" placeholder="Search by Rollno or Student Name">
            <input type="submit" value="Search">
            <input type="reset" value="Reset">
        </form>

        <?php
        // Database connection
        include "db_conn.php";

        // Search students
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql = "SELECT * FROM students WHERE Rollno LIKE '%$search%' OR Sname LIKE '%$search%'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<caption>Search Results</caption>";
                echo "<tr><th>Rollno</th><th>Student Fullname</th><th>Address</th><th>Email</th><th>Action</th></tr>";
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>".$row['Rollno']."</td>";
                    echo "<td>".$row['Sname']."</td>";
                    echo "<td>".$row['Address']."</td>";
                    echo "<td>".$row['Email']."</td>";
                    echo "<td class='action-links'><a href='edit_student.php?id=".$row['Rollno']."'>Edit</a> <a href='?delete_id=".$row['Rollno']."'>Delete</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No results found</p>";
            }
        }
        ?>

    <div class="container">
        <?php
        include "db_conn.php";
    
            if(isset($_POST['btnAdd'])) {
            $Rollno = $_POST['Rollno'];
            $Sname = $_POST['Sname'];
            $Address = $_POST['Address'];
            $Email = $_POST['Email'];
            if($Rollno=="" || $Sname=="" || $Address=="" || $Email=="")
            {
                echo "<p class='error-message'>(*) Fields cannot be empty</p>";
            } else 
                {
                    $sql = "SELECT Rollno FROM students WHERE Rollno='$Rollno'";
                    $result = mysqli_query($conn,$sql);
                    if(mysqli_num_rows($result)==0) {
                    $sql = "INSERT INTO students VALUES ('$Rollno', '$Sname', '$Address', '$Email')";
                    mysqli_query($conn,$sql);
                    echo '<meta http-equiv="refresh" content="0; URL=StudentList.php">';
                    } else {
                        echo "<p class='error-message'>Student already exists</p>";
                    }
                }
    }

    if(isset($_GET['delete_id'])) {
        $id = $_GET['delete_id'];
        $sql = "DELETE FROM students WHERE Rollno='$id'";
        if(mysqli_query($conn, $sql)) {
            header("Location: StudentList.php");
            exit();
        } else {
            echo "<h2>Error deleting record:</h2><p class='error-message'>" . mysqli_error($conn) . "</p>";
        }
    }
    ?>

    <table>
        <caption>Student List</caption>
        <tr>
            <th>Rollno</th>
            <th>Student Fullname</th>
            <th>Address</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php
        $sql = "SELECT * FROM students";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        ?>
            <tr>
                <td><?php echo $row['Rollno']; ?></td>
                <td><?php echo $row['Sname']; ?></td>
                <td><?php echo $row['Address']; ?></td>
                <td><?php echo $row['Email']; ?></td>
                <td class="action-links">
                    <a href="edit_student.php?id=<?php echo $row['Rollno']; ?>">Edit</a>
                    <a href="?delete_id=<?php echo $row['Rollno']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <form method="post">
        <table>
            <caption><b>Add Student</b></caption>
            <tr>
                <td>Rollno</td>
                <td><input type="text" name="Rollno"/></td>
            </tr>
            <tr>
                <td>Student Name</td>
                <td><input type="text" name="Sname"/></td>
            </tr>
            <tr>
                <td>Student Address</td>
                <td><input type="text" name="Address"/></td>
            </tr>
            <tr>
                <td>Student Email</td>
                <td><input type="text" name="Email"/></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Add" name="btnAdd"/>
<input type="reset" value="Cancel" name="btnCancel"/>
                </td>
            </tr>
        </table>
    </form>
        <!-- Your content goes here -->
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Student Management System</p>
    </footer>
</body>
</html>
