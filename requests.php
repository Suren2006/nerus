<?php
require_once './server.php';

session_start();


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}


$users = $k->users();
$requests = array_unique($k->followers());
$followings = $k->requests();
$user_id = $_SESSION['user']['id'];

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NERUS</title>
    <link rel="website icon" href="/img/newfavicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-black">
    

<div class="container-fluid d-flex justify-content-start align-items-start">
    <?php include './aside.php'; ?>
    <div class="main mx-auto mt-5 text-white col-lg-4 col-12">
        <h3>Requests</h3>
        <?php foreach($users as $key => $value) { ?>
            <?php if($value['id'] !== $_SESSION['user']['id']) { ?>
                <?php if((in_array($value['id'], $requests))) { ?>
                    <?php if(!(in_array($value['id'], $followings))) { ?>
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div class="d-flex justify-content-between align-items-center gap-4">
                                <div class="rounded-circle text-center overflow-hidden d-flex justify-content-center align-items-center" style=" width: 50px;aspect-ratio: 1">
                                    <img src="<?= $value['profile_picture'] ? $value['profile_picture'] : '/img/profile.png'?>" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div>
                                    <a href="./userprofile.php?profile=<?= $value['id']?>" class="d-flex justify-content-start align-items-center gap-2 text-decoration-none text-white">
                                        <h5> <?= $value['first_name'] ?></h5>
                                        <h5> <?= $value['last_name'] ?></h5>
                                    </a>
                                    <h6 class="text-secondary"> <?= $value['description'] ?> </h6>
                                </div>
                            </div>
                            <form action="/server.php" method="post">
                                <input type="hidden" name="action" value="follow">
                                <input type="hidden" name="user_id" value="<?= $value['id'] ?>">
                                <button type="submit" class="btn btn-primary text-white">
                                    <?=   'Follow Back' ?>
                                </button>
                            </form>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        <?php  } ?>
    </div>
</div>

</body>
</html>