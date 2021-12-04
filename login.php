<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="text/javascript" href="scripts/showpass.js">

<body>

    <div class="split left"></div>
    <div class="split right">
        <div class="login">
            <form name="login" method="POST" action="#" autocomplete="off">
                <div class="outer">
                    <table border="1" id="login">
                        <th colspan="2" style="background-color: aqua ;color:black;">Login Form</th>
                        <tr>
                            <td align="right">User ID:</td>
                            <td align="left"><input type="text" name="userid" placeholder="User ID" id="username"></input></td>
                        </tr>
                        <tr>
                            <td align="right">Password:</td>
                            <td align="left"><input type="password" name="password" placeholder="Password" id="password"></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><input type="checkbox" style="width:15px; vertical-align:middle" onclick="showpass()">Show Password</td>
                        </tr>
                        <tr>
                            <td align="right">Select Company Name:</td>
                            <td align="left"><input list="browsers" name="company" id="company" placeholder="Enter Name or Select Company" value="">

                                <datalist id="browsers">
                                    <?php
                                    require_once("../includes/connect.php");
                                    $sql = "SELECT * FROM `companies` ORDER BY `id` ASC;";
                                    $result = mysqli_query($con, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo ('<option Value="' . ($row['companyName']) . '">' . $row['companyName'] . '</option>');
                                    }
                                    ?>
                                </datalist>
                            </td>
                        </tr>
                    </table>
                </div>
                <button onclick="return validate()" style="cursor:pointer; background-color:blue; color:aliceblue; width:100px; border-radius:10px;padding:10px;margin-right:20px;" type="submit">Login</button>
            </form>
            <div>
                <button onclick="forget()" style="cursor:pointer; background-color:red; color:aliceblue; width:150px; border-radius:10px;padding:10px;margin-bottom:20px;">Forget Password</button>
            </div>
        </div>
        <script>
            function forget() {
                location.replace("forgetPassword.php");
            }

            function validate() {
                var id = document.getElementById("username").value;
                var password = document.getElementById("password").value;
                var company = document.getElementById("company").value;
                if (id == null || id == "") {
                    alert('User ID Must Be Entered');
                    return false;
                } else if (password == null || password == "") {
                    alert('Password Must Be Entered');
                    return false;
                } else if (company == null || company == "") {
                    alert('Company Must Be Entered');
                    return false;
                }
            }

            function showpass() {
                var x = document.getElementById("password");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
        </script>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $userid = $_POST['userid'];
            $password = sha1($_POST['password']);
            $company = $_POST['company'];
            include_once("../includes/connect.php");
            $sql = "SELECT * FROM `companies` WHERE `companyName`='$company'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_num_rows($result);
            if ($row > 0) {
                $con = mysqli_connect($host, $uname, $pass, $company);
                $sql = "SELECT * FROM `users` WHERE `userid`='$userid' and `password`='$password'";
                $result = mysqli_query($con, $sql);
                $row = mysqli_num_rows($result);
                if ($row > 0) {
                    $row=mysqli_fetch_assoc($result);
                    $username=$row['username'];
                    session_start();
                    $_SESSION['username']=$username;
                    $_SESSION['userid']=$userid;
                    $_SESSION['loggedin']=true;
                    $_SESSION['company']=$company;

                    header("location:index.php");
                } else echo('<script>alert("Invalid User ID or Password");</script>');
            } else echo('<script>alert("Company Name Not Valid, Select From List Only");</script>');
        }
        ?>
    </div>
</body>
</head>