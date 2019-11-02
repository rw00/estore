var MIN_PASS_LEN = 8;

function isValidEmail(s) {
    var EMAIL_REGEX = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return EMAIL_REGEX.test(s);
}

function isValidName(s) {
    return /^[A-Za-z]+$/.test(s);
}

function isValidPhoneNumber(s) {
    s = s.trim();
    return /^[\d]{8,}$/.test(s);
}

function isValidPassword(s) {
    s = s.trim();
    if (s.length < MIN_PASS_LEN || s.indexOf(" ") > -1) {
        return false;
    }
    return true;
}

function fixName(s) {
    s = s.trim().toLowerCase();
    return s.charAt(0).toLowerCase() + s.slice(1);
}

function fixEmail(s) {
    s = s.trim().toLowerCase();
    return s;
}
