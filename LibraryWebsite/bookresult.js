async function displayBook() {
    const params = new URLSearchParams(window.location.search);
    const bookId = params.get('id');

    const response = await fetch('getBooks.php');
    const books = await response.json();

    const book = books.find(b => b.id === bookId);

    if(!book){
        document.getElementById("bookTitle").textContent = "Book not found";
        return;
    }

    document.getElementById("bookTitle").textContent = book.title;
    document.getElementById("bookISBN").textContent = "ISBN: " + (book.isbn ?? "N/A");
    document.getElementById("bookSubtitle").textContent = "Subtitle: " + (book.subtitle ?? "N/A");
    document.getElementById("bookAuthor").textContent = "Author: " + (book.author ?? "N/A");
    document.getElementById("bookEdition").textContent = "Edition: " + (book.edition ?? "N/A");
    document.getElementById("bookPublisher").textContent = "Publisher: " + (book.publisher ?? "N/A");
    document.getElementById("bookPublicationDate").textContent = "Publication Date: " + (book.publication_date ?? "N/A");
    document.getElementById("bookFormat").textContent = "Format: " + (book.format ?? "N/A");
    document.getElementById("bookDewey").textContent = "Dewey Decimal Number: " + (book.dewey_decimal_number ?? "N/A");
    document.getElementById("bookGenre").textContent = "Genre: " + (book.genre ?? "N/A");
    document.getElementById("bookAudience").textContent = "Audience: " + (book.audience ?? "N/A");
    document.getElementById("bookPageNumber").textContent = "Page Number: " + (book.page_number ?? "N/A");

}

document.addEventListener('DOMContentLoaded', displayBook);