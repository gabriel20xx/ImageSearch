<?php
include 'includes/mysql.php';
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

    <form method="get" action="index.php" class="container">
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
                <input type="text" name="search" class="form-control search-input" placeholder="Enter your search term..." value="<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>">

            </div>

            <div id="slider" class="slider-form mb-3">
                <label for="range" class="form-label">Select Range</label>
                <input class="slider-input" type="range" id="range" name="range" min="0" max="100" step="1" value="25">
            </div>

            <div class="model-form mb-3">
                <label for="model" class="form-label">Choose Model</label>
                <select class="form-control model-input" id="model" name="model">
                    <?php
                    $modelOptions = [
                        'URPM'
                    ];

                    foreach ($modelOptions as $option) {
                        echo '<option value="' . $option . '" ' . (isset($_GET['model']) && $_GET['model'] === $option ? 'selected' : '') . '>' . $option . '</option>';
                    }
                    ?>
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

    <!-- Search results -->
    <?php
    if (isset($_GET['search'])) {
        require_once 'includes/search.php';

        echo '<script>';
        echo 'var jsImages = ' . json_encode($phpImages) . ';';
        echo '</script>';
    }
    ?>
</body>

</html>