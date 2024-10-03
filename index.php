<?php


// connection database
$host = "127.0.0.1:4306";
$username = "root";
$password = "";
$dbname = "company";
$con = mysqli_connect($host, $username, $password, $dbname);






// CREATE DATA
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $insertquery = "INSERT INTO `employees` VALUES (NULL,'$name','$email','$phone','$gender','$department')";
    $insert = mysqli_query($con, $insertquery);
}


// DELETE DATA
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deletequery = "DELETE FROM `employees` where id = $id";
    $delete = mysqli_query($con, $deletequery);
}

$name = '';
$email = '';
$phone = '';
$gender = '';
$department = '';
$empId = '';
$mode = 'create';

// EDIT DATA
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $selectByIdquery = "SELECT * FROM `employees` where id = $id";
    $edit = mysqli_query($con, $selectByIdquery);
    $row = mysqli_fetch_assoc($edit);
    $name = $row['name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $gender = $row['gender'];
    $department = $row['department'];
    $empId = $id;
    $mode = 'update';
}


if(isset($_POST['update'])){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $department = $_POST['department'];
    $updatequery = "UPDATE `employees` SET `name` = '$name',phone = '$phone' ,email = '$email',gender='$gender',department='$department' where id=$empId";
    $update = mysqli_query($con, $updatequery);
    header("location:index.php");
}


// SEARCH DATA
$selectquery = "SELECT * FROM `employees`";
$search = '';
$message = '';
if(isset($_GET['search'])){
    $value = $_GET['search'];
    $search = $value;
    $selectquery = "SELECT * FROM `employees` where `name` like '%$value%' or email like '%$value%' or department like '%$value%' or gender like '%$value%'";
}
// ORDER BY ASC
if(isset($_GET['asc'])){
    if(!isset($_GET['orderBy'])){
        $message = 'Please select A column to order by';
    }else{
        $order = $_GET['orderBy'];
        $selectquery = "SELECT * FROM `employees` ORDER BY $order ASC";
    }
}
// ORDER BY DESC
if(isset($_GET['desc'])){
    if (!isset($_GET['orderBy'])) {
        $message = 'Please select A column to order by';
    } else {
        $order = $_GET['orderBy'];
        $selectquery = "SELECT * FROM `employees` ORDER BY $order DESC";
    }
}
// READ DATA
$select = mysqli_query($con, $selectquery);


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <style>
        body {
            background-color: #333;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container py-2">
        <div class="card bg-dark text-light">
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                value="<?= $name ?>"
                                name="name"
                                id="name"
                                class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                value="<?= $email ?>"
                                name="email"
                                id="email"
                                class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input
                                type="text"
                                name="phone"
                                value="<?= $phone ?>"
                                id="phone"
                                class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="form-select">
                                <?php if ($gender == 'male'): ?>
                                    <option disabled >Choose...</option>
                                    <option value="male" selected>Male</option>
                                    <option value="female">Female</option>
                                <?php elseif ($gender == 'female'): ?>
                                    <option disabled>Choose...</option>
                                    <option value="male">Male</option>
                                    <option value="female" selected>Female</option>
                                <?php else: ?>
                                    <option disabled selected>Choose...</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input
                                type="text"
                                value="<?= $department ?>"
                                name="department"
                                id="department"
                                class="form-control" />
                        </div>
                        <div class="col-12 text-center">
                            <?php if($mode=='update'):?>
                                <button class="btn btn-warning" name="update">UPDATE</button>
                                <a href="index.php" class="btn btn-secondary">CANCEL</a>
                            <?php else:?>
                            <!-- <button class="btn btn-warning" name="update">UPDATE</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a> -->
                            <button class="btn btn-primary" name="submit">Submit</button>
                            <input type="text" name="submit" hidden value="<?= $emp['id'] ?>">
                            <?php endif;?>
                            

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container py-2">
        <div class="card bg-dark text-light">
            <div class="card-body">
                <h2 class="text-center">filters</h2>
                <form>
                    <div class="mb-3">
                        <label for="search" class="form-label">Search</label>
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                value="<?=$search?>"
                                name="search"
                                id="search" />
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
                <form>
                    <h5 class="text-danger"><?= $message?></h5>
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3">
                            <label for="orderBy">Order By</label>
                            <select name="orderBy" id="orderBy" class="form-select">
                                <option disabled selected>Choose...</option>
                                <option value="id">Id</option>
                                <option value="name">Name</option>
                                <option value="gender">Gender</option>
                                <option value="email">Email</option>
                                <option value="phone">Phone</option>
                                <option value="department">Department</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-info" name="asc">Ascending</button>
                            <button class="btn btn-info" name="desc">Descending</button>
                        </div>
                    </div>
                </form>
                <a href="./index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>

    <div class="container py-2">
        <div class="card bg-dark">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>name</th>
                        <th>email</th>
                        <th>phone</th>
                        <th>gender</th>
                        <th>department</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data LOOP -->
                    <?php foreach ($select as $i => $emp): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $emp['name'] ?></td>
                            <td><?= $emp['email'] ?></td>
                            <td><?= $emp['phone'] ?></td>
                            <td><?= $emp['gender'] ?></td>
                            <td><?= $emp['department'] ?></td>
                            <td>
                                <!-- <form>
                                    <input type="text" name="delete" hidden value="<?= $emp['id'] ?>">
                                    <button class="btn btn-danger">DELETE</button>
                                </form> -->
                                <a href="?edit=<?= $emp['id'] ?>" class=" btn btn-warning">EDIT</a>
                                <a href="?delete=<?= $emp['id'] ?>" class=" btn btn-danger">DELETE</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <!-- end of loop -->

                </tbody>
            </table>
        </div>
    </div>
</body>

</html>