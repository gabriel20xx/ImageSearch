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
</head>

<body>
    <h1>Horde Image Indexer</h1>

    <form method="get" action="index.php">
        <select name="filter" onchange="handleFilterChange(this.value)">
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
        </select>
        <?php
        if (isset($_GET['filter']) && ($_GET['filter'] === 'PositivePrompt' || $_GET['filter'] === 'NegativePrompt')) {
            echo '<label for="search">Search:</label>';
            echo '<input type="text" name="search" id="search" placeholder="Enter your search term">';
        }
        ?>

        <?php
        if (isset($_GET['filter']) && $_GET['filter'] === 'Model') {
            echo '<select name="model">';
            echo '<option value="URPM">URPM</option>';
            echo '<option value="Hassanblend">Hassanblend</option>';
            echo '</select>';
        }
        ?>
        <select name="count">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="100">100</option>
        </select>
        <input type="submit" value="Search">
    </form>

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

    <!-- Fullscreen Image Container -->
    <div class="fullscreen-container" id="fullscreenContainer" onclick="closeFullscreen()">
        <span class="close-button" onclick="closeFullscreen()">&times;</span>
        <img src="" alt="Fullscreen Image" class="fullscreen-image" id="fullscreenImage">
    </div>

    <script>
        function openFullscreen(imageSrc) {
            var fullscreenContainer = document.getElementById('fullscreenContainer');
            var fullscreenImage = document.getElementById('fullscreenImage');

            fullscreenImage.src = imageSrc;
            fullscreenContainer.style.display = 'flex';
        }

        function closeFullscreen() {
            var fullscreenContainer = document.getElementById('fullscreenContainer');
            fullscreenContainer.style.display = 'none';
        }
    </script>
</body>

</html>