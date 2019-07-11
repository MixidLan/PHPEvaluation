<?php
session_start();
require_once 'database.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);
//echo 'connection successfull <br>';
$db_name = 'real_estate';
$db_found = mysqli_select_db($conn, $db_name);
//echo DB_NAME . ' found!' . '<br>';
//$result = mysqli_query($conn, $query);


$titleErr = "";
$addressErr = "";
$cityErr = "";
$pcErr = "";
$areaErr = "";
$priceErr = "";
$typeErr = "";

if (isset($_POST["subBtn"])) {

    $title = $_POST["title"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $pc = $_POST["pc"];
    $area = $_POST["area"];
    $price = $_POST["price"];
    $type = $_POST["type"];
    $description = $_POST["description"];
    $photo = $_POST["photo"];

    $required = $_POST["title"] && $_POST["address"] && $_POST["city"] && $_POST["pc"] && $_POST["area"] && $_POST["price"] && $_POST["type"];

    if (empty($required)) {
        $titleErr = "Title is required";
        $addressErr = "Adress is required";
        $cityErr = "City is required";
        $pcErr = "Postal Code is required";
        $areaErr = "Only numbers please";
        $priceErr = "Only numbers please";
        $typeErr = "Type is required";
    } else if ($db_found) {
        echo "$db_name found !<br>";
        // Prepare my query
        $query = "INSERT INTO housing(title, address, city, pc, area, price, type, photo, description)
        VALUES('$title', '$address', '$city', '$pc', '$area', '$price', 
        '$type','$photo', '$description')";
        //Send the query to the DB
        $results = mysqli_query($conn, $query);
        if ($results)
            echo "House inserted successfully";
        else
            echo "Insert went wrong";
    } else
        echo "$db_name NOT found !<br>";
}

if (isset($_POST['mySubmit'])) {
    var_dump($_FILES);
    // Check if there is not errors
    if ($_FILES['myFile']['error'] != UPLOAD_ERR_OK) {
        echo "Some error during upload";
    } else {
        // Check if it's an image
        $extensionArray = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif'
        );
        // Check if the extension match a value in the array
        $extFile = array_search($_FILES['myFile']['type'], $extensionArray);

        if ($extFile) {
            // Hash the file name
            $shaFile = sha1_file($_FILES['myFile']['tmp_name']);
            echo "HASH NAME : " . $shaFile;
            $destinationDir = '/photo';
            $fileNumbers = 0;
            do {
                $fileName = $shaFile . $fileNumbers . '.' . $extFile;
                $fullPath = $destinationDir . $fileName;
                var_dump($fullPath);
                $fileNumbers++;
            } while (file_exists($fullPath));

            $moved = move_uploaded_file($_FILES['myFile']['tmp_name'], $fullPath);

            if ($moved)
                echo "File successfully saved";
            else
                echo "Error during saving";
        } else {
            echo 'File is not an image !';
        }
    }
}

?>
<style>
    h1 {
        text-align: center;
    }

    div {
        text-align: center;
        margin-top: 10%;
        border: 2px solid black;
        padding: 20px auto;
    }

    input {
        margin: 5px;
    }
</style>
<title>Evaluation</title>
</head>

<body>

    <div>
        <h1>Add Housing</h1>
        <form enctype="multipart/form-data" action="" method="POST">
            Title : <input type="text" name="title"><?php echo $titleErr; ?>
            <br>
            Address: <input type="text" name="address"><?php echo $addressErr; ?>
            <br>
            City: <input type="text" name="city"><?php echo $cityErr; ?>
            <br>
            Postal Code : <input type="text" name="pc"><?php echo $pcErr; ?>
            <br>
            Area : <input type="text" name="area"><?php echo $areaErr; ?>
            <br>
            Price : <input type="text" name="price"><?php echo $priceErr; ?>
            <br>
            <input type="hidden" name="photo" value="">
            Select photo : <input type="file" name="myFile">
            <input type="submit" name="mySubmit" value="Upload Photo">
            <br>
            Location/Sale : <?php echo $typeErr; ?>
            <select name="type"><?php echo $typeErr; ?>
                <option value="choose">Choose the type...</option>
                <option value="Location">Location</option>
                <option value="Sale">Sale</option>
            </select>
            <br>
            Description : <input type="text" name="description">
            <br>

            <input type="submit" name="subBtn" value="SEND">

        </form>
    </div>

</body>

</html>
<?php
session_unset();
?>