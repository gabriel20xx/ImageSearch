<?php
include 'includes/mysql.php';
?>

<?php
if (isset($_GET["page"])) {
    $currentPage = $_GET["page"];
} else {
    $currentPage = 1;
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <title>Horde Image Indexer</title>
    <script src="js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <h1>Horde Image Indexer</h1>

    <form method="get" action="index.php">
        <p>
            <select name="filter" onchange="handleFilterChange(this.value)">
                <option value="all" <?php echo (!isset($_GET['filter']) || $_GET['filter'] === 'all') ? 'selected' : ''; ?>>All</option>
                <option value="FileName" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'FileName') ? 'selected' : ''; ?>>Filename</option>
                <option value="Directory" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'Directory') ? 'selected' : ''; ?>>Directory</option>
                <option value="FileSize" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'FileSize') ? 'selected' : ''; ?>>File Size</option>
                <option value="PositivePrompt" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'PositivePrompt') ? 'selected' : ''; ?>>Positive Prompt</option>
                <option value="NegativePrompt" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'NegativePrompt') ? 'selected' : ''; ?>>Negative Prompt</option>
                <option value="Steps" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'Steps') ? 'selected' : ''; ?>>Steps</option>
                <option value="Sampler" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'Sampler') ? 'selected' : ''; ?>>Sampler</option>
                <option value="CFGScale" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'CFGScale') ? 'selected' : ''; ?>>CFG Scale</option>
                <option value="Seed" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'Seed') ? 'selected' : ''; ?>>Seed</option>
                <option value="ImageSize" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'ImageSize') ? 'selected' : ''; ?>>Image Size</option>
                <option value="ModelHash" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'ModelHash') ? 'selected' : ''; ?>>Model Hash</option>
                <option value="Model" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'Model') ? 'selected' : ''; ?>>Model</option>
                <option value="SeedResizeFrom" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'SeedResizeFrom') ? 'selected' : ''; ?>>Seed Resize From</option>
                <option value="DenoisingStrength" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'DenoisingStrength') ? 'selected' : ''; ?>>Denoising Strength</option>
                <option value="Version" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'Version') ? 'selected' : ''; ?>>Version</option>
                <option value="NSFWProbability" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'NSFWProbability') ? 'selected' : ''; ?>>NSFW Probability</option>
                <option value="SHA1" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'SHA1') ? 'selected' : ''; ?>>SHA1</option>
                <option value="SHA256" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'SHA256') ? 'selected' : ''; ?>>SHA256</option>
                <option value="MD5" <?php echo (isset($_GET['filter']) && $_GET['filter'] === 'MD5') ? 'selected' : ''; ?>>MD5</option>
            </select>

            <label for="search">Search:</label>
            <input type="text" name="search" id="search" placeholder="Enter your search term">
        </p>
        <p>
            <span class="add">Add fields</span>
            <input type="submit" value="Search">
            <select name="count">
                <option value="10" <?php echo (isset($_GET['count']) && $_GET['count'] === '10') ? 'selected' : ''; ?>>10</option>
                <option value="25" <?php echo (isset($_GET['count']) && $_GET['count'] === '25') ? 'selected' : ''; ?>>25</option>
                <option value="100" <?php echo (isset($_GET['count']) && $_GET['count'] === '100') ? 'selected' : ''; ?>>100</option>
            </select>
        </p>
    </form>


    <!-- Fullscreen Image Container -->
    <div class="fullscreen-container" id="fullscreenContainer" onclick="closeFullscreen()">
        <span class="close-button" onclick="closeFullscreen()">&times;</span>
        <img src="" alt="Fullscreen Image" class="fullscreen-image" id="fullscreenImage">
    </div>

    <?php
    if (isset($_GET['search'])) {
        $search = '%' . $_GET["search"] . '%';
        $page = 1;
        $filter = 'PositivePrompt';
        $countmax = 25;

        if (isset($_GET['filter'])) {
            $filter = $_GET["filter"];
        }

        if (isset($_GET['count'])) {
            $countmax = $_GET["count"];
        }

        $sqlCount = "SELECT COUNT(*) as count FROM Metadata WHERE `" . mysqli_real_escape_string($conn, $filter) . "` LIKE ?";
        $stmtCount = mysqli_prepare($conn, $sqlCount);

        if ($stmtCount) {
            mysqli_stmt_bind_param($stmtCount, "s", $search);
            mysqli_stmt_execute($stmtCount);
            $resultCount = mysqli_stmt_get_result($stmtCount);
            $row = mysqli_fetch_assoc($resultCount);
            $totalCount = $row["count"];
            echo "Total number of rows matching the query: $totalCount";

            $sqlData = "SELECT * FROM Metadata WHERE `" . mysqli_real_escape_string($conn, $filter) . "` LIKE ? LIMIT $countmax OFFSET " . $countmax * ($page - 1);
            $stmtData = mysqli_prepare($conn, $sqlData);

            if ($stmtData) {
                mysqli_stmt_bind_param($stmtData, "s", $search);
                mysqli_stmt_execute($stmtData);
                $resultData = mysqli_stmt_get_result($stmtData);
                mysqli_stmt_close($stmtData);

                echo '<div class="card-grid">';
                while ($row = mysqli_fetch_assoc($resultData)) {
                    echo '<div class="card" onclick="openFullscreen(\'images/' . $row['Directory'] . '/' . $row['FileName'] . '.png\')">';
                    echo '<img src="' . "images" . "/" . $row['Directory'] . "/" . $row['FileName'] . ".png" . '" alt="Image">';
                    echo '<p>' . substr($row['PositivePrompt'], 0, 50) . '</p>';
                    echo '<p>' . substr($row['NegativePrompt'], 0, 50) . '</p>';
                    echo '<p>' . $row['Model'] . '</p>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo "Prepare statement failed for data retrieval.";
            }
        }

        // Close the database connection
        mysqli_close($conn);
    } else {
        echo "Prepare statement failed for count retrieval.";
    }
    ?>


    <!-- Page indicator -->
    <?php
    if ($currentPage != 1) {
        $previousPage = $currentPage - 1;
    } else {
        $previousPage = "None";
    }

    if ($count > $countmax * ($currentPage)) {
        $nextPage = $currentPage + 1;
    } else {
        $nextPage = "None";
    }
    ?>

    <div>
        <ul class="pagination justify-content-center">
            <?php
            if ($count > $countmax && $currentPage != 1) {
                echo "<li class='page-item'>
                <a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $previousPage))) . "' aria-label='Previous'>
                    <span aria-hidden='true'>«</span>
                </a>
            </li>
            
            <li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $previousPage))) . "'>$previousPage</a></li>";
            }

            echo "<li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $currentPage))) . "'>$currentPage</a></li>";

            if ($count > $countmax * ($currentPage)) {
                echo "<li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $nextPage))) . "'>$nextPage</a></li>

            <li class='page-item'>
                <a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $nextPage))) . "' aria-label='Next'>
                    <span aria-hidden='true'>»</span>
                </a>
            </li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>