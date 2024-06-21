<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>NERUS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="website icon" href="/img/newfavicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-black">
<div class="container-fluid d-flex justify-content-start align-items-start">
    <?php include 'aside.php'?>

    <div class="mx-auto my-5 text-white col-10 col-lg-6">
        <h1 class="mb-3">Settings</h1>
        <hr>
        <div class="border rounded-3 p-4">
            <h3> Edit Profile</h3>
            <form action="./server.php" method="POST"id="editProfile">
                <div class="my-4">
                    <label for="first_name" class="text-secondary">First Name</label>
                    <input type="text" class="form-control p-3 ps-0 text-white rounded-0 border-0 border-bottom bg-transparent" id="first_name" value="<?= $_SESSION['user']['first_name'] ?>" name="first_name" >
                </div>
                <div class="my-4">
                    <label for="first_name" class="text-secondary">Last Name</label>
                    <input type="text" class="form-control p-3 ps-0 text-white rounded-0 border-white border-0 border-bottom bg-transparent" id="last_name" value="<?= $_SESSION['user']['last_name'] ?>" name="last_name" >
                </div>
                <div class="my-4">
                    <label for="first_name" class="text-secondary">Age</label>
                    <input type="number" class="form-control p-3 ps-0 text-white rounded-0 border-white border-0 border-bottom bg-transparent" id="age" value="<?= $_SESSION['user']['age'] ?>" name="age" >
                </div>
                <div class="my-4">
                    <label for="first_name" class="text-secondary">Description</label>
                    <input type="text" class="form-control p-3 ps-0 text-white rounded-0 border-white border-0 border-bottom bg-transparent" id="description" value="<?= $_SESSION['user']['description'] ?>" name="description" >
                </div>
                <button type="submit" class="btn btn-outline-success text-white fs-3 p-1 w-100">Update</button>
            </form>

        </div>


        <div class="border border-danger rounded-3 p-4 my-5">
            <h3 class="text-white mb-3 ms-3">Danger Zone</h3>
            <button class="btn btn-outline-danger mt-3 ms-3" id="deleteUser">
                DELETE Profile <i class="bi bi-trash"></i>
            </button>
            <button class="btn btn-outline-danger mt-3 ms-3" id="deleteprofilepic">
                DELETE Profile image <i class="bi bi-trash"></i>
            </button>

            <button class="btn btn-outline-light mt-3 ms-3" id="logOut">
                Log Out
            </button>
        </div>
    </div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>

    $("#editProfile").submit(function(e) {
        e.preventDefault();
        const firstName = $("#first_name").val();
        const lastName = $("#last_name").val();
        const age = $("#age").val();
        const description = $("#description").val();
        $.ajax({
            url: "server.php",
            type: "POST",
            data: {
                first_name: firstName,
                last_name: lastName,
                age: age,
                description: description,
                action: 'updateInfo'
            },
            success: function(r) {
                alert(r);
                location.href = "./profile.php";
            }

        })
    })

    $('#deleteUser').click(function (e) {
        $.ajax({
            url: 'server.php',
            type: "post",
            data: {
                'action': 'deleteUser'
            },
            success: function (r) {
                location.href = 'index.php'
            }
        })
    })


    $('#deleteprofilepic').click(function (e) {
        $.ajax({
            url: 'server.php',
            type: "post",
            data: {
                'action': 'deleteprofilepic'
            },
            success: function (r) {
                location.href = 'profile.php'
            }
        })
    })

    $('#logOut').click(function (e) {
        $.ajax({
            url: 'server.php',
            type: "post",
            data: {
                'action': 'logOut'
            },
            success: function (r) {
                location.href = 'index.php'
            }
        })
    })

</script>
</body>
</html>