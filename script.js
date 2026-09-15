//use this please: file:///C:/Users/Maxwe/OneDrive/Documents/Software%20Capstone%20Proposal/CapstoneProject/LibraryWebsite/login.html


function goToSignup() {
    window.location.href = "signup.html";
}

/** Pass and username requirements */
const storedEmail = "test@email.com";
const storedPassword = "password123";
const maxAttempts = 5;
const lockoutDuration = 30 * 60 * 1000; 




/**fix later so that after 5 attempts the error page is displayed then have it be on for 30 minutes */
function goToMain() {

    if (
        document.getElementById("email").value === storedEmail &&
        document.getElementById("password").value === storedPassword
    ) {
        window.location.href = "main.html";
    } else {
        window.location.href = "error.html";
    }
}





function forgotPassword() {
    window.location.href = "login.html";
}

function logout() {
    window.location.href = "login.html";
}

function closeErrorAlert(){
    document.getElementById("errorAlert").style.display = "none";
}


