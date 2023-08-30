function checkFormat() {
    var input = document.getElementById("username").value;
    var regex = /^[a-z]_[a-z]_[a-z]+_trb$/;

    if (regex.test(input)) {
        document.getElementById("usernameError").innerHTML = "";
        document.getElementById("username").style.borderColor = "green";
    } else {
        document.getElementById("usernameError").innerHTML = "Invalid format! Please follow the format: a_b_last_trb";
        document.getElementById("username").style.borderColor = "red";
    }
}