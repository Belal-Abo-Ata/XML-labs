<?php
// Initialize the XML file path
$xmlFilePath = 'data.xml';
if (!file_exists($xmlFilePath)) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $root = $dom->createElement('contacts');
    $dom->appendChild($root);
    $dom->save($xmlFilePath);
} else {
    $dom = new DOMDocument();
    $dom->load($xmlFilePath);
}

function insertContact($name, $phone, $address, $email)
{
    global $dom, $xmlFilePath;
    $contact = $dom->createElement('contact');
    $contact->appendChild($dom->createElement('name', $name));
    $contact->appendChild($dom->createElement('phone', $phone));
    $contact->appendChild($dom->createElement('address', $address));
    $contact->appendChild($dom->createElement('email', $email));
    $dom->documentElement->appendChild($contact);
    $dom->save($xmlFilePath);
}

function updateContact($oldName, $newName, $newPhone, $newAddress, $newEmail)
{
    global $dom, $xmlFilePath;
    $contacts = $dom->getElementsByTagName('contact');
    foreach ($contacts as $contact) {
        if ($contact->getElementsByTagName('name')->item(0)->nodeValue == $oldName) {
            $contact->getElementsByTagName('name')->item(0)->nodeValue = $newName;
            $contact->getElementsByTagName('phone')->item(0)->nodeValue = $newPhone;
            $contact->getElementsByTagName('address')->item(0)->nodeValue = $newAddress;
            $contact->getElementsByTagName('email')->item(0)->nodeValue = $newEmail;
            $dom->save($xmlFilePath);
            return;
        }
    }
}

function deleteContact($name)
{
    global $dom, $xmlFilePath;
    $contacts = $dom->getElementsByTagName('contact');
    foreach ($contacts as $contact) {
        if ($contact->getElementsByTagName('name')->item(0)->nodeValue == $name) {
            $dom->documentElement->removeChild($contact);
            $dom->save($xmlFilePath);
            return;
        }
    }
}

function searchContacts($searchValue, $searchField)
{
    global $dom;
    $searchResults = [];
    $contacts = $dom->getElementsByTagName('contact');
    foreach ($contacts as $contact) {
        $currentFieldValue = $contact->getElementsByTagName($searchField)->item(0)->nodeValue;
        if (str_contains(strtolower($currentFieldValue), strtolower($searchValue))) {
            array_push($searchResults, $contact);
        }
    }
    return $searchResults;
}

$searchResults = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $searchValue = trim($_POST['searchValue'] ?? '');
    $searchField = $_POST['searchField'] ?? 'name';

    switch ($action) {
    case 'Insert':
        insertContact($name, $phone, $address, $email);
        break;
    case 'Update':
        updateContact($name, $name, $phone, $address, $email);
        break;
    case 'Delete':
        deleteContact($name);
        break;
    case 'Search':
        $searchResults = searchContacts($searchValue, $searchField);
        break;
    }
}

function displaySearchResults($searchResults)
{
    if (!empty($searchResults)) {
        echo '<div class="search-results ms-4">';
        echo '<h2 class="mb-3">Search Results</h2>';
        foreach ($searchResults as $contact) {
            echo '<div class="card mb-3">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($contact->getElementsByTagName('name')->item(0)->nodeValue) . '</h5>';
            echo '<p class="card-text"><strong>Phone:</strong> ' . htmlspecialchars($contact->getElementsByTagName('phone')->item(0)->nodeValue) . '</p>';
            echo '<p class="card-text"><strong>Address:</strong> ' . htmlspecialchars($contact->getElementsByTagName('address')->item(0)->nodeValue) . '</p>';
            echo '<p class="card-text"><strong>Email:</strong> ' . htmlspecialchars($contact->getElementsByTagName('email')->item(0)->nodeValue) . '</p>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    </head>
    <body class="vh-100 d-flex justify-content-center align-items-center">
    
        <div class="container d-flex align-items-center justify-content-center">
            <form action="index.php" method="post"class="col-5">
                <div class="mb-3 d-flex align-items-center">
                    <label for="name" class="form-label col-2 me-2">Name</label>
                    <input type="text" class="form-control" id="name" name="name" />
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <label for="phone" class="form-label col-2 me-2">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone"/>
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <label for="address" class="form-label col-2 me-2">Address</label>
                    <input type="text" class="form-control" id="address" name="address"/>
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <label for="email" class="form-label col-2 me-2">Email</label>
                    <input type="email" class="form-control" id="email" name="email"/>
                </div>
                <div class="d-flex gap-3 justify-content-center">
                        <button type="submit" name="action" value="Insert" class="btn btn-primary">Insert</button>
                        <button type="submit" name="action" value="Update" class="btn btn-secondary">Update</button>
                        <button type="submit" name="action" value="Delete" class="btn btn-danger">Delete</button>
                </div>
                    <div class="mt-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search Value" name="searchValue" aria-label="Search Value">
                            <select class="form-select" name="searchField">
                                <option value="name">Name</option>
                                <option value="phone">Phone</option>
                                <option value="address">Address</option>
                                <option value="email">Email</option>
                            </select>
                            <button class="btn btn-primary" type="submit" name="action" value="Search">Search</button>
                        </div>
                    </div>
            </form>
                        <?php
                        if (!empty($searchResults)) {
                            displaySearchResults($searchResults);
                        }
                        ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="app.js"></script>
</body>
</html>
