<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>eStore | Reset Password</title>
    <link rel="icon" href="img/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    <!--<script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>-->
</head>

<body>
<div class="container">
    <div id="forgotpasswordbox" style="margin-top: 180px;" class="mainbox col-md-6 col-md-offset-3">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="panel-title">Reset Password</div>
            </div>
            <div style="padding-top: 10px" class="panel-body">
                <div id="reset-alert" style="margin-bottom: 10px" class="text-danger col-sm-12">
                    <?php
                    require_once("php/classes/InfoManager.php");
                    if (isset($_GET["id"], $_GET["code"])) {
                        $id = $_GET["id"];
                        $code = $_GET["code"];
                        if (isValidCode($code) && checkCodeAndId($id, $code) === true) {
                            if (isset($_POST["new_password"], $_POST["confirm_new_password"])) {
                                $new_password = $_POST["new_password"];
                                $confirm_password = $_POST["confirm_new_password"];
                                $checkPass = checkPassword($new_password, $confirm_password);
                                if ($checkPass === true) {
                                    if (changePassword($id, $new_password)) {
                                        echo "Password successfully changed!<br />" . $redirect_script;
                                        activateAccount($id);
                                    } else {
                                        echo UPDATE_FAIL_ERR;
                                    }
                                } else {
                                    echo $checkPass;
                                }
                            }
                        } else {
                            echo INVALID_INFO;
                        }
                    }
                    ?>
                </div>
                <form method="post" action="<?php echo getPageURI(); ?>" class="form-horizontal">
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" class="form-control" name="new_password" placeholder="New Password">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" class="form-control" name="confirm_new_password" placeholder="Confirm New Password">
                    </div>
                    <div style="margin-top: 10px" class="form-group">
                        <div class="col-md-offset-3 col-md-6 controls">
                            <button type="submit" class="btn btn-block btn-danger">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>
