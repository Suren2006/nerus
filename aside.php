<div class="text-white start-0 px-lg-4 aside bottom-0 col-12 col-lg-auto d-flex justify-content-lg-around justify-content-center align-items-lg-start align-items-end flex-row flex-lg-column position-fixed border-end border-dark bg-black">
    <div class="d-none d-lg-block">
        <a href="./profile.php">
            <img src="/img/newlogo.png" width="200px">
        </a>
        <hr>
    </div>
    <ul class="list-unstyled w-100 d-flex justify-content-between align-items-center d-lg-block m-0">
        <li>
            <a href="./requests.php" class="w-100 btn btn-outline-dark border-0 mt-4 text-start text-decoration-none text-white fs-3">
                <p class="d-none d-lg-inline "> Requests </p>
<!--                <img src="./img/notification-bell-svgrepo-com.svg" class="" width="30px">-->
                <i class="bi bi-bell"></i>
            </a>
        </li>
        <li>
            <a href="/suggestions.php" class="w-100 btn btn-outline-dark border-0 mt-4 text-start text-decoration-none text-white fs-3">
                <p class="d-none d-lg-inline "> Other Users </p>
                <i class="bi bi-people"></i>
            </a>
        </li>
        <li>
            <a href="<?php  
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";     
    
    $url.= $_SERVER['HTTP_HOST'];   
    $url.= ":3000/chats/$user_id";
     
    echo $url;  
  ?>   
" class="w-100 btn btn-outline-dark border-0 mt-4 text-start text-decoration-none text-white fs-3">
                <p class="d-none d-lg-inline "> Chats </p>
                <i class="bi bi-people"></i>
            </a>
        </li>
        <li>
            <a href="./profile.php" class="w-100 btn btn-outline-dark border-0 mt-4 text-start text-decoration-none text-white fs-3">
                <p class="d-none d-lg-inline "> Profile </p>
                <i class="bi bi-person-circle"></i>
            </a>
        </li>
        <li>
            <a href="./addPost.php" class="w-100 btn btn-outline-dark border-0 mt-4 text-start text-decoration-none text-white fs-3">
                <p class="d-none d-lg-inline "> Add Post </p>
                <i class="bi bi-plus-square"></i>
            </a>
        </li>
        <li>
            <a href="./settings.php" class="w-100 btn btn-outline-dark border-0 mt-4 text-start text-decoration-none text-white fs-3">
                <p class="d-none d-lg-inline "> Settings </p>
                <i class="bi bi-gear"></i>
            </a>
        </li>
<!--        <li>-->
<!--            <a href="./addProfilePic.php" class="w-100 btn btn-outline-dark border-0 mt-4 text-start text-decoration-none text-white fs-3"> Update Profile Pic</a>-->
<!--        </li>-->
    </ul>
    <div class="dropup-start dropup w-100 d-none d-lg-block">
        <button class="btn btn-outline-dark text-white text-start w-100 border-0 btn-lg" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list"></i> More
        </button>
        <ul class="dropdown-menu dropdown-menu-dark">
            <button id="logOut" class="w-100 btn btn-outline-dark border-0 text-start text-white border-0 bg-transparent" >Log Out</button>
        </ul>
    </div>
</div>