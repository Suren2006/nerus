<?php
require_once './server.php';

session_start();


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}


$user_id = (isset($_GET['profile'])) ? $_GET['profile'] : $_SESSION['user']['id'];
$requests = $k->requests();
$followers = $k->db->query("SELECT * FROM users JOIN requests ON requests.from_user = users.id AND requests.to_user = $user_id")->fetch_all(true);
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
    
    <div class="main mx-auto mt-5  text-white col-lg-4 col-12">
        <h3 class="mb-3">Followers</h3>
        <?php foreach($followers as $key => $value) { ?>
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
                <?php if($value['id'] !== $_SESSION['user']['id']) { ?>
                    <form action="/server.php" method="post">
                        <input type="hidden" name="action" value="follow">
                        <input type="hidden" name="user_id" value="<?= $value['id'] ?>">
                        <input type="hidden" name="path" value="suggestions">
                        <!-- <button type="submit" class="btn btn-outline-primary">Unfollow</button> -->
                        <button type="submit" class="btn btn-<?= (in_array($value['id'], $requests)) ? 'dark' : 'primary' ?> text-white">
                            <?= (in_array($value['id'], $requests)) ? 'Unfollow' : 'Follow' ?>
                        </button>
                    </form>
                <?php } ?>
            </div>
        <?php  } ?>
    </div>
</body>
</html>