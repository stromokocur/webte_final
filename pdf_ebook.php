<?php
error_reporting(E_ALL | E_STRICT);
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);

// Example.
// Create a test book for download.
// ePub uses XHTML 1.1, preferably strict.
// This is the minimalistic version.

// This is for the example, this is the XHTML 1.1 header
$content_start =
"<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
. "<head>"
. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
. "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n"
. "<title>Test Book</title>\n"
. "</head>\n"
. "<body>\n";

$bookEnd = "</body>\n</html>\n";

// setting timezone for time functions used for logging to work properly
date_default_timezone_set('Europe/Berlin');

$fileDir = './PHPePub';

include_once("js/EPub.php");

$book = new EPub(); // no argumetns gives us the default ePub 2, lang=en and dir="ltr"

// Title and Identifier are mandatory!
$book->setTitle("Popis Sluzieb"); 
$book->setIdentifier("http://www.google.com", EPub::IDENTIFIER_URI); // Could also be the ISBN number, prefered for published books, or a UUID.
$book->setLanguage("sk"); // Not needed, but included for the example, Language is mandatory, but EPub defaults to "en". Use RFC3066 Language codes, such as "en", "da", "fr" etc.
$book->setDescription("popis sluzieb");
$book->setAuthor("STU", "STU");
$book->setPublisher("none", "http://neexistuje.com/"); // I hope this is a non existant address :)
$book->setDate(time()); // Strictly not needed as the book date defaults to time().
$book->setRights("Copyright and licence information specific for the book."); // As this is generated, this _could_ contain the name or licence information of the user who purchased the book, if needed. If this is used that way, the identifier must also be made unique for the book.
$book->setSourceURL("http://neexistuje.html");

// A book need styling, in this case we use static text, but it could have been a file.
$cssData = "body {\n  margin-left: .5em;\n  margin-right: .5em;\n  text-align: justify;\n}\n\np {\n  font-family: serif;\n  font-size: 10pt;\n  text-align: justify;\n  text-indent: 1em;\n  margin-top: 0px;\n  margin-bottom: 1ex;\n}\n\nh1, h2 {\n  font-family: sans-serif;\n  font-style: italic;\n  text-align: center;\n  background-color: #6b879c;\n  color: white;\n  width: 100%;\n}\n\nh1 {\n    margin-bottom: 2px;\n}\n\nh2 {\n    margin-top: -2px;\n    margin-bottom: 2px;\n}\n";
$book->addCSSFile("styles.css", "css1", $cssData);

// Add cover page
$cover = $content_start . "<h1>SLUZBY - vypocet funkcnych hodnot pre graf</h1>\n<h2>By: Ziaci STU</h2>\n" . $bookEnd;
$book->addChapter("Notices", "Cover.html", $cover);

$chapter1 = $content_start . "<h1>Chapter 1</h1>\n"
    . $_GET['html']
    . $bookEnd;
$book->addChapter("Chapter 1: Lorem ipsum", "Chapter001.html", $chapter1, true, EPub::EXTERNAL_REF_ADD);


$book->finalize(); // Finalize the book, and build the archive.

// Send the book to the client. ".epub" will be appended if missing.
$zipData = $book->sendBook("Sluzby - funkcie");

// After this point your script should call exit. If anything is written to the output,
// it'll be appended to the end of the book, causing the epub file to become corrupt.
?>

