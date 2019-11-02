var NAME_REGEX = /^[a-zA-Z]{2,}$/;
var EMAIL_REGEX = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
var PHONE_NUMBER_REGEX = /^(01|03|05|06|07|08|09|70|71|76|78)[0-9]{6}$/;
var PASSWORD_REGEX = /^[a-zA-Z0-9]{8,}$/;

$(function () {
    $("#loginform").submit(login);
    $("#btn-forgotpassword").click(forgotpassword);
    $("#btn-signup").click(signup);

    $("#signupform input").keyup(validateSignUp);
    $("#signupform input").blur(validateSignUp);

    $("#file-btn").on("change", fileUploaded);

    activateSearch();
});

$(document).ajaxStart(function () {
    $("#loader").css("display", "block");
});

$(document).ajaxComplete(function () {
    $("#loader").css("display", "none");
});


function login(e) {
    e.preventDefault();
    var email = $("#login-email").val();
    var password = $("#login-password").val();
    var remember = $("#login-remember").is(":checked") ? "remember" : "";
    if (emptyStr(email, password)) {
        return;
    }
    $.post(
        "GetInfo.php", {
            "email": email,
            "password": password,
            "remember": remember
        },
        function (msg) {
            msg = msg.trim();
            if (msg == "") {
                $(location).attr('href', 'home');
                clearFields("");
            } else {
                $("#login-alert").html(msg + "<br />");
            }
        }
    );
    clearPasswords();
}

function forgotpassword() {
    var email = $("#forgot-email").val();
    var confirmEmail = $("#confirm-email").val();
    if (emptyStr(email, confirmEmail)) {
        return;
    }
    $.post(
        "GetInfo.php", {
            "user_email": email,
            "confirm_email": confirmEmail
        },
        function (msg) {
            msg = msg.trim();
            if (msg == 1) {
                $("#forgot-alert").html("An email has been sent to your inbox to reset your password!");
                clearFields("");
            } else {
                $("#forgot-alert").html(msg + "<br />");
            }
        }
    );
}

function signup() {
    var email = $("#signup-email").val();
    var password = $("#signup-password").val();
    var confirmPassword = $("#signup-confirm-password").val();
    var firstName = $("#signup-first-name").val();
    var lastName = $("#signup-last-name").val();
    var phoneNumber = $("#signup-phone-number").val();
    if (emptyStr(email, password, confirmPassword, firstName, lastName)) {
        return;
    }
    $.ajax({
        type: "post",
        url: "GetInfo.php",
        data: {
            "user_email": email,
            "user_password": password,
            "confirm_password": confirmPassword,
            "first_name": firstName,
            "last_name": lastName,
            "phone_number": phoneNumber
        },
        success: function (msg) {
            msg = msg.trim();
            if (msg == true) {
                alert("New account successfully created!\n An email was sent to your inbox to activate your account!");
                display("#loginbox");
                $("#login-email").val(email.toLowerCase().trim());
                clearFields("");
            } else {
                $("#signup-alert").html(msg + "<br />");
            }
        }
    });
    clearPasswords();
}

function hideAll() {
    $('#loginbox').fadeOut(500);
    $('#signupbox').fadeOut(500);
    $('#forgotpasswordbox').fadeOut(500);
}

function display(box) {
    $("#loginbox").fadeOut(500);
    $("#signupbox").fadeOut(500);
    $("#forgotpasswordbox").fadeOut(500);
    $(box).fadeIn(500);
    clearFields(box);
    clearDetails(box);
    clearValid();
}

function changeView(oldView, newView) {
    $(oldView).slideUp();
    $(newView).slideDown(500);
    clearFields(newView);
    clearDetails(newView);
    clearValid();
}

function clearFields(view) {
    if (view != "#signupbox") {
        $("#signupform input").each(function (idx, e) {
            $(e).val("");
        });
    }
    if (view != "#forgotpasswordbox") {
        $("#forgotform input").each(function (idx, e) {
            $(e).val("");
        });
    }
    if (view != "#loginbox") {
        $("#loginform #login-password").val("");
        if ($("#login-email").val() !== "" && $("#login-remember").is(":checked")) {

        } else {
            $("#login-email").val("");
        }
    }
}

function clearDetails(view) {
    if (view != "#signupbox") {
        $("#signup-alert").html("");
        clearValid();
    }
    if (view != "#forgotpasswordbox") {
        $("#forgot-alert").html("");
    }
    if (view != "#loginbox") {
        $("#login-alert").html("");
    }
}

