<?php 

require './server.php';
session_start();


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}


$user_id = $_GET['profile'];
$main_user_id = $_SESSION['user']['id'];
$user = $k->db->query("SELECT * FROM users WHERE id = $user_id")->fetch_all(true);

$chat_id = (min($user_id, $main_user_id) . '000' . max($user_id, $main_user_id));

$user_name = $user[0]['first_name'];
$user_lastname = $user[0]['last_name'];
$user_posts = $user[0]['posts'] ?? '';
$description = $user[0]['description'];
$user_id = $user[0]['id'];
$followings = count($k->db->query("SELECT * FROM requests WHERE from_user = '$user_id'")->fetch_all(true));
$followers = count($k->db->query("SELECT * FROM requests WHERE to_user = '$user_id'")->fetch_all(true));
$posts = $k->db->query("SELECT *  FROM posts WHERE user_id = $user_id")->fetch_all(true);
$follow_or_not = !empty($k->db->query("SELECT * FROM requests WHERE to_user = $user_id AND from_user = $main_user_id")->fetch_all(true));
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
<div class="container d-flex justify-content-end align-items-start">

    <?php include './aside.php'; ?>
    <div class="col-lg-9 col-12 mt-5">
        <div class="w-100">
            <div class="d-flex w-100 justify-content-around align-items-center">
                <div class="w-50 text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>
                            <?= $user_name . " " . $user_lastname ?>
                        </h3>
                    </div>
                    <div class="d-flex justify-content-between align-items-center ">
                        <div class="w-100">
                            <div class="mt-3 d-flex justify-content-between align-items-start flex-column flex-lg-row ">
                                <h5><?= count($posts) ?> posts </h5>
                                <h5><a href="./followers.php?profile=<?= $user_id ?>" class="text-decoration-none text-white"> <?= $followers ?> followers </a></h5>
                                <h5><a href="./followings.php?profile=<?= $user_id ?>" class="text-decoration-none text-white"> <?= $followings ?> following </a></h5>
                            </div>    
                            <div class="my-3 text-secondary">
                                <?= $description ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center ">
                                <form action="/server.php" method="post">
                                    <input type="hidden" name="action" value="follow">
                                    <input type="hidden" name="user_id" value="<?= $user_id ?>">
                                    <button type="submit" class="btn btn-<?= $follow_or_not ? "dark" : "primary" ?>">
                                        <?= $follow_or_not ? "Unfollow" : "Follow" ?>
                                    </button>
                                </form>
                                <a href="/chat/<?= $chat_id  ?>" onclick="javascript:event.target.port=3000" class="btn btn-outline-primary ms-3">Message</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profilePic rounded-circle text-center overflow-hidden d-flex justify-content-center align-items-center" style="aspect-ratio: 1">
                    <img src="<?= $user[0]['profile_picture'] ? $user[0]['profile_picture']  : '/img/profile.png' ?>" class="w-100 h-100 object-fit-cover">
                </div> 
            </div>
            <div class="w-100 bg-secondary wrapper mt-5"></div>
        </div>
        <div class="w-100">
            <div class="text-white mt-4">
                <?php if(empty($posts)) {?>
                    <div class="text-center mt-5">
                        No Posts Found
                    </div>
                <?php }else { ?>
                    <div>
                        <?php foreach ($posts as $key => $post) {?>
                            <div class="bg-dark rounded-3 my-5 py-4 col-lg-9 col-12 mx-auto">
                                <div class="d-flex justify-content-between align-items-center ms-3">
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div class="rounded-circle text-center overflow-hidden d-flex justify-content-center align-items-center" style=" width: 75px;aspect-ratio: 1">
                                            <img src="<?= $user[0]['profile_picture'] ? $user[0]['profile_picture'] : './img/profile.png' ?>" class="w-100 h-100 object-fit-cover">
                                        </div>     
                                        <h4 class="m-0"><?= $user_name . " " . $user_lastname ?></h4> 
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <h4 class="ms-3 "> <?=$post['title'] ?></h4>
                                    <h6 class="ms-3 mt-3"> <?=$post['content'] ?></h6>
                                    <div class="d-flex justify-content-center align-items-center w-100 overflow-hidden" style="aspect-ratio: 1"">
                                        <img src="<?= $post['img_url'] ?>" alt="" class="w-100 bg-white h-100 object-fit-cover">
                                    </div>
                                </div>
                                <div>
                                    <?php 
                                            $post_id = $post['id'];
                                            $my_like = $k->db->query("SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = $main_user_id")->fetch_all(true);
                                            $likes = $k->db->query("SELECT * FROM likes WHERE post_id = '$post_id'")->fetch_all(true);
                                        ?>
                                        <button class="like_btn btn border-0 btn-lg text-white" data-id="<?=$post['id'] ?>">
                                            <?= (empty($my_like)) ? '♡' : '❤️' ?>
                                        </button>
                                        <?= count($likes) ?>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>

$(".like_btn").click(function (e) {
    e.preventDefault();
    const post_id = $(this).data('id');
    const user_id = <?= $main_user_id ?>;
    $.ajax({
        url: 'server.php',
        type: "POST",
        data: {
            post_id: post_id,
            user_id: user_id,
            action: "like_function"
        },
        success: function(r) {
            location.reload();
        }
    })
})


</script>

</body>
</html>