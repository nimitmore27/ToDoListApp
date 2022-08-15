<?php
    $insert = false;
    $update = false;
    $delete = false;
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "keepnotes";
    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Sorry we failed to connect: " . mysqli_connect_error());
    }
    if (isset($_GET['delete'])) {
        $sno = $_GET['delete'];
        $delete = true;
        $sql = "DELETE FROM `mynotes` WHERE `sno` = $sno";
        $result = mysqli_query($conn, $sql);
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['snoEdit'])) {
            $sno = $_POST["snoEdit"];
            $title = $_POST["titleEdit"];
            $description = $_POST["descriptionEdit"];
            $sql = "UPDATE `mynotes` SET `title` = '$title' , `description` = '$description' WHERE `mynotes`.`sno` = $sno";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $update = true;
            } else {
                echo "We could not update the record successfully";
            }
        } else {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $sql = "INSERT INTO `mynotes` (`title`, `description`) VALUES ('$title', '$description')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $insert = true;
            } else {
                echo "The record was not inserted successfully because of this error ---> " . mysqli_error($conn);
            }
        }
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
        <title>Keep Notes</title>
    </head>
<body>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/ToDoListApp/index.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="desc">Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-block mr-auto">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
        if ($insert) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Success!</strong> Your note has been inserted successfully
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
        }
    ?>
    <?php
        if ($delete) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>Success!</strong> Your note has been deleted successfully
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
        }
    ?>
    <?php
        if ($update) {
            echo "  <div class='alert alert-success alert-dismissible fade show' role='alert'>
                        <strong>Success!</strong>
                        Your note has been updated successfully
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>";
        }
    ?>
    <div class="container my-4">
        <h2>Keep Notes</h2>
        <form action="/ToDoListApp/index.php" method="POST">
            <div class="form-group">
                <label for="title">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>
            <div class="form-group">
                <label for="desc">Note Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>
    <div class="container my-4">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM `mynotes`";
                    $result = mysqli_query($conn, $sql);
                    $sno = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sno = $sno + 1;
                        echo "<tr>
                                <th scope='row'>". $sno ."</th>
                                <td>" . $row['title'] . "</td>
                                <td>" . $row['description'] . "</td>
                                <td>
                                    <button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">
                                        Edit</button> <button class='delete btn btn-sm btn-primary' id=d" . $row['sno'] . ">
                                            Delete
                                    </button>
                                </td>
                            </tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <hr>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script src='script.js'>
    </script>
</body>
</html>