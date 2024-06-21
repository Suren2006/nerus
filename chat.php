<?php
require './server.php';
    session_start();


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}


$chat_id = $_GET['chat_id'];
$main_user_id = $_SESSION['user']['id'];
// $main_messages = $k->db->query("SELECT * FROM chat WHERE from_user = '$main_user_id' AND to_user = '$user_id' ORDER BY send_on")->fetch_all(true);
// $messages = $k->db->query("SELECT * FROM chat WHERE from_user = '$user_id' AND to_user = '$main_user_id' ORDER BY send_on")->fetch_all(true);
$all_messages = $k->db->query("SELECT * FROM `chat` WHERE chat_id = $chat_id ORDER BY send_on")->fetch_all(true);
$user_id = $k->db->query("SELECT to_user FROM chat WHERE (from_user = $main_user_id AND chat_id = $chat_id)")->fetch_all(true)[0]['to_user'];
$user = $k->db->query("SELECT * FROM users WHERE id = $user_id")->fetch_all(true);
// echo "<pre>";
// print_r($all_messages);die;
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
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-black">
<div class="container-fluid d-flex justify-content-end align-items-start h-100">
  <?php include './aside.php'; ?>

    <div class="col-10 h-100">
        <div class="w-100 h-100 p-5 d-flex justify-content-between align-items-center flex-column">
            <div class="w-100">
                    <div class="d-flex justify-content-start align-items-center gap-3 pb-3 border-bottom">
                        <div class="rounded-circle text-center overflow-hidden d-flex justify-content-center align-items-center" style=" width: 75px;aspect-ratio: 1">
                            <img src="<?= $user[0]['profile_pic'] ?? './img/profile.png'; ?>" class="w-100 h-100 object-fit-cover">
                        </div>
                        <h4 class="m-0 text-white "><?= $user[0]['first_name'] . " " . $user[0]['last_name'] ?></h4>
                    </div>
            </div>
            <div id="chatBox" class="text-white w-100 m-5 h-100 overflow-y-scroll">
                <?php foreach($all_messages as $message) { ?>
                    <div class="w-100 d-flex">
                        <h3 class="ms-auto btn btn-<?=($message['from_user'] == $main_user_id) ? "primary" : "secondary" ?>">
                            <?= $message['message'] ?>
                        </h3>
                    </div>
                <?php } ?>
            </div>
            <form class="w-100 d-flex justify-content-end align-items-center" id="sendmessageForm">
                <input type="text" id="messageInput" placeholder="Type..." autocomplete="off" class="w-100 form-control text-white  border-info bg-transparent rounded-3" autofocus>
                <button type="submit" class="bg-transparent border-0 position-absolute text-white pe-3" type="submit">Send</button>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="/socket.io/socket.io.js"></script>
    <script> 
        const socket = io();

        socket.on('message', (message) => {
            const messages = document.querySelector('#messages');
            const messageElement = document.createElement('li');
            messageElement.textContent = message;
            messages.appendChild(messageElement);
        });   

        $('#sendmessageForm').submit( function (e) {
            e.preventDefault()
            const messageInput = document.getElementById("messageInput");
            const message = messageInput.value;
            const from_user_id = <?= $main_user_id ?> ;
            const to_user_id = <?= $user_id ?> ;
            socket.emit('message', [message,from_user_id,to_user_id]);
            messageInput.value = "";
        })
         function scrollChatBox() {
                const chatbox = document.querySelector('#chatBox');
                chatbox.scrollTop = chatbox.scrollHeight
         }

         scrollChatBox();

    </script>


</body>
</html>
