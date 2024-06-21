<?php

session_start();


$errors = (isset($_SESSION['errors'])) ? $_SESSION['errors'] : [];

if (isset($_SESSION['user'])) {
  header("Location: /profile.php");
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
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
    font-family: 'Noto Sans', sans-serif;
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
  color: <?= isset($errors['loginError']) ? '#ff0000' : '#fff'  ?> ;
}

.loginBtn {
  border-radius: 12px;
  background: linear-gradient(119deg, #628EFF 0%, #8740CD 53.13%, #580475 100%);
}

@media screen and (max-width: 1400px) {
  .welcome {
    font-size: 72px;
}
}

.checkPassword {
  background-image: url('./img/eye-slash.svg');
}


.checkPassword:checked {
  background-image: url('./img/eye.svg');
}

hr {
  width: 40%;
}

</style>


<body>
<div class="container-fluid px-lg-5 p-0 text-white d-flex position-absolute top-50 start-50 translate-middle">
  <div class="col-8 mx-auto d-none d-lg-flex flex-column justify-content-center align-items-start">
    <h1 class="fw-semibold welcome">Welcome Back .!</h1>
    <div class="d-flex justify-content-between align-items-center w-100">
      <div class="col-4 col-xl-3 border border-2 text-center py-3 fs-3 fst-italic fw-bold" > Skip the lag ?</div>
      <div class="col-8 col-xl-9 border"></div>
    </div>
  </div>

  <div class="form-div col-10 col-xs-8 col-lg-4 mx-auto p-4 pt-5 border border-light border-opacity-25 ">
    <h1 class="fw-semibold"> Login </h1>
    <h6 class="fw-lighter">Glad you're back.!</h6>
    <form action="./server.php" method="POST" id="login">
      <div class="my-4">
        <input type="email" class="form-control p-3 ps-4 rounded-4 border-1 text-white border-white bg-transparent" id="email" placeholder="Email" name="email">
      </div>
      <div class="my-4 d-flex justify-content-end align-items-center">
        <input type="password" class="form-control p-3 ps-4 rounded-4 border-1 text-white border-white bg-transparent" id="password" placeholder="Password" name="password">
        <input class="form-check-input position-absolute me-4 checkPassword border-0 bg-transparent" type="checkbox" id="checkPassword">
      </div>
      
      <div class="my-4">
        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
        <label for="remember"> Remember me </label>
      </div>

      <p class="text-danger" id="errors"></p>
      <input type="hidden" name="action" value="userlogin">
      <button type="submit" class="btn loginBtn w-100 text-white p-1 py-2 fs-5">Login</button>
    </form>


    <div class="d-flex justify-content-between align-items-center my-5">
      <hr>
      <h3 class="m-0 text-secondary">Or</h3>
      <hr>
    </div>

    <p class="text-center">Don't have an Account? <a href="/register.php" class="text-decoration-none text-white fw-bold">SignUp</a></p>
  </div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
  if(localStorage.getItem('user')) {
    const id = localStorage.getItem('user');
    $.ajax({
      url: 'server.php',
      type: "POST",
      data: {
        id: id,
        action: 'ifremembered'
      },
      success:  function (r){
        location.href = 'profile.php'
      }
    })
  } 
  $("#login").submit(function(e) {
    e.preventDefault();
    const email = $("#email").val();
    const password = $("#password").val();
    const remember = $("#remember")[0].checked
    
 
  if ((email.length < 255) && (password.length > 8) ) {
      $(`#errors`).empty()
      $(`#email`).removeClass("border-danger");
      $(`#email`).addClass("border-white");
      $(`#password`).removeClass("border-danger");
      $(`#password`).addClass("border-white");
  }

  $.ajax({
    url: "server.php",
    type: "POST",
    data: {
      email: email,
      password: password,
      action: "userLogin"
    },
    success: function(r) {
      const {errors , success} = JSON.parse(r) || null;
      if (errors) {
        Object.keys(errors).forEach( function(item) {
          console.log(errors[item].loginError)
          $(`#${item}`).empty()
          $(`#${item}`).append(errors[item].loginError);
          $(`#email`).removeClass("border-white");
          $(`#email`).addClass("border-danger");
          $(`#password`).removeClass("border-white");
          $(`#password`).addClass("border-danger");
        })
      }
      if (success) {
        if (remember) {
          localStorage.setItem('user', success)
        }
        location.href = "./profile.php";
      }
    }
  })

})

$("#checkPassword").click(function(e) {
  if ($("#password").attr('type') == "password") {
    $("#password").attr('type', 'text');
  }else {
    $("#password").attr('type', 'password');
  }
})

</script>
</body>
</html>
