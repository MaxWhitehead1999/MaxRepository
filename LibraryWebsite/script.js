//use this please: file:///C:/Users/Maxwe/OneDrive/Documents/Software%20Capstone%20Proposal/CapstoneProject/LibraryWebsite/login.html

function goToSignup() {
    window.location.href = "signup.html";
}

/** Pass and username requirements */

const maxAttempts = 5;
const lockoutDuration = 30 * 60 * 1000;

let count = 0;

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


/** functions used for password validation in signup menu, conditions include:
 *  not leaving the password blank,
 *  length being less then 8 charecters 
 *  password must include at least one uppercase letter, 
 *  password must include one lowercase letter,
 *  password must include one number, 
 *  password must include one special character
 */
function loginValidation(password) {

    if( password.length === 0) {
        alert("Password cannot be empty.");
        return false;
    } else if (password.length < 8) {
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

    return true;
}


/** functions used for email validation in signup menu, conditions include:
 *  not leaving the email blank,
 *  length being less then 5 
 *  email must include a @ and a . within
 */
function emailValidation(email) {
    if(email.length === 0 ) {
        alert("Email cannot be empty.");
        return false;
    } else if (email.length < 5) {
        alert("Email must be at least 9 characters long.");
        return false;
    } else if (!email.includes("@") || !email.includes(".")) {
        alert("Please enter a valid email address that includes an '@' symbol and a domain.");
        return false;
    } 
    return true;
}

/** functions used for phone validation in signup menu, conditions include:
 *  not leaving the phone number blank,
 *  length being exactly 10 digits
 *  phone number must only include numbers
 */
function phoneValidation(phone) {
    if (phone.length === 0) {
        alert("Phone number cannot be empty.");
        return false;
    } else if (!/^\d{10}$/.test(phone)) {
        alert("Please enter a valid 10-digit phone number.");
        return false;
    }
    return true;
}

/** functions used for address validation in signup menu, conditions include:
 *  not leaving the address blank,
 */
function addressValidation(address) {
    if (address.length === 0) {
        alert("Address cannot be empty.");
        return false;
    }
    return true;
}

/** functions used for first name validation in signup menu, conditions include:
 *  not leaving the first name blank,
 */
function firstNameValidation(firstName) {
    if (firstName.length === 0) {
        alert("First name cannot be empty.");
        return false;
    }
    return true;
}

function lastNameValidation(lastName) {
    if (lastName.length === 0) {
        alert("Last name cannot be empty.");
        return false;
    }
}




/** functions used for signup validation in signup menu 
 */
function validateSignup() {
    let password = document.getElementById("password").value;
    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;
    let address = document.getElementById("address").value;
    let firstName = document.getElementById("firstName").value;
    let lastName = document.getElementById("lastName").value;
    
  
    if (!emailValidation(email)) {
        return false; // Stop execution if email is invalid
    }

    if (!loginValidation(password)) {
        return false; // Stop execution if password is invalid
    }

    if (!phoneValidation(phone)) {
        return false; // Stop execution if phone number is invalid
    }
    if (!addressValidation(address)) {
        return false; // Stop execution if address is invalid
    }

    if (!firstNameValidation(document.getElementById("firstName").value)) {
        return false; // Stop execution if first name is invalid
    }

    if (!lastNameValidation(document.getElementById("lastName").value)) {
        return false; // Stop execution if last name is invalid
    }

    return true;
}



/* functions used for forgot password and logout buttons, they redirect the user to the login page */
function forgotPassword() {
    window.location.href = "login.html";
}

function logout() {
    window.location.href = "login.html";
}

function closeErrorAlert() {
    document.getElementById("errorAlert").style.display = "none";
}


