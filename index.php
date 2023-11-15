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

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <title>Horde Image Indexer</title>
</head>

<body>
    <h1>Horde Image Indexer</h1>

    <div class="toggle-buttons">
        <button class="<?= $displayMode === 'list' ? 'active' : ''; ?>" onclick="setDisplayMode('list')">List View</button>
        <button class="<?= $displayMode === 'cards' ? 'active' : ''; ?>" onclick="setDisplayMode('cards')">Card View</button>
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
    function shortenText($text, $maxLength = 50) {
        if (mb_strlen($text) > $maxLength) {
            $shortenedText = mb_substr($text, 0, $maxLength) . '...';
            return '<span class="expand-text" onclick="expandText(this)">' . htmlspecialchars($shortenedText) . '</span>';
        } else {
            return htmlspecialchars($text);
        }
    }

    if (isset($_GET['search'])) {
        $search = '%' . $_GET["search"] . '%';
        $filter = $_GET["filter"];

        $sql = "SELECT * FROM Metadata WHERE $filter LIKE ? LIMIT 100";
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
                        echo '<div class="card">';
                        echo '<img src="' . "images" . "/" . $row['Directory'] . "/" . $row['FileName'] . ".png" . '" alt="Image">';
                        echo '<p class="short-text" data-full-text="' . htmlspecialchars($row['PositivePrompt']) . '">' . shortenText($row['PositivePrompt']) . '</p>';
                        echo '<p class="short-text" data-full-text="' . htmlspecialchars($row['NegativePrompt']) . '">' . shortenText($row['NegativePrompt']) . '</p>';
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
            const url = new URL(window.location.href);
            url.searchParams.set('display_mode', mode);
            history.pushState({}, '', url);
            updateButtonStyles(mode);
        }

        function updateButtonStyles(activeMode) {
            const buttons = document.querySelectorAll('.toggle-buttons button');
            buttons.forEach(button => {
                const mode = button.textContent.toLowerCase().replace(' ', '_');
                button.classList.toggle('active', mode === activeMode);
            });
        }

        function expandText(element) {
            const fullText = element.dataset.fullText;
            element.innerHTML = fullText;
            element.classList.add('expanded');
            element.onclick = null;
        }
    </script>
</body>

</html>