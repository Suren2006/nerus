<?php 
require './server.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}
    


$user_name = $_SESSION['user']['first_name'];
$user_lastname = $_SESSION['user']['last_name'];
$user_posts = $_SESSION['user']['posts'] ?? '';
$description = $_SESSION['user']['description'];
$user_id = $_SESSION['user']['id'];
$profile_picture = $_SESSION['user']['profile_picture'] ? $_SESSION['user']['profile_picture'] : "./img/profile.png";
$posts = $k->db->query("SELECT * FROM posts WHERE user_id = $user_id ORDER BY id DESC")->fetch_all(true);
$followings = $k->countOfFollowings();
$followers = $k->countOfFollowers();

?>






<!DOCTYPE html>
<html lang="en">
<head>
  <title>NERUS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="website icon" href="./img/newfavicon.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-black">
    <div class="container d-flex justify-content-end align-items-start">
        <?php include './aside.php'; ?>
        <div class="col-12 col-lg-9 mt-5">
        <div class="w-100">
            <div class="d-flex w-100 justify-content-around align-items-center">
                <div class="w-50 text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>
                            <?= $user_name . " " . $user_lastname ?>
                        </h3>
                        <div class="d-lg-flex d-none">
                            <a href="settings.php" class="btn text-white">
                                <i class="bi bi-gear"></i>
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="mt-3 d-flex justify-content-between align-items-start flex-column flex-lg-row ">
                            <h5> <?= count($posts) ?> posts </h5>
                            <h5><a href="./followers.php" class="text-decoration-none text-white"> <?= $followers ?> followers </a></h5>
                            <h5><a href="./followings.php" class="text-decoration-none text-white"> <?= $followings ?> following </a></h5>
                        </div>
                        <div class="mt-3 text-secondary">
                            <?= $description ?>
                        </div>
                    </div>
                </div>
                <a href="./addProfilePic.php" class="profilePic rounded-circle text-center overflow-hidden d-flex justify-content-center align-items-center" style="aspect-ratio: 1">
                    <img src="<?= $profile_picture ?>" class="w-100 h-100 object-fit-cover" >
                </a>
            </div>
            <div class="w-100 bg-secondary wrapper mt-5"></div>
        </div>
        <div class="w-100">
            <div class="text-white mt-4">
                <?php if(empty($posts)) {?>
                    <div class="text-center mt-5">
                        <div class="fw-bolder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-camera" viewBox="0 0 16 16">
                                <path d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z"/>
                                <path d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                            </svg>
                        </div>
                        <h3 class="mt-3">Share photos</h3>
                        <h6 class="my-4">When you share photos, they will appear on your profile.</h6>
                        <a href="./addPost.php" class="text-decoration-none text-primary">Share your first photo</a>
                    </div>
                    <?php }else { ?>
                        <div>
                            <?php foreach ($posts as $key => $post) {?>
                                <div class="bg-dark rounded-3 my-5 py-4 col-lg-9 col-12 mx-auto">
                                    <div class="d-flex justify-content-between align-items-center ms-3">
                                        <div class="d-flex justify-content-between align-items-center gap-3">
                                            <div class="rounded-circle text-center overflow-hidden d-flex justify-content-center align-items-center" style=" width: 75px;aspect-ratio: 1">
                                            <img src="<?= $profile_picture ?>" class="w-100 h-100 object-fit-cover">
                                        </div> 
                                        <h4 class="m-0"><?= $user_name . " " . $user_lastname ?></h4> 
                                    </div>
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-outline-light border-0"  type="button" data-bs-toggle="dropdown" aria-expanded="false"> 
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form action="/server.php" method="post"> 
                                                    <input type="hidden" name="action" value="delete_post">  
                                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>"> 
                                                    <input type="hidden" name="post_url" value="<?= $post['img_url'] ?>"> 
                                                    <button type="submit" class="dropdown-item text-danger" >Delete Post</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <h4 class="ms-3"> <?=$post['title'] ?></h4>
                                    <h6 class="ms-3"> <?=$post['content'] ?></h6>
                                    <div class="d-flex justify-content-center align-items-center w-100 overflow-hidden" style="aspect-ratio: 1">
                                        <img src="<?= $post['img_url'] ?>" alt="" class="w-100 bg-white h-100 object-fit-cover">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <?php 
                                            $post_id = $post['id'];
                                            $my_like = $k->db->query("SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = $user_id")->fetch_all(true);
                                            $likes = $k->db->query("SELECT * FROM likes WHERE post_id = '$post_id'")->fetch_all(true);
                                            $save = $k->db->query("SELECT * FROM saves WHERE post_id = $post_id and user_id = $user_id")->fetch_all(true);
                                            ?>
                                        <button class="like_btn btn border-0 btn-lg text-white" data-id="<?=$post['id'] ?>">
                                            <?= (empty($my_like)) ? '♡' : '❤️' ?>
                                        </button>
                                        <?= count($likes) ?>
                                    </div>
                                    <button class="btn border-0 btn-dark saveBtn" data-id="<?= $post['id'] ?>">
                                        <i class="bi <?= (empty($save) ? 'bi-bookmark': 'bi-bookmark-fill') ?>"></i>
                                    </button>
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

    $('#logOut').click(function(e) {
        $.ajax({
            url: 'server.php',
            type: "POST",
            data: {
                action: 'logOut'
            },
            success: function(r){
                localStorage.clear();
                location.href = 'index.php'
            }
        })
    })
    
    $(".like_btn").click(function (e) {
        e.preventDefault();
        const post_id = $(this).data('id');
        const user_id = <?= $_SESSION['user']['id'] ?>;
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

$(".saveBtn").click(function (e) {
    e.preventDefault();
    const post_id = $(this).data('id');
    const user_id = <?= $_SESSION['user']['id'] ?>;
    const child = $(this).find('i')
    let save = false;
    if (child.hasClass("bi-bookmark")) {
        save = true;
    }

    console.log(save);
    $.ajax({
        url: "server.php",
        type: "POST",
        data: {
            post_id: post_id,
            user_id: user_id,
            save: save,
            action: 'savefunction'
        },
        success: function (r) {
            location.reload();
            console.log(r);
        }
    })
})
// console.log($('.profilePic').length)
if ($('.profilePic').find('img').width() < $('.profilePic').find('img').height()) {
    $('.profilePic').find('img').addClass('w-100');
}else {
    $('.profilePic').find('img').addClass('h-100');
}



</script>

</body>
</html>