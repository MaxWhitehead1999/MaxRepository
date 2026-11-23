

function goToSignup() {
    window.location.href = "signup.html";
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

    if (password.length === 0) {
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
    if (email.length === 0) {
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
    return true;
}


/** functions used for signup validation in signup menu using if statements 
 *  that call for checks on the email, password, phone number, address, 
 *  first name, and last name. If any of these checks fail, the function 
 *  will return false and stop execution. If all checks pass, the function 
 *  will return true and allow the user to proceed with signing up.
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

    if (!firstNameValidation(firstName)) {
        return false; // Stop execution if first name is invalid
    }

    if (!lastNameValidation(lastName)) {
        return false; // Stop execution if last name is invalid
    }

    return true;
}

/* 
 *functions used for forgot password and logout buttons, they redirect 
 * user to the login page 
 */
function forgotPassword() {

    alert("Email Has sent a password reset link to your email address.");
    window.location.href = "login.html";
}

/* functions used for logout button, they redirect the user to the login page 
 *
 */
function logout() {
    window.location.href = "login.html";
}

/* functions used for closing the error alert message, they hide the error alert
 * message when the user clicks the close button
 */
function closeErrorAlert() {
    document.getElementById("errorAlert").style.display = "none";
}



/**
 * Gets user account information from the server and updates the profile page with the retrieved data.
 * This function fetches the account information from the server using a GET request to "getAccount.php".
 * It then updates the corresponding HTML elements on the profile page with the retrieved data.
 * If any error occurs during the fetch operation, it logs the error to the console.
 */
function getAccount() {

    fetch("getAccount.php")
        .then(response => response.json())
        .then(account => {

            if (document.getElementById("firstName")) {
                document.getElementById("firstName").textContent =
                    account.first_name;
            }

            if (document.getElementById("lastName")) {
                document.getElementById("lastName").textContent =
                    account.last_name;
            }

            if (document.getElementById("email")) {
                document.getElementById("email").textContent =
                    account.email;
            }

            if (document.getElementById("address")) {
                document.getElementById("address").textContent =
                    account.address;
            }

            if (document.getElementById("phone")) {
                document.getElementById("phone").textContent =
                    account.phone;
            }

            if (document.getElementById("welcome")) {
                document.getElementById("welcome").textContent =
                    "Welcome " + account.first_name;
            }

        })
        .catch(error => console.error(error));
}


/* Retrieves books from MySQL database which displays, sorts and filters them
 * on the search.html web page. It also grants the users the ability to click on a
 * hyper link that leads to bookresult.html that displays the data of the particular
 * book from the hyperlink
 */
async function fetchBooks() {
    //retrieves books from myLibrary sql on myphpadmin using getBooks.php
    const response = await fetch('getBooks.php');
    const books = await response.json();

    // Selection Options for search page includes the filter dropdown, sort dropdown, and search bar
    const filterSelect = document.getElementById('filterSelect').value;
    const sortSelect = document.getElementById('sort').value;
    const searchInput = document.getElementById('searchInput').value.toLowerCase();



    // determines which books should appear which includes a default filter option
    const filteredBooks = books.filter(book => {


        if (filterSelect === 'default') {
            return book.title.toLowerCase().includes(searchInput);
        }

        // filters out by selected field of book value then enforces conditions to ensure no field is left null
        const value = book[filterSelect];

        return value !== null &&
            value !== undefined &&
            String(value).trim() !== '' &&
            String(value).trim().toUpperCase() !== 'N/A' &&
            String(value).toLowerCase().includes(searchInput);
    });


    // Sort the filtered books based on the selected option, 0 sets to default
    filteredBooks.sort((a, b) => {
        if (sortSelect === 'ascending') {
            return a.title.localeCompare(b.title);
        } else if (sortSelect === 'descending') {
            return b.title.localeCompare(a.title);
        }
        return 0; // Default sorting (no change)
    });

    // finds and clears the results table to prevent more rows of the same books from being implemented if a filter, search, or sort is made
    const table = document.getElementById('searchResults');
    table.innerHTML = '';


    filteredBooks.forEach(book => {

        // Displays each book with a new table row and table collumn for data
        const row = document.createElement('tr');
        const cell = document.createElement('td');

        // allows for a clickable Title that will lead the user to bookresult.html
        const title = document.createElement('h3');
        const link = document.createElement('a');

        link.textContent = book.title;
        link.href = 'bookresult.html?id=' + book.id;

        title.appendChild(link);

        // displays any additional information added below the title of each book
        const details = document.createElement('p');
        details.textContent = "ISBN: " + (book.isbn ?? "N/A") +
            ", Author: " + (book.author ?? "N/A") +
            ", Publisher: " + (book.publisher ?? "N/A") +
            ", Genre: " + (book.genre ?? "N/A");

        cell.appendChild(title);
        cell.appendChild(details);
        row.appendChild(cell);
        table.appendChild(row);
    });

}


async function getUserCheckouts() {

    const container = document.getElementById("userCheckouts");

    if (!container) {
        return;
    }

    try {
        const response = await fetch("getUserCheckouts.php");

        if (!response.ok) {
            throw new Error("Unable to retrieve books.");
        }

        const books = await response.json();

        container.innerHTML = "";

        if (books.length === 0) {
            container.textContent = "No books currently checked out.";
            return;
        }

        books.forEach(book => {

            const paragraph = document.createElement("p");
            const link = document.createElement("a");

            link.textContent = book.title;
            link.href = "bookresult.html?id=" + book.id;

            paragraph.appendChild(link);
            container.appendChild(paragraph);

        });

    } catch (error) {
        console.error("Error loading checked out books:", error);
    }
}





// Sends the selected book ID to checkoutBook.php
async function checkoutBook(bookID) {

    try {
        const response = await fetch("checkoutBook.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                book_id: bookID
            })
        });

        const result = await response.json();

        if (result.success) {
            return true;
        } else {
            alert("Checkout Failed: " + result.message);
            return false;
        }

    } catch (error) {
        alert("An error occurred while checking out the book.");

        console.error(error);
        return false;
    }
}