function clearPasswords() {
    $("#signup-password").val("");
    $("#signup-confirm-password").val("");
    $("#login-password").val("");
}

function emptyStr() {
    for (var i = 0; i < arguments.length; i++) {
        if (arguments[i].length == 0) return true;
    }
    return false;
}

function getInitials(name) {
    var initials = "";
    var words = name.split(" ");
    for (var i = 0; i < words.length; i++) {
        initials += words[i].substring(0, 1);
    }
    return initials;
}

function fileUploaded() {
    $(this).parent().addClass("upload-success");
}

function activateSearch() {
    $("#categories li").click(function () {
        var t = $(this).text().replace(/\s+/g, "+").toLowerCase();
        $(location).attr("href", "home?category=" + t);
    });

    $("#brands li").click(function () {
        var t = $(this).text().replace(/\s+/g, "+").toLowerCase();
        $(location).attr("href", "home?brand=" + t);
    });
}

function validateSignUp() {
    var $field = $("#" + this.id);
    $field.css("border-width", "2px");
    var v = $field.val();
    if (this.name === "first_name") {
        if (NAME_REGEX.test(v)) {
            $field.css('border-color', 'green');
        } else {
            $field.css('border-color', 'red');
        }
    } else if (this.name === "last_name") {
        if (NAME_REGEX.test(v)) {
            $field.css('border-color', 'green');
        } else {
            $field.css('border-color', 'red');
        }
    } else if (this.name === "user_email") {
        if (EMAIL_REGEX.test(v)) {
            $field.css('border-color', 'green');
        } else {
            $field.css('border-color', 'red');
        }
    } else if (this.name === "phone_number") {
        if (v === "") {
            return;
        }
        if (PHONE_NUMBER_REGEX.test(v)) {
            $field.css('border-color', 'green');
        } else {
            $field.css('border-color', 'red');
        }
    } else if (this.name === "user_password") {
        var $field1 = $("#" + this.id);
        var $field2 = $("#signup-confirm-password");
        if (PASSWORD_REGEX.test($field1.val())) {
            $field1.css('border-color', 'green');
        } else {
            $field1.css('border-color', 'red');
        }
        if ($field1.val() != $field2.val() && $field2.val() != "") {
            $field2.css('border-color', 'red');
        }
    } else {
        var $field1 = $("#" + this.id);
        var $field2 = $("#signup-password");
        if (PASSWORD_REGEX.test($field1.val()) && $field2.val() === $field1.val()) {
            $field1.css('border-color', 'green');
        } else {
            $field1.css('border-color', 'red');
        }
    }
}

function clearValid() {
    $("#signupform input").css("border-width", "1px");
    $("#signupform input").css("border-color", "initial");
}

function displayMessages(msgs) {
    var $msgsArea = $("#msgs-area");
    $msgsArea.empty();
    msgs = jQuery.parseJSON(msgs);
    for (var i = 0; i < msgs.length; i++) {
        var msg = msgs[i];
        var $p = $("<p>");
        $p.addClass("msg");
        if (msg.folder === "Inbox") {
            $p.addClass("other");
        } else {
            $p.addClass("me");
        }
        $p.text(msg.msg_text);
        $msgsArea.append($p);
    }
}

function displayContacts(contacts) {
    var $contactsArea = $("#contacts-area");
    $contactsArea.empty();
    contacts = jQuery.parseJSON(contacts);
    for (var i = 0; i < contacts.length; i++) {
        var contact = contacts[i];
        var $form = $("<form>");
        $form.attr("action", "messages");
        $form.attr("method", "post");

        var $input = $("<input>");
        $input.attr("type", "hidden");
        $input.attr("name", "oid");
        $input.val(contact.oid);

        $form.append($input);

        var $div = $("<div>");
        $div.addClass("window-widget");
        $div.addClass("contact");

        var $d2 = $("<div>");
        $d2.addClass("contact-circle");
        $d2.text(getInitials(contact.oname));
        var $h = $("<h4>");
        $h.text(contact.oname);

        $div.append($d2);
        $div.append($h);

        $form.append($div);

        $div.click(function () {
            $(this).parent().submit();
        });

        $contactsArea.append($form);
    }
}

function confirmDeleteAccount() {
    return window.confirm("Are you sure you completely want to delete your account?");
}
