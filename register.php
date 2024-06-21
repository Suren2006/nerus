<?php

session_start();


if (isset($_SESSION['user'])) {
    header("Location: profile.php");
}



$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];



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
    <h1 class="fw-semibold welcome">Roll the Carpet .!</h1>
    <div class="d-flex justify-content-between align-items-center w-100">
      <div class="col-4 col-xl-3 border border-2 text-center py-3 fs-3 fst-italic fw-bold" > Skip the lag ?</div>
      <div class="col-8 col-xl-9 border"></div>
    </div>
  </div>
  <div class="form-div col-10 col-xs-8 col-lg-4 mx-auto p-4 pt-5 border border-light border-opacity-25 ">
    <h1 class="fw-semibold"> Signup</h1>
    <h6 class="fw-lighter">Just some details to get you in.!</h6>
      <form action="./server.php" method="POST" id="register">
        <div class="my-4">
          <input type="text" class="form-control p-3 ps-4 rounded-4 border-1 text-white border-white bg-transparent" id="first_name" placeholder="First Name" name="first_name" >
        </div>
        <p class="text-danger" id="error-first_name"> </p>
        <div class="my-4">
          <input type="text" class="form-control p-3 ps-4 rounded-4 border-1 text-white border-white bg-transparent" id="last_name" placeholder="Last Name" name="last_name" >
        </div>
        <p class="text-danger" id="error-last_name"></p>
        <div class="my-4">
          <input type="email" class="form-control p-3 ps-4 rounded-4 border-1 text-white border-white bg-transparent" id="email" placeholder="Email" name="email" >
        </div>
        <p class="text-danger"  id="error-email"></p>
        <div class="my-4 d-flex justify-content-end align-items-center">
          <input type="password" class="form-control p-3 ps-4 rounded-4 border-1 text-white border-white bg-transparent" id="password" placeholder="Password" name="password" >
          <input class="form-check-input position-absolute me-4 checkPassword border-0 bg-transparent" type="checkbox" id="checkPassword">
        </div>
        <p class="text-danger" id="error-password"></p> 
        <input type="hidden" name="action" value="registerForm">
            <button type="submit" class="btn loginBtn w-100 text-white p-1 py-2 fs-5">Sign up</button>
      </form>
      

    <div class="d-flex justify-content-between align-items-center my-5">
        <hr>
        <h3 class="m-0 text-secondary">Or</h3>
        <hr>
    </div>

    <p class="text-center">Already Registered? <a href="/index.php" class="text-decoration-none text-white fw-bold"> Login</a></p>
  </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>

  $('#register').submit(function(e) {
    e.preventDefault();
    const firstName = $('#first_name').val();
    const lastName = $('#last_name').val();
    const email = $('#email').val();
    const password = $('#password').val();
    if (firstName.length < 40) {
      $(`#error-first_name`).empty()
      $(`#first_name`).removeClass("border-danger");
      $(`#first_name`).addClass("border-white");
    }
    if (lastName.length < 40) {
      $(`#error-last_name`).empty()
      $(`#last_name`).removeClass("border-danger");
      $(`#last_name`).addClass("border-white");
    }
    if (email.length < 255) {
      $(`#error-email`).empty()
      $(`#email`).removeClass("border-danger");
      $(`#email`).addClass("border-white");
    }
    if (password.length > 8) {
      $(`#error-password`).empty()
      $(`#password`).removeClass("border-danger");
      $(`#password`).addClass("border-white");
    }
      $.ajax({
          url: 'server.php',
          type: 'post',
          data: {
            first_name: firstName,
            last_name: lastName,
            email: email,
            password: password,
            action: "registerForm"
          },
          success:function(r){
            const { errors,success } = JSON.parse(r) || null;
            if (success) {
              window.location.href = "./index.php";
            }
            else if (errors) {
              Object.keys(errors).forEach( function(item) {
                $(`#error-${item}`).empty()
                $(`#error-${item}`).append(errors[item]);
                $(`#${item}`).removeClass("border-white");
                $(`#${item}`).addClass("border-danger");
                console.log(item)
              })
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
