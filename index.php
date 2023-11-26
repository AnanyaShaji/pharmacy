<?php
error_reporting(1);
session_start();
include("dbcon.php");

if (isset($_SESSION['user_session'])) {
    $invoice_number = "CA-" . invoice_number();
    header("location:home.php?invoice_number=$invoice_number");
}

if (isset($_POST['submit'])) {
    // Validate username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_msg = "<center><font color='red'>Both username and password are required</font></center>";
    } else {
        $password = sha1($password);

        $select_sql = "SELECT * FROM users ";
        $select_query = mysqli_query($con, $select_sql);

        if ($select_query) {
            $login_successful = false;

            while ($row = mysqli_fetch_array($select_query)) {
                $s_username = $row['user_name'];
                $s_password = $row['password'];

                if ($s_username == $username && $s_password == $password) {
                    $login_successful = true;
                    break;
                }
            }

            if ($login_successful) {
                $_SESSION['user_session'] = $s_username;
                $invoice_number = "CA-" . invoice_number();
                header("location:home.php?invoice_number=$invoice_number");
            } else {
                $error_msg = "<center><font color='red'>Login Failed. Please check your username and password.</font></center>";
            }
        } else {
            $error_msg = "<center><font color='red'>Database error. Please try again later.</font></center>";
        }
    }
}

function invoice_number()
{
    $chars = "09302909209300923";
    srand((double) microtime() * 1000000);
    $i = 1;
    $pass = '';

    while ($i <= 7) {
        $num  = rand() % 10;
        $tmp  = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SPMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css">
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <style>
        body {
            /* Set the background image path */
            background-image: url('img.webp'); /* Adjust the path to your image */
            background-size: cover; /* Cover the entire background */
            background-repeat: no-repeat; /* Do not repeat the image */
            margin: 0; /* Remove default body margin */
        }
    </style>
</head>
<body>

<center><h1>MedShop</h1></center>

<div class="content" style="width: 400px">
    <form method="POST">
        <table class="table table-bordered table-responsive ">
            <tr>
                <td><H5><label for="username">Username</label></H5></td>
                <td><input type="text" autocomplete="off" name="username" class="form-group" required></td>
            </tr>
            <tr>
                <td><H5><label for="password">Password</label></H5></td>
                <td><input type="password" name="password" required></td>
            </tr>
            <input type="hidden" autocomplete="off" name="invoice_number" value="<?php echo 'CA-' . invoice_number() ?>">
        </table>

        <input type="submit" name="submit" class="btn btn-success btn-large" value="Login">
        <?php echo $error_msg; ?>
    </form>
</div>

</body>
</html>
