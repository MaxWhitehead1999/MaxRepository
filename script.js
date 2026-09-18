//use this please: file:///C:/Users/Maxwe/OneDrive/Documents/Software%20Capstone%20Proposal/CapstoneProject/LibraryWebsite/login.html


function goToSignup() {
    window.location.href = "signup.html";
}

/** Pass and username requirements */

const maxAttempts = 5;
const lockoutDuration = 30 * 60 * 1000;

let accounts = [["max", "whi", "password123", "29AvenueStreet", "test@email.com"],
["andrew", "smith", "password456", "456OakAvenue", "andrew@email.com"]]; // Example existing accounts

let count = 0;



/**fix later so that after 5 attempts the error page is displayed then have it be on for 30 minutes */
function goToMain() {



    emailInput = document.getElementById("email").value;
    passwordInput = document.getElementById("password").value;


    for (let i = 0; i < accounts.length; i++) {

        if (emailInput == accounts[i][4] && passwordInput == accounts[i][2]) {
            window.location.href = "main.html";
            break;
        } else {
            
            count++;
            if (count >= maxAttempts) {

                window.location.href = "error.html";
                count = 0; // Reset count after redirecting to error page
            }
            alert("Invalid email or password. Please try again.");
            break; 32
        }

    }

}



function addNewAccount() {

    if (!loginValidation(document.getElementById("password").value)) {
        return; // Stop execution if password is invalid
    }

    let newAccount = {
        firstName: document.getElementById("firstName").value,
        lastName: document.getElementById("lastName").value,
        password: document.getElementById("password").value,
        address: document.getElementById("address").value,
        email: document.getElementById("email").value
    };

    accounts.push([
        newAccount.firstName,
        newAccount.lastName,
        newAccount.password,
        newAccount.address,
        newAccount.email
    ]);

    window.location.href = "login.html";

}



function loginValidation(password) {
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
    } else if (!/[A-Z]/.test(password)) {
        alert("Password must contain at least one uppercase letter.");
        return false;
    } else if (!/[a-z]/.test(password)) {
        alert("Password must contain at least one lowercase letter.");
        return false;
    } else if (!/[0-9]/.test(password)) {
        alert("Password must contain at least one number.");
        return false;
    } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        alert("Password must contain at least one special character.");
        return false;
    }
}


function forgotPassword() {
    window.location.href = "login.html";
}

function logout() {
    window.location.href = "login.html";
}

function closeErrorAlert() {
    document.getElementById("errorAlert").style.display = "none";
}


