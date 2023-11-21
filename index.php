<?php
include 'includes/mysql.php';

if (isset($_GET['search'])) {
    require 'search.php';
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
    <title>Horde Image Indexer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <script src="js/script.js"></script>
</head>

<body>
    <h1>Horde Image Indexer</h1>

    <form method="get" action="index.php" class="container" onsubmit="updateFormAction()">
        <div>
            <div class="mb-3">
                <label for="filter" class="form-label">Select Filter</label>
                <select id="filter" class="form-select" name="filter" onchange="handleFilterChange(this.value)">
                    <?php
                    $filterOptions = [
                        'FileName', 'Directory', 'FileSize', 'PositivePrompt', 'NegativePrompt',
                        'Steps', 'Sampler', 'CFGScale', 'Seed', 'ImageSize', 'ModelHash',
                        'Model', 'SeedResizeFrom', 'DenoisingStrength', 'Version',
                        'NSFWProbability', 'SHA1', 'SHA256', 'MD5'
                    ];

                    foreach ($filterOptions as $option) {
                        echo '<option value="' . $option . '" ' . (isset($_GET['filter']) && $_GET['filter'] === $option ? 'selected' : '') . '>' . $option . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div id="search" class="search-form mb-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Enter your search term">
            </div>

            <div id="slider" class="slider-form mb-3">
                <label for="range" class="form-label">Select Range</label>
                <input type="range" id="range" name="range" min="0" max="100" step="1" value="25">
                <input type="range" id="range2" name="range2" min="0" max="100" step="1" value="75">
                <p>Selected Range: <span id="rangeValues"></span></p>
            </div>

            <div class="model-form mb-3">
                <label for="model" class="form-label">Choose Model</label>
                <select class="form-control" id="model" name="model">
                    <option value="URPM">URPM</option>
                </select>
            </div>
        </div>
        <div>
            <div class="mb-3">
                <input type="button" class="btn btn-success add-row" value="Add Row">
                <input type="submit" class="btn btn-primary" value="Search">
            </div>

            <div class="mb-3">
                <label for="count">Results per page</label>
                <select name="count" class="form-select">
                    <?php
                    $countOptions = [12, 24, 48, 96, 192];

                    foreach ($countOptions as $option) {
                        echo '<option value="' . $option . '" ' . (isset($_GET['count']) && $_GET['count'] == $option ? 'selected' : '') . '>' . $option . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </form>

    <!-- Fullscreen Image Container -->
    <div class="fullscreen-container text-center" id="fullscreenContainer" style="display: none;">
        <span class="close-button" onclick="closeFullscreen()">&times;</span>
        <div class="row">
            <div class="col-12">
                <img src="" alt="Fullscreen Image" class="fullscreen-image" id="fullscreenImage">
            </div>
        </div>
        <div class="row mt-2 d-flex justify-content-center">
            <div class="col-6">
                <button class="btn btn-primary btn-block" onclick="prevImage()">Previous</button>
            </div>
            <div class="col-6">
                <button class="btn btn-primary btn-block" onclick="nextImage()">Next</button>
            </div>
        </div>
    </div>

    <!-- Page indicator -->
    <?php
    $firstPage = 1;

    if ($currentPage != 1) {
        $previousPage = $currentPage - 1;
    } else {
        $previousPage = "None";
    }

    if ($currentPage > 2) {
        $overPreviousPage = $currentPage - 2;
    } else {
        $overPreviousPage = "None";
    }

    if ($count > $countmax * ($currentPage)) {
        $nextPage = $currentPage + 1;
    } else {
        $nextPage = "None";
    }

    if ($count > $countmax * ($currentPage - 1)) {
        $overNextPage = $currentPage + 2;
    } else {
        $overNextPage = "None";
    }

    $lastPage = ceil($count / $countmax);
    ?>

    <div>
        <ul class="pagination justify-content-center">
            <?php
            if ($count > $countmax && $currentPage != 1) {
                echo "<li class='page-item'>
            <a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $firstPage))) . "' aria-label='First'>
                <span aria-hidden='true'>«</span>
            </a>
        </li>";

                echo "<li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $previousPage))) . "' aria-label='Previous'><</a></li>";

                if ($overPreviousPage != "None") {
                    echo "<li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $overPreviousPage))) . "' aria-label='Previous'>$overPreviousPage</a></li>";
                }

                echo "<li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $previousPage))) . "' aria-label='Previous'>$previousPage</a></li>";
            }

            if (isset($_GET['search'])) {
                echo "<li class='page-item active'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $currentPage))) . "'>$currentPage</a></li>";
            }

            if ($count > $countmax * ($currentPage)) {
                echo "<li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $nextPage))) . "' aria-label='Next'>$nextPage</a></li>";

                if ($overNextPage != "None") {
                    echo "<li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $overNextPage))) . "' aria-label='Previous'>$overNextPage</a></li>";
                }

                echo "<li class='page-item'><a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $nextPage))) . "' aria-label='Next'>></a></li>";

                echo "<li class='page-item'>
            <a class='page-link' href='?" . http_build_query(array_merge($_GET, array('page' => $lastPage))) . "' aria-label='Last'>
                <span aria-hidden='true'>»</span>
            </a>
        </li>";
            }
            ?>
        </ul>
    </div>
</body>

</html>