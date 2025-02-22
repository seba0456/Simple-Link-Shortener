<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <link rel="stylesheet" href="style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
</head>

<body>
    <a href="index.php"><img src="images/app-icon.png" style="width:150px;height:150px;" alt="App logo" class="center"></a>
    <?php
    require "library.php";
    // Pobierz public ID z parametru zapytania
    $tinyUrl = isset($_GET['target']) ? $_GET['target'] : '';

    // Wyświetl zawartość public ID
    $dbConfig = getDatabaseConfig();
    $conn = new mysqli($dbConfig['servername'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);

    if ($conn) {
        // Using prepared statement to avoid SQL injection
        $query = "SELECT * FROM url_list WHERE tinyUrl = ?";
        $stmt = $conn->prepare($query);

        // Bind the parameter
        $stmt->bind_param("s", $tinyUrl);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the data
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Access the 'longUrl' column from the result
                $longUrl = $row['longUrl'];

                // Do something with $longUrl
                echo "Long URL: $longUrl";

                header("Location: $longUrl");
                die();
            }
            // Close the statement
            $stmt->close();
        } else {
            header("Location: error.html");
        }
    }

    $conn->close();
    ?>
    <div class="border-box">
        <h1 style='text-align: center; font-size: 40px;'>Redirecting!</h1><br>
        <p style='font-size: 25px;'>Redirecting...</p>
    </div>
    <br>
    <hr>
    <footer>
        <h3>Authors:</h3>
        <div class="links">
            <a href="https://github.com/kordight">
                <img src="images/github-mark.png" alt="Github profile" style="width:42px;height:42px;">
                <br><strong>Kordight</strong>
            </a>
        </div>
    </footer>
</body>

</html>