<?php
    recaptcha();
    
    function recaptcha() {
        $secret = "CAPTCHA_SECRET_HERE";
        $captcha = $_POST["token"];

        $options = array(
            "http" => array(
                "header" => "Content-type: application/x-www-form-urlencoded",
                "method" => "POST",
                "content" => "secret=$secret&response=$captcha"
            )
        );

        $context = stream_context_create($options);
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify", false, $context);
        $result = json_decode($response, true);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($result["success"]) {
                success();
            }
            else {
                $send = array("result" => "FAIL_RECAPTCHA");
                echo json_encode($send);
            }
        }
    }

    function success() {
        header("Category-type: application/json");

        $email = $_POST["email"];
        $pass = $_POST["pass"];

        if ($email == "") {
            $send = array("result" => "EMPTY_EMAIL");
            echo json_encode($send);
            return;
        }

        if ($pass == "") {
            $send = array("result" => "EMPTY_PASS");
            echo json_encode($send);
            return;
        }

        if (strlen($pass) < 8) {
            $send = array("result" => "SHORT_PASS");
            echo json_encode($send);
            return;
        }

        $invalid = "/[\"' ]/";

        if (preg_match($invalid, $email)) {
            $send = array("result" => "INVALID_EMAIL");
            echo json_encode($send);
            return;
        }

        if (preg_match($invalid, $pass)) {
            $send = array("result" => "INVALID_PASS");
            echo json_encode($send);
            return;
        }

        $db_addr = "localhost";
        $db_user = "root";
        $db_pass = "D00gl3b3rry"; 
        $db_name = "share_some_games";

        $db_conn = new mysqli($db_addr, $db_user, $db_pass, $db_name);
        if ($db_conn) {
            $query = $db_conn -> query("SELECT email FROM accounts WHERE email=\"$email\"");
            $query = $query -> fetch_all(MYSQLI_ASSOC);
            if (count($query) == 0) {
                $query = $db_conn -> query("INSERT INTO accounts (email, pass, user) VALUES (\"$email\", \"" . password_hash($pass, PASSWORD_BCRYPT) . "\", \"User_" . rand(10000, 99999) . "\")");
                $session = bin2hex(random_bytes(32));
                $expires = time() + (86400 * 90);
                $query = $db_conn -> query("INSERT INTO sessions (session, email, expires) VALUES (\"$session\", \"$email\", $expires)");
                $send = array("result" => "ACCOUNT_CREATED", "session" => $session);
                echo json_encode($send);
            }
            else {
                $send = array("result" => "ACCOUNT_EXISTS");
                echo json_encode($send);
            }
        }
        else {
            $send = array("result" => "CONNECT_FAILED");
            echo json_encode($send);
        }
    }
?>