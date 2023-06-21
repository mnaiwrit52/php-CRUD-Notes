<?php

$showAlert = false;
$edited = false;

//Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";

//Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

//Die if connection is not successful
if (!$conn) {
    die("Sorry we failed to connect: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        //Update Query
        $title = $_POST['editTitle'];
        $sno = $_POST['snoEdit'];
        $description = $_POST['editDesc'];

        //SQL Query to be executed
        $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`s_no` = '$sno'";
        $result = mysqli_query($conn, $sql);

        if($result){
            $edited = true; 
        }
    }
    else
    {
        $title = $_POST['title'];
        $description = $_POST['desc'];
    
        //SQL Query to be executed
        $sql = "INSERT INTO `notes` (`s_no`, `title`, `description`, `tstamp`) VALUES (NULL, '$title', '$description', current_timestamp())";
        $result = mysqli_query($conn, $sql);
    
        if ($result) {
            $showAlert = true;
        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="notes.css">
    <title>Document</title>
</head>

<body>

    <!-- Edit Modal Starts -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./index.php" method="POST">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Note Title</label>
                            <input type="text" class="form-control" name="editTitle" id="editTitle" aria-describedby="editTitle">
                        </div>
                        <div class="mb-3">
                            <label for="editDesc" class="form-label">Description</label>
                            <textarea class="form-control" name="editDesc" id="editDesc" cols="30" rows="7"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Note</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal Ends -->

    <!-- Navbar Starts -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="https://cdn-icons-png.flaticon.com/512/5968/5968332.png" alt="php-logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active " aria-current="page" href="#">Home</a>
                    <a class="nav-link " href="#">About Us</a>
                    <a class="nav-link " href="#">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar Ends -->

    <!-- Successfully data entried alert starts -->
    <?php
    if ($showAlert) {
        echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Note has been inserted successfully<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> ';
    }
    ?>
    <!-- Successfully data entried alert ends -->

    <!-- Updation success alert starts-->
    <?php
    if ($edited) {
        echo ' <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Note has been updated successfully<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> ';
    }
    ?>
    <!-- Updation success alert ends -->


    <!-- Note Form starts -->
    <div class="container d-flex flex-column align-items-center my-3">
        <div class="form-heading">
            <h1>Add a Note 📑</h1>
        </div>
        <form action="./index.php" method="POST">
            <div class="mb-3">
                <label for="note-heading" class="form-label">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="title">
            </div>
            <div class="mb-3">
                <label for="desc" class="form-label">Description</label>
                <textarea class="form-control" name="desc" id="desc" cols="30" rows="7"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>
    <!-- Note Form ends -->

    <hr>

    <!-- Note Database Contents Starts -->
    <div class="container">
        <table class="table table-striped" id="myTable">
            <thead>
                <tr>
                    <th scope="col">SNo.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno = $sno + 1;
                    echo '<tr>
                <th scope="row">' . $sno . '</th>
                <td>' . $row['title'] . '</td>
                <td>' . $row['description'] . '</td>
                <td><button class="edit btn btn-primary" id=' . $row['s_no'] . '>Edit</button> <a href="/del">Delete</a></td>
                </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Note Database Contents Ends -->



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <!-- Datatable JS -->
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="notes.js"></script>
</body>

</html>