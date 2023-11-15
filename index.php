<?php
include 'includes/mysql.php';
?>
<?php
// Initialize $displayMode based on query parameter or user preference
if (isset($_GET['display_mode'])) {
$displayMode = 'cards';
} else {
    $displayMode ='cards';
}
?>

<!DOCTYPE html>
<html>    

<head>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <title>Horde Image Indexer</title>
</head>

<body>
    <h1>Horde Image Indexer</h1>

    <div class="toggle-buttons">
        <button class="<?php echo $displayMode === 'list' ? 'active' : ''; ?>" onclick="setDisplayMode('list')">List View</button>
        <button class="<?php echo $displayMode === 'cards' ? 'active' : ''; ?>" onclick="setDisplayMode('cards')">Card View</button>
    </div>

    <form method="get" action="index.php">
        <label for="search">Search:</label>
        <input type="text" name="search" id="search" placeholder="Enter your search term">
        <select name="filter">
            <option value="all">All</option>
            <option value="FileName">Filename</option>
            <option value="Directory">Directory</option>
            <option value="FileSize">File Size</option>
            <option value="PositivePrompt">Positive Prompt</option>
            <option value="NegativePrompt">Negativ Prompt</option>
            <option value="Steps">Steps</option>
            <option value="Sampler">Sampler</option>
            <option value="CFGScale">CFG Scale</option>
            <option value="Seed">Seed</option>
            <option value="ImageSize">Image Size</option>
            <option value="ModelHash">Model Hash</option>
            <option value="Model">Model</option>
            <option value="SeedResizeFrom">Seed Resize From</option>
            <option value="DenoisingStrength">Denoising Strength</option>
            <option value="Version">Version</option>
            <option value="NSFWProbability">NSFW Probability</option>
            <option value="SHA1">SHA1</option>
            <option value="SHA256">SHA256</option>
            <option value="MD5">MD5</option>
            <!-- Add more categories as needed -->
        </select>
        <select name="sort">
            <option value="relevance">Relevance</option>
            <option value="date">Date</option>
            <option value="rating">Rating</option>
        </select>
        <input type="submit" value="Search">
    </form>
    <?php 
    if (isset($_GET['search'])) {
        $search = $_GET["search"];
        $filter = $_GET["filter"];

        $sql = "SELECT * FROM Metadata WHERE $filter LIKE ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $search);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            if (mysqli_num_rows($result) > 0) {
                // Reset the result pointer to the beginning of the result set
                mysqli_data_seek($result, 0);

                if ($displayMode === 'cards') {
                    echo '<div class="card-grid">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="card">';
                        echo '<img src="'. "/images" . "/" . $row['Directory']. "/". $row['FileName'] . ".png".'" alt="Image">';
                        echo '<p>' . $row['PositivePrompt'] . '</p>';
                        echo '<p>' . $row['NegativePrompt'] . '</p>';
                        echo '<p>' . $row['Model'] . '</p>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<ul class="list-view">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<li>' . $row['PositivePrompt'] . '</li>';
                        echo '<li>' . $row['NegativePrompt'] . '</li>';
                        echo '<li>' . $row['Model'] . '</li>';
                    }
                    echo '</ul>';
                }
            } else {
                echo "No results found.";
            }
        } else {
            echo "Prepare statement failed.";
        }
    }
    ?>
    <script>
        function setDisplayMode(mode) {
            window.location.href = `index.php?display_mode=${mode}`;
        }
    </script>
</body>

</html>
