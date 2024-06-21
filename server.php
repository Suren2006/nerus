<?php

class Model {
    public $db;
    
    public function __construct() {
        session_start();
        $this->db = new mysqli("localhost", "mysql", "mysql", "project");
//        $this->db = new mysqli("localhost", "suren222_222", "Sahakyan.2006", "suren222_project");
        if (isset($_POST['action'])) {
            call_user_func([$this,$_POST['action']]);
        }
    }
    
    public function registerForm() {
        // print_r(json_encode($_POST));
        
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); ;
        $errors = [];
        $_SESSION['errors'] = [];

        if(empty($first_name)) {
            $errors['first_name'] = "First Name is empty";
        }elseif(strlen($first_name) > 40) {
            $errors['first_name'] = "First Name is too long";
        }
        
        if(empty($last_name)) {
            $errors['last_name'] = "Last Name is empty";
        }elseif(strlen($last_name) > 40) {
            $errors['last_name'] = "Last Name is too long";
        }
        
        if(empty($email)) {
            $errors['email'] = "Email is empty";
        }elseif(strlen($email) > 255) {
            $errors['email'] = "Email is too long";
        }
        
        if(empty($password)) {
            $errors['password'] = "Password is empty";
        }elseif(strlen($password) < 8) {
            $errors['password'] = "Password is too small";
        }

        if(count($errors) > 0) {
            $_SESSION['errors'] = $errors;  
            print_r(json_encode(['errors' => $errors]));
        }else {   
            $this->db->query("
            INSERT INTO users (first_name, last_name, email, password)
            VALUES ('$first_name', '$last_name', '$email', '$password')
            ");
            print_r(json_encode(['success' => true]));
        }
    }
    