// Connects the Check Out button to the selected book


function setupCheckoutButton() {

    const checkoutBtn = document.getElementById("checkoutBtn");

    if (!checkoutBtn) {
        return;
    }

    const params = new URLSearchParams(window.location.search);
    const bookID = Number(params.get("id"));

    if (!Number.isInteger(bookID) || bookID < 1) {
        checkoutBtn.disabled = true;
        return;
    }

    checkoutBtn.addEventListener("click", function () {

        let checkoutList = JSON.parse(
            sessionStorage.getItem("checkoutList") || "[]"
        );

        if (checkoutList.includes(bookID)) {
            alert("This book is already in your checkout list.");
            return;
        }

        checkoutList.push(bookID);

        sessionStorage.setItem(
            "checkoutList",
            JSON.stringify(checkoutList)
        );

        alert("Book added to checkout list!");
    });
}


async function checkoutAllBooks() {

    const checkoutList = JSON.parse(
        sessionStorage.getItem("checkoutList") || "[]"
    );

    if (checkoutList.length === 0) {
        alert("no books to check out");
        return;
    }

    const checkoutBtn = document.getElementById("checkoutAllBtn");

    checkoutBtn.disabled = true;

    const remainingBooks = [];
    let successCount = 0;

    try {
        for (const bookID of checkoutList) {

            const success = await checkoutBook(bookID);

            if (success) {
                successCount++;
            } else {
                remainingBooks.push(bookID);
            }
        }

        sessionStorage.setItem(
            "checkoutList",
            JSON.stringify(remainingBooks)
        );

        await displayCheckoutList();

        if (successCount > 0) {
            alert(successCount + " book(s) successfully reserved!");
        }

    } finally {
        checkoutBtn.disabled = false;
    }
}

