<?php
session_start();
include 'includes/header.php';
include 'includes/footer.php';
?>

<?php
if (isset($_SESSION['logged_in'])) {
    $loc = 'Location: '.$path.'/index.php';
    header($loc);
}
?>



    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Login
            </div>
            <div class="panel-body">
                <form action="login.php" method="post">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" name='username' class="form-control" placeholder="" required>
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="username">Password</label>
                        <input type="password" name='password' class="form-control" placeholder="" required>
                    </div>

                    <br/>
                    <div class="input-group">
                        <label for="" style="padding-right: 10px"> Account Type : </label>
                        <label class="radio-inline"><input type="radio" checked="checked" name="type" value="teacher">Teacher</label>
                        <label class="radio-inline"><input type="radio" name="type" value="student">Student</label>
                        <label class="radio-inline"><input type="radio" name="type" value="parent">Parent</label>
                        <label class="radio-inline"><input type="radio" name="type" value="admin">Administrator</label>
                    </div>

                    <hr/>
                    <button class="btn btn-primary" name="login_user" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>


<?php
if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $account_type = $_POST['type'];
    $username = stripslashes($username);
    $username = mysqli_real_escape_string($conn, $username);

    $password = stripslashes($password);
    $password = mysqli_real_escape_string($conn, $password);

    if ($account_type == 'student') {

        $query = sprintf("SELECT * FROM STUDENTS WHERE username = '%s' and password = '%s'", $username, $password);
        $raw_results = mysqli_query($conn, $query);
        if ($raw_results == false) {
            die(formattedMessage(DIE_MSG,1));
        } else {
            if (mysqli_num_rows($raw_results) == 1) {
                $results = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
                echo formattedMessage("Login Successfully!", 2);
                $_SESSION['student_logged_in'] = 1;
                $_SESSION['logged_in'] = 1;
                $_SESSION['username'] = $results[0]['username'];
                $_SESSION['student_username'] = $results[0]['username'];
                $_SESSION['student_ssn'] = $results[0]['child_ssn'];
                redirect_to('index.php', 1000);
            } else {
                echo formattedMessage("Oops! Wrong Username/Password combination!", 1);
            }
        }

    } else if ($account_type == 'teacher') {

        $query = sprintf("SELECT * FROM Employees WHERE username = '%s' and password = '%s'", $username, $password);
        $raw_results = mysqli_query($conn, $query);
        if ($raw_results == false) {
            die(formattedMessage(DIE_MSG,1));
        } else {
            if (mysqli_num_rows($raw_results) == 1) {
                $results = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);

                mysqli_free_result($raw_results);
                mysqli_next_result($conn);

                $query = sprintf("SELECT * FROM Teachers WHERE teacher_username = '%s'", $username);
                $raw_results = mysqli_query($conn, $query);

                if ($raw_results == false) {
                    die(formattedMessage(DIE_MSG,1));
                } else {
                    if (mysqli_num_rows($raw_results) == 1) {
                        echo formattedMessage("Login Successfully!", 2);
                        $_SESSION['logged_in'] = 1;
                        $_SESSION['teacher_logged_in'] = 1;
                        $_SESSION['username'] = $results[0]['username'];
                        $_SESSION['teacher_username'] = $results[0]['username'];
                        redirect_to('index.php', 1000);
                    } else {
                        echo formattedMessage("Oops! Wrong Username/Password/Type combination!", 1);
                    }
                }

            } else {
                echo formattedMessage("Oops! Wrong Username/Password/Type combination!", 1);
            }
        }

    } else if ($account_type == 'parent') {

        $query = sprintf("SELECT * FROM Parents WHERE username = '%s' and password = '%s'", $username, $password);
        $raw_results = mysqli_query($conn, $query);
        if ($raw_results == false) {
            die(formattedMessage(DIE_MSG,1));
        } else {
            if (mysqli_num_rows($raw_results) == 1) {
                $results = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);
                echo formattedMessage("Login Successfully!", 2);
                $_SESSION['logged_in'] = 1;
                $_SESSION['parent_logged_in'] = 1;
                $_SESSION['username'] = $results[0]['username'];
                $_SESSION['parent_username'] = $results[0]['username'];
                redirect_to('index.php', 1000);
            } else {
                echo formattedMessage("Oops! Wrong Username/Password/Type combination!", 1);
            }
        }

    } else {
        $query = sprintf("SELECT * FROM Employees WHERE username = '%s' and password = '%s'", $username, $password);
        $raw_results = mysqli_query($conn, $query);
        if ($raw_results == false) {
            die(formattedMessage(DIE_MSG,1));
        } else {
            if (mysqli_num_rows($raw_results) == 1) {
                $results = mysqli_fetch_all($raw_results, MYSQLI_ASSOC);

                mysqli_free_result($raw_results);
                mysqli_next_result($conn);

                $query = sprintf("SELECT * FROM Administrators WHERE employee_username = '%s'", $username);
                $raw_results = mysqli_query($conn, $query);
                if ($raw_results == false) {
                    die(formattedMessage(DIE_MSG,1));
                } else {
                    if (mysqli_num_rows($raw_results) == 1) {
                        echo formattedMessage("Login Successfully!", 2);
                        $_SESSION['logged_in'] = 1;
                        $_SESSION['admin_logged_in'] = 1;
                        $_SESSION['username'] = $results[0]['username'];
                        $_SESSION['admin_username'] = $results[0]['username'];
                        redirect_to('index.php', 1000);
                    } else {
                        echo formattedMessage("Oops! Wrong Username/Password/Type combination!", 1);
                    }
                }
            }
        }
    }


}
?>