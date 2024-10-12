<?php
session_start();
include '../../service/condb.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AddUser++</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
    <div class="col-sm-10 center ">
    <div class=" h4 text-center alert alert-success mb-4 mt-4" role="alert">Add User</div>
    <form method="POST" action="insert_user.php">
    <label for="productId">UserID:</label>
    <input type="number" name="UserId" class="form-control" placehoder="....id">
    <label for="Name">Name:</label>
    <input type="text" name="name" class="form-control"class="form-control" placehoder="....name" required>
    <label for="Surname">Surname:</label>
    <input type="text" name="Surname" class="form-control"class="form-control" placehoder="....surname"required>
    <label for="Email">Email:</label>
    <input type="text" name="Email" class="form-control"class="form-control" placehoder="....email"required>
    <label for="Password">Password:</label>
    <input type="text" name="Password" class="form-control"class="form-control" placehoder="....password"required>
    <label for="Tel">Tel:</label>
    <input type="number" name="Tel" class="form-control"class="form-control" placehoder="....tel"required>
    <label for="Position">Position:</label>
    <input type="text" Position class="form-control">
    <label for="CreatedAt">Created At:</label>
    <input type="date" name="CreatedAt"class="form-control"> <br>
    <input type="submit" value="submit" class="btn btn-success">
    <a href="add_user.php" class="btn btn-danger">Cancer</a>
</div>
</form>
</body>
</html>