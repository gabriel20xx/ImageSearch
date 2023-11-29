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
    <script src="js/fullscreen.js"></script>
    <script src="js/handleFilterChange.js"></script>
    <script src="js/handleMinMaxRangeChange.js"></script>
    <script src="js/duplicateForm.js"></script>
    <script src="js/onLoad.js"></script>
</head>

<body>
    <h1>Horde Image Indexer</h1>

    <form method="get" action="index.php" class="container">
        <div>
            <!-- Filter container -->
            <div class="mb-3">
                <label for="filter" class="form-label">Filter</label>
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

            <!-- Search container -->
            <div id="search-form" class="mb-3">
                <label for="search-input" class="form-label">Search</label>
                <input type="text" name="search" id="search-input" class="form-control" placeholder="Enter your search term..." value="<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>">
            </div>

            <!-- Min, Max, Range container -->
            <div id="minmaxrange-form" class="mb-3">
                <label for="minmaxrange-input" class="form-label">Min, Max or Range?</label>
                <select id="minmaxrange-input" class="form-select" name="min-max-range" onchange="handleMinMaxRangeChange(this.value)">
                    <?php
                    $minMaxRangeOptions = ['Min', 'Max', 'Range'];

                    foreach ($minMaxRangeOptions as $option) {
                        echo '<option value="' . $option . '" ' . (isset($_GET['min-max-range']) && $_GET['min-max-range'] === $option ? 'selected' : '') . '>' . $option . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- One Value container -->
            <div id="oneValueForm" class="mb-3">
                <label for="oneValueInput" class="form-label">Type in your Number...</label>
                <input class="form-control" id="oneValueInput" name="one-value" type="number" step="0.01" value="<?php echo isset($_GET['one-value']) ? htmlentities($_GET['one-value']) : "0.5"; ?>">
            </div>

            <!-- Two Value container -->
            <div id="twoValueForm" class="mb-3">
                <div>
                    <label for="lowerValueInput" class="form-label">Type in your lower Number...</label>
                    <input class="form-control" id="lowerValueInput" name="lower-value" type="number" step="0.01" value="<?php echo isset($_GET['lower-value']) ? htmlentities($_GET['lower-value']) : "0.25"; ?>">
                </div>
                <div>
                    <label for="upperValueInput" class="form-label">Type in your higher Number...</label>
                    <input class="form-control" id="upperValueInput" name="upper-value" type="number" step="0.01" value="<?php echo isset($_GET['upper-value']) ? htmlentities($_GET['upper-value']) : "0.75"; ?>">
                </div>
            </div>

            <!-- Model container -->
            <div id="model-form" class="mb-3">
                <label for="model-input" class="form-label">Model</label>
                <select class="form-control" id="model-input" name="model">
                    <?php
                    $modelOptions = ['URPM', 'ChilloutMix', 'PFG', 'RealBiter', 'PPP', 'HRL'];

                    foreach ($modelOptions as $option) {
                        echo '<option value="' . $option . '" ' . (isset($_GET['model']) && $_GET['model'] === $option ? 'selected' : '') . '>' . $option . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Sort container -->
            <div id="sortForm" class="mb-3">
                <label for="sort-input" class="form-label">Sort</label>
                <select class="form-control" id="sort-input" name="sort">
                    <?php
                    $sortOptions = ['ASC', 'DESC'];

                    foreach ($sortOptions as $option) {
                        echo '<option value="' . $option . '" ' . (isset($_GET['sort']) && $_GET['sort'] === $option ? 'selected' : '') . '>' . $option . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div>
            <div class="mb-3">
                <input type="button" class="btn btn-success add-row" value="Add Row" onclick="duplicateForm()">
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
    if (isset($_GET['filter'])) {
        require_once 'includes/search.php';

        echo '<script>';
        echo 'var jsImages = ' . json_encode($phpImages) . ';';
        echo '</script>';
    }
    ?>
</body>

</html>