    public function userlogin() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $errors = [];
        $_SESSION['errors'] = [];
        $user_info = $this->db->query("SELECT * FROM users WHERE email = '$email'")->fetch_all(true);
        if(empty($user_info) || !password_verify($password, $user_info[0]['password'])) {
            $errors['loginError'] = 'Invalid Email or Password';
            $_SESSION['errors'] = $errors;
            print_r(json_encode(['errors' => $errors]));
        }else {
            $_SESSION['user'] = $user_info[0];
            print_r(json_encode(['success' => $user_info[0]['id']]));
        }
    }

    public function ifremembered() {
        $id = $_POST['id'];
        $user = $this->db->query("SELECT * FROM users WHERE id = $id")->fetch_all(true);
        $_SESSION['user'] = $user[0];
        if (!empty($user)) {
            print_r(json_encode(['success' => true]));
        }
    }

    public function logOut() {
        unset($_SESSION['user']);
        print('success');
    }

    public function updateInfo() {
        $id = $_SESSION['user']["id"];
        $new_first_name = ($_POST['first_name'] == "") ?  $_SESSION['user']['first_name'] :$_POST['first_name'];
        $new_last_name = ($_POST['last_name'] == "") ? $_SESSION['user']['last_name'] : $_POST['last_name'];
        $new_age = ($_POST['age'] == "") ?  $_SESSION['user']['age'] : $_POST['age'];
        $new_desc = ($_POST['description'] == "") ? $_SESSION['user']['description'] : $_POST['description'];
    
        $insert =  $this->db->query("UPDATE `users` SET 
        first_name= '$new_first_name',
        last_name= '$new_last_name',
        age= '$new_age',
        description= '$new_desc'
        WHERE id = $id");
        $_SESSION['user']['first_name'] = $new_first_name;
        $_SESSION['user']['last_name'] = $new_last_name;
        $_SESSION['user']['age'] = $new_age;
        $_SESSION['user']['description'] = $new_desc;
        
        print_r(json_encode($insert));
    }

    public function update_profile_pic() {
        $id = $_SESSION['user']["id"];

        $statusMsg = '';
        $targetDir = "profile_pics/";
        $fileName = basename($_FILES["img"]["name"]);
        $targetFilePath = "profile_pics/" . $id . "/" . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        
        if (!file_exists("profile_pics/" . $id)) {
            mkdir("profile_pics/" . $id . "/");
        }

        if(!empty($_FILES["img"]["name"])){
            $allowTypes = array('jpg','png','jpeg',);
            if(in_array($fileType, $allowTypes)){
                if(move_uploaded_file($_FILES["img"]["tmp_name"], $targetFilePath)){
                    $insert =  $this->db->query("UPDATE users SET 
                        profile_picture = '$targetFilePath'
                        WHERE id = '$id'");
                    $_SESSION['user']['profile_picture'] = $targetFilePath;
                    if($insert){
                        $statusMsg = "The profile picture has been uploaded successfully.";
                    }else{
                        $statusMsg = "File upload failed, please try again.";
                    } 
                }else{
                    $statusMsg = "Sorry, there was an error uploading your file.";
                }
            }else{
                $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
            }
        }else{
            $statusMsg = 'Please select a file to upload.';
        }
        print_r($statusMsg);
    }

    public function deleteUser() {
        $id = $_SESSION['user']['id'];
        $this->db->query("DELETE FROM users WHERE id = '$id'");
        $this->db->query("DELETE FROM requests WHERE from_id = '$id'");
        $this->db->query("DELETE FROM requests WHERE to_id = '$id'");
        unset($_SESSION['user']);
        header("Location: /index.php");
    }

    public function deleteprofilepic() {
        $url = $_SESSION['user']['profile_picture'];
        $id = $_SESSION['user']['id'];
        if ($url !== null) {
            unlink($url);
            $this->db->query("UPDATE users SET profile_picture = '' WHERE id = $id");
        }
        $_SESSION['user']['profile_picture'] = "";
    }
    public function users() {
        $users = $this->db->query("SELECT * FROM users")->fetch_all(true);
        return $users;
    }

    public function requests() {
        $user_id = $_SESSION['user']['id'];
        $requests = $this->db->query("SELECT * FROM `requests` WHERE from_user = $user_id")->fetch_all(true);
        $following = [];
        foreach($requests as $key => $value) {
            array_push($following, $value['to_user']);
        }
        return $following;
    }

    public function followers() {
        $user_id = $_SESSION['user']['id'];
        $requests = $this->db->query("SELECT * FROM `requests` WHERE to_user = $user_id")->fetch_all(true);
        $following = [];
        foreach($requests as $key => $value) {
            $following[] = $value['from_user'];
        }
        return $following; 
    }

    public function countOfFollowers() {
        $user_id = $_SESSION['user']['id'];
        $followers = $this->db->query("SELECT * FROM `requests` WHERE to_user = $user_id")->fetch_all(true);
        return count($followers);
    }
    
    public function countOfFollowings() {
        $user_id = $_SESSION['user']['id'];
        $following = $this->db->query("SELECT * FROM `requests` WHERE from_user = $user_id")->fetch_all(true);
        return count($following);
    }

    public function follow() {
        $from = $_SESSION['user']['id'];
        $to = $_POST['user_id'];
        $follow = $this->db->query("SELECT * FROM `requests` WHERE from_user = $from AND to_user = $to")->fetch_all(true);
        $path = "/{$_POST['path']}.php";
        if (empty($follow)) {
            $this->db->query("INSERT INTO requests (from_user, to_user) VALUES ('$from', '$to')");
        }else {
            $this->db->query("DELETE FROM requests WHERE from_user = $from AND to_user = $to");
        }
        
        header("Location: " . $_SERVER['HTTP_REFERER'] );
    }

    public function get_url() {
        
        print_r($_FILES["img"]["tmp_name"]);
    }

    public function addPost() {
        $user_id = $_SESSION['user']['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        
        $statusMsg = '';
        $fileName = basename($_FILES["img"]["name"]);
        $targetFilePath = "posts/" . $user_id . "/" . time() . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        if (!file_exists("posts/" . $user_id)) {
            mkdir("posts/" . $user_id . "/");
        }
        
        if(!empty($_FILES["img"]["name"])){
            $allowTypes = array('jpg','png','jpeg','gif','pdf');
            if(in_array($fileType, $allowTypes)){
                if(move_uploaded_file($_FILES["img"]["tmp_name"], $targetFilePath)){
                    $insert = $this->db->query("INSERT into posts (user_id, title, img_url, content, uploaded_on) VALUES ('$user_id', '$title', '$targetFilePath', '$content', NOW())");
                    if($insert){
                        $statusMsg = "The post has been uploaded successfully.";
                    }else{
                        $statusMsg = "File upload failed, please try again.";
                    } 
                }else{
                    $statusMsg = "Sorry, there was an error uploading your file.";
                }
            }else{
                $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
            }
        }else{
            $statusMsg = 'Please select a file to upload.';
        }
        print_r($statusMsg);
    }

    public function delete_post() {
        $id = $_POST['post_id'];
        $url = $_POST['post_url'];
        unlink($url);
        $this->db->query("DELETE FROM posts WHERE id = $id");
        $this->db->query("DELETE FROM likes WHERE post_id = $id");
        header("Location: ./profile.php");
    }

    public function like_function(){
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];
        $like = $this->db->query("SELECT * FROM likes WHERE post_id = $post_id AND user_id = $user_id")->fetch_all(true);
        if (empty($like)) {
            $this->db->query("INSERT INTO likes (post_id, user_id) VALUES ($post_id, $user_id)");
        }else {
            $this->db->query("DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id");
        }


    }


    public function savefunction(){
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];
        $save = $this->db->query("SELECT * FROM saves WHERE post_id = $post_id AND user_id = $user_id")->fetch_all(true);
        if (empty($save)) {
            $this->db->query("INSERT INTO saves (user_id, post_id) VALUES ('$user_id', '$post_id')");
            print('Saved');
        }else {
            $this->db->query("DELETE FROM saves WHERE user_id = $user_id and post_id = $post_id");
            print("Unsaved");
        }

    }
 }

$k = new Model();

?>