// Displays the list of books in the checkout list on addToCheckout.html
async function displayCheckoutList() {

    const container = document.getElementById("checkoutList");

    if (!container) {
        return;
    }

    const checkoutList = JSON.parse(
        sessionStorage.getItem("checkoutList") || "[]"
    );

    container.innerHTML = "";

    if (checkoutList.length === 0) {
        container.textContent = "Books yet to be checked out";
        return;
    }

    try {
        const response = await fetch("getBooks.php");

        if (!response.ok) {
            throw new Error("Unable to retrieve books.");
        }

        const books = await response.json();

        checkoutList.forEach(bookID => {

            const book = books.find(
                book => Number(book.id) === bookID
            );

            if (!book) {
                return;
            }

            const paragraph = document.createElement("p");
            const link = document.createElement("a");

            link.textContent = book.title;
            link.href = "bookresult.html?id=" + book.id;

            paragraph.appendChild(link);
            container.appendChild(paragraph);
        });

    } catch (error) {
        console.error("Error displaying checkout list:", error);
        container.textContent = "Unable to load checkout list.";
    }
}

// Clears the checkout list from session storage and updates the display
function clearCheckoutList() {

    sessionStorage.removeItem("checkoutList");

    displayCheckoutList();

    alert("Checkout list cleared!");
}

// Retrieves the user's profile information from the server and populates the profile form fields in the editProfile.html page. If an error occurs during the fetch operation, it logs the error to the console and displays an alert to the user.
async function getProfile() {

    try {
        const response = await fetch("getProfile.php");

        if (!response.ok) {
            throw new Error("Unable to retrieve profile.");
        }

        const user = await response.json();

        document.getElementById("firstName").value = user.first_name;
        document.getElementById("lastName").value = user.last_name;
        document.getElementById("address").value = user.address;
        document.getElementById("email").value = user.email;

    } catch (error) {
        console.error(error);
        alert("Unable to load profile information.");
    }
}


// Updates users proile info from the editprofile page and sends the updated information to the server for processing. It prevents the default form submission behavior, constructs a user object with the updated profile information, and sends it to "updateProfile.php" using a POST request. If the update is successful, it displays a success message; otherwise, it logs the error and alerts the user of the failure.
async function updateProfile(event) {

    event.preventDefault();

    const user = {
        first_name: document.getElementById("firstName").value,
        last_name: document.getElementById("lastName").value,
        address: document.getElementById("address").value,
        email: document.getElementById("email").value
    };

    try {
        const response = await fetch("updateProfile.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(user)
        });

        const result = await response.json();

        alert(result.message);

    } catch (error) {
        console.error(error);
        alert("Unable to update profile.");
    }
}



// adds event listeners for sort dropdown, search input, and filter dropdown for search page
document.addEventListener('DOMContentLoaded', () => {

    // Only run search functions on search.html
    if (document.getElementById('sort')) {
        fetchBooks();

        document.getElementById('sort').addEventListener('change', fetchBooks);
        document.getElementById('searchInput').addEventListener('input', fetchBooks);
        document.getElementById('filterSelect').addEventListener('change', fetchBooks);
    }

    // Connect checkout button on bookresult.html
    setupCheckoutButton();


    getUserCheckouts();

    const checkoutAllBtn = document.getElementById("checkoutAllBtn");

    if (checkoutAllBtn) {
        checkoutAllBtn.addEventListener("click", checkoutAllBooks);
    }

    const clearCheckoutBtn = document.getElementById("clearCheckoutBtn");

    if (clearCheckoutBtn) {
        clearCheckoutBtn.addEventListener("click", clearCheckoutList);
    }

    displayCheckoutList();

    // Only run on editProfile.html
    const editProfileForm = document.getElementById("editProfileForm");

    if (editProfileForm) {
        getProfile();
        editProfileForm.addEventListener("submit", updateProfile);
    }
});
