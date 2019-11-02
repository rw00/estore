<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title>Welcome to eStore</title>
    <link rel="icon" href="img/favicon.ico"/>
    <link rel="stylesheet" href="css/default.css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body>
<?php
require_once("php/classes/InfoManager.php");
if (isLoggedIn()) {
    header("Location: home");
    exit;
}
if (isset($_POST["activate"], $_POST["user_id"])) {
    accountActivation($_POST["user_id"]);
    echo "<script>alert('An activation email was sent to your inbox.');</script>";
}
?>
<div id="loader" class="loading-img"></div>
<ul class="slideshow">
    <li><span>Image 1</span></li>
    <li><span>Image 2</span></li>
    <li><span>Image 3</span></li>
    <li><span>Image 4</span></li>
    <li><span>Image 5</span></li>
    <li><span>Image 6</span></li>
    <li><span>Image 7</span></li>
    <li><span>Image 8</span></li>
</ul>
<div id="container">
    <div id="heading">
        <a class="btn-link" onclick="hideAll();"><h1>Welcome Back</h1></a>
    </div>
    <div id="content">

        <a id="login" class="btn btn-lg btn-danger" onclick="display('#loginbox')">Login</a>
        <br/>
        <br/>
        <a id="signup" class="btn btn-lg btn-danger" onclick="display('#signupbox')">Sign Up</a>
    </div>
    <div id="footer">
        <h4> eStore: The Leading Electronics Online Store </h4>
        <h6> &copy; 2015 eStore </h6>
    </div>
</div>
<div class="container">
    <div id="loginbox" style="display: none; margin-top: 150px;" class="mainbox col-md-6 col-md-offset-3">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="panel-title">Sign In</div>
                <div class="pull-right small" style="margin-top: -10px"><a class="btn-link" onclick="changeView('#loginbox', '#forgotpasswordbox')">Forgot Password?</a></div>
            </div>
            <div style="padding-top: 15px" class="panel-body">
                <div id="login-alert" class="text-danger col-sm-12"></div>
                <form id="loginform" action="index" method="post" class="form-horizontal">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="login-email" name="email" type="text" class="form-control" value="<?= getRememberedEmail() ?>" placeholder="Email">
                    </div>
                    <div style="margin-top: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="login-password" name="password" type="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group" style="margin-top: 15px; margin-left: 22px">
                        <div id="remember-me" class="checkbox">

                            <input name="remember" id="login-remember" type="checkbox" <?= remembered(); ?> />
                            <label style="margin-left: -10px;" for="login-remember"> Remember Me</label>

                        </div>
                    </div>
                    <div style="margin-top: 10px" class="form-group">
                        <div class="col-md-offset-3 col-md-6">
                            <button id="btn-login" type="submit" class="btn btn-block btn-danger">Login</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div style="border-top: 1px solid #888; padding-top: 15px;">
                                Don't have an account?
                                <a class="btn-link" onclick="changeView('#loginbox', '#signupbox')">
                                    Sign Up Here
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="signupbox" style="display: none; margin-top: 85px" class="mainbox col-md-6 col-md-offset-3">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="panel-title">Sign Up</div>
                <div class="pull-right small" style="margin-top: -10px"><a class="btn-link" onclick="changeView('#signupbox', '#loginbox')">Sign In</a></div>
            </div>
            <div style="padding-top: 15px" class="panel-body">
                <div id="signup-alert" class="text-danger col-sm-12"></div>
                <form id="signupform" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label">First Name*</label>
                        <div class="col-md-8">
                            <input id="signup-first-name" name="first_name" type="text" class="form-control" placeholder="First Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Last Name*</label>
                        <div class="col-md-8">
                            <input id="signup-last-name" name="last_name" type="text" class="form-control" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Email*</label>
                        <div class="col-md-8">
                            <input id="signup-email" name="user_email" type="email" class="form-control" placeholder="Email Address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Phone Number</label>
                        <div class="col-md-8">
                            <input id="signup-phone-number" name="phone_number" type="text" class="form-control" placeholder="Phone Number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Password*</label>
                        <div class="col-md-8">
                            <input id="signup-password" name="user_password" type="password" class="form-control" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Confirm Password*</label>
                        <div class="col-md-8">
                            <input id="signup-confirm-password" name="confirm_password" type="password" class="form-control" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-6">
                            <button id="btn-signup" type="button" class="btn btn-block btn-danger">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="forgotpasswordbox" style="display: none; margin-top: 180px;" class="mainbox col-md-offset-3 col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="panel-title">Forgot Password</div>
                <div class="pull-right small" style="margin-top: -10px"><a class="btn-link" onclick="changeView('#forgotpasswordbox', '#loginbox')">Sign In</a></div>
            </div>
            <div style="padding-top: 15px" class="panel-body">
                <div id="forgot-alert" class="text-danger col-sm-12"></div>
                <form id="forgotform" class="form-horizontal">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="forgot-email" type="text" class="form-control" name="user_email" placeholder="Email"/>
                    </div>
                    <div style="margin-top: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="confirm-email" type="text" class="form-control" name="confirm_email" placeholder="Confirm Email"/>
                    </div>
                    <div style="margin-top: 15px" class="form-group">
                        <div class="col-md-offset-3 col-md-6">
                            <button id="btn-forgotpassword" type="button" class="btn btn-block btn-danger">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php // include_once("views/alertbox.html"); ?>
</body>

</html>
