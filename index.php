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
            <!-- Filter container -->
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

            <!-- Search container -->
            <div id="search" class="search-form mb-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" class="form-control search-input" placeholder="Enter your search term..." value="<?php echo isset($_GET['search']) ? htmlentities($_GET['search']) : ''; ?>">
            </div>

            <!-- Min, Max, Range container -->
            <div class="minmaxrange-form" id="min-max-range">
                <label for="min-max-range" class="form-label">Min, Max or Range?</label>
                <select id="min-max-range" class="minmaxrange-input form-select" name="min-max-range" onchange="handleMinMaxRangeChange(this.value)">
                    <option value="Min">Min</option>
                    <option value="Max">Max</option>
                    <option value="Range">Range</option>
                </select>
            </div>

            <!-- One Value container -->
            <div class="oneValueForm">
                <label for="one-value" class="form-label">Type in your Number...</label>
                <input class="oneValueInput" id="one-value" name="one-value" type="number" step="0.01" value="<?php echo isset($_GET['one-value']) ? htmlentities($_GET['one-value']) : "0.5"; ?>">
            </div>

            <!-- Two Value container -->
            <div class="twoValueForm">
                <div>
                    <label for="lower-value" class="form-label">Type in your lower Number...</label>
                    <input class="lowerValueInput" id="lower-value" name="lower-value" type="number" step="0.01" value="<?php echo isset($_GET['lower-value']) ? htmlentities($_GET['lower-value']) : "0.25"; ?>">
                </div>
                <div>
                    <label for="upper-value" class="form-label">Type in your higher Number...</label>
                    <input class="upperValueInput" id="upper-value" name="upper-value" type="number" step="0.01" value="<?php echo isset($_GET['upper-value']) ? htmlentities($_GET['upper-value']) : "0.75"; ?>">
                </div>
            </div>

            <!-- Model container -->
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