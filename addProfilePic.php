<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="website icon" href="/img/newfavicon.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<style>

body {
    background-image: url('./img/background.png');
    background-size: cover;
    background-position: center;
    height: 100vh;
    font-family: Noto Sans;
}
.skipTheLag {
  font-size: 32px;
}
.welcome {
  font-size: 96px;
}

.form-div {
  border-radius: 20px;
  background: var(--glass-1-fill-carey, linear-gradient(294deg, rgba(191, 191, 191, 0.06) 0%, rgba(0, 0, 0, 0.00) 100%), rgba(0, 0, 0, 0.14));
  box-shadow: -8px 4px 5px 0px rgba(0, 0, 0, 0.24);
  backdrop-filter: blur(26.5px);
}

.form-control::placeholder {
  color: #fff ;
}

.loginBtn {
  border-radius: 12px;
  background: linear-gradient(119deg, #628EFF 0%, #8740CD 53.13%, #580475 100%);
}

</style>


<body>

<div class="container-fluid text-white d-flex position-absolute top-50 start-50 translate-middle">
  <div class="form-div col-8 col-lg-4 mx-auto p-4">
    <h1 class="fw-semibold"> Add Profile Picture</h1>
      <form action="./server.php" method="POST" id="addPost" enctype="multipart/form-data">
        <div class="my-4">
          <div class="profilePic w-100 rounded-circle text-center overflow-hidden d-flex justify-content-center align-items-center" style="aspect-ratio: 1">
            <img src="" id="profile-img" class="w-100 h-100 object-fit-cover">
          </div>
          <input type="file" class="form-control  p-3 rounded-4 border-2 text-white bg-transparent" id="img" name="img" >
        </div>
        <p class="text-danger"> <?= $errors['password'] ?? "" ?> </p> 
        <input type="hidden" name="action" value="update_profile_pic">
        <button type="submit" class="btn loginBtn text-white fs-3 p-1 w-100">Add</button>
        <a href="./profile.php" class="btn btn-dark text-white fs-3 mt-3 p-1 w-100">Cancel</a>
      </form>
      
  </div>
</div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>

  $("#img").change(function (e) {
    const url = URL.createObjectURL(e.target.files[0])
    $('#profile-img').attr('src', url);
  })

  $("#addPost").submit(function (e) {
    e.preventDefault();
    const files = $("#img")[0]
    const img = files.files[0];

    const formData = new FormData();
    formData.append('img', img);
    formData.append('action', "update_profile_pic");

    // console.log(img);

    $.ajax({
      url: "server.php",
      type: "post",
      contentType: false,
      processData: false,
      data: formData,
      success: function(r) {
        alert(r);
        if (r == "The profile picture has been uploaded successfully.") {
          location.href = "./profile.php";
        }else {
          location.href = "./addProfilePic.php"
        }
      },
    })
  })

  if ($('.profilePic').find('img').width() < $('.profilePic').find('img').height()) {
    $('.profilePic').find('img').addClass('w-100');
}else {
    $('.profilePic').find('img').addClass('h-100');
}

</script>
</body>
</html>
