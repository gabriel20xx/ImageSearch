<?php
include 'includes/mysql.php';
?>
<?php
// Initialize $displayMode based on query parameter or user preference
if (isset($_GET['display_mode'])) {
    $displayMode = 'cards';
} else {
    $displayMode = 'cards';
}
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
        $filter = $_GET["filter"];
        $countmax = $_GET["count"];

        $sql = "SELECT COUNT(*) as count FROM Metadata";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $count = $row["count"];

            if ($count > $countmax) {
                $count = $countmax;
            }

            for ($i = 0; $i < $count; $i++) {
                $sql = "SELECT * FROM Metadata WHERE $filter LIKE ? LIMIT 1 OFFSET " . ($i+(($currentPage-1)*$count));
                $stmt = mysqli_prepare($conn, $sql);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "s", $search);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);

                    $numRows = mysqli_num_rows($result);

                    if ($numRows > 0) {
                        echo "Number of rows matching the query: $numRows";

                        if ($displayMode === 'cards') {
                            echo '<div class="card-grid">';
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="card" onclick="openFullscreen(\'images/' . $row['Directory'] . '/' . $row['FileName'] . '.png\')">';
                                echo '<img src="' . "images" . "/" . $row['Directory'] . "/" . $row['FileName'] . ".png" . '" alt="Image">';
                                echo '<p>' . substr($row['PositivePrompt'], 0, 50) . '</p>';
                                echo '<p>' . substr($row['NegativePrompt'], 0, 50) . '</p>';
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
        }
    }
    ?>

    <!-- Page indicator -->
    <?php
    if ($currentPage != 1) {
        $previousPage = $currentPage - 1;
    } else {
        $previousPage = "None";
    }

    if ($count > 12 * ($currentPage)) {
        $nextPage = $currentPage + 1;
    } else {
        $nextPage = "None";
    }
    ?>

    <div>
        <ul class="pagination justify-content-center">
            <?php
            if ($count > 12 && $currentPage != 1) {
                echo "<li class='page-item'>
                <a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $previousPage))) . "' aria-label='Previous'>
                    <span aria-hidden='true'>«</span>
                </a>
            </li>
            
            <li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $previousPage))) . "'>$previousPage</a></li>";
            }

            echo "<li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $currentPage))) . "'>$currentPage</a></li>";

            if ($count > 12 * ($currentPage)) {
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
    <script>
        function setDisplayMode(mode) {
            window.location.href = `index.php?display_mode=${mode}`;
        }

        function openFullscreen(imageSrc) {
            var fullscreenContainer = document.createElement('div');
            fullscreenContainer.className = 'fullscreen';

            var imgElement = document.createElement('img');
            imgElement.src = imageSrc;

            fullscreenContainer.appendChild(imgElement);

            document.body.appendChild(fullscreenContainer);

            // Close fullscreen on click
            fullscreenContainer.addEventListener('click', function () {
                document.body.removeChild(fullscreenContainer);
            });
        }
    </script>
</body>

</html>