<?php
if (isset($_GET['filter'])) {
    include 'mysql.php';

    $filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'PositivePrompt';
    $search = '%' . (isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "") . '%';
    $model = isset($_GET['model']) ? mysqli_real_escape_string($conn, $_GET['model']) : 'URPM';
    $sort = isset($_GET['sort']) ? mysqli_real_escape_string($conn, $_GET['sort']) : 'ASC';
    $minmaxrange = isset($_GET['min-max-range']) ? (int)$_GET['min-max-range'] : 'Min';
    $oneValue = isset($_GET['one-value']) ? (int)$_GET['one-value'] : 0;
    $min = isset($_GET['lower-value']) ? (int)$_GET['lower-value'] : 0;
    $max = isset($_GET['upper-value']) ? (int)$_GET['upper-value'] : 1;
    $countmax = isset($_GET['count']) ? (int)$_GET['count'] : 25;
    $currentPage = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
    $offset = $countmax * ($currentPage - 1);

    if ($filter == 'NSFWProbability') {
        $value = 'BETWEEN ? AND ?';
    } else {
        $value = 'LIKE ?';
    }

    $sqlAllCount = "SELECT COUNT(*) as allcount FROM Metadata";
    $stmtAllCount = mysqli_prepare($conn, $sqlAllCount);
    mysqli_stmt_execute($stmtAllCount);
    $resultAllCount = mysqli_stmt_get_result($stmtAllCount);
    $row = mysqli_fetch_assoc($resultAllCount);
    $totalAllCount = $row["allcount"];
    mysqli_stmt_close($stmtAllCount);

    $sqlCount = "SELECT COUNT(*) as count FROM Metadata WHERE $filter $value";
    $stmtCount = mysqli_prepare($conn, $sqlCount);

    if ($stmtCount) {
        if ($filter == 'NSFWProbability') {
            if ($minmaxrange == 'Min') {
                mysqli_stmt_bind_param($stmtCount, "dd", $oneValue, 1);
            } else if ($minmaxrange == 'Max') {
                mysqli_stmt_bind_param($stmtCount, "dd", 0, $oneValue);
            } else {
                mysqli_stmt_bind_param($stmtCount, "dd", $min, $max);
            } 
        } else if ($filter == 'Model') {
            mysqli_stmt_bind_param($stmtCount, "s", $model);
        } else {
            mysqli_stmt_bind_param($stmtCount, "s", $search);
        }

        mysqli_stmt_execute($stmtCount);
        $resultCount = mysqli_stmt_get_result($stmtCount);
        $row = mysqli_fetch_assoc($resultCount);
        $totalCount = $row["count"];
        echo '<p class="text-center">Total number of results: ' . $totalCount . ' of ' . $totalAllCount . '</p>';

        $sqlData = "SELECT * FROM Metadata WHERE $filter $value ORDER BY id $sort LIMIT ? OFFSET ?";
        $stmtData = mysqli_prepare($conn, $sqlData);

        if ($stmtData) {
            if ($filter == 'NSFWProbability') {
                if ($minmaxrange == 'Min') {
                    echo 'Test';
                    mysqli_stmt_bind_param($stmtData, "ddii", $oneValue, 1, $countmax, $offset);
                } else if ($minmaxrange == 'Max') {
                    mysqli_stmt_bind_param($stmtData, "ddii", 0, $oneValue, $countmax, $offset);
                } else {
                    mysqli_stmt_bind_param($stmtData, "ddii", $min, $max, $countmax, $offset);
                }
            } else if ($filter == 'Model') {
                mysqli_stmt_bind_param($stmtData, "sii", $model, $countmax, $offset);
            } else {
                mysqli_stmt_bind_param($stmtData, "sii", $search, $countmax, $offset);
            }

            mysqli_stmt_execute($stmtData);
            $resultData = mysqli_stmt_get_result($stmtData);
            mysqli_stmt_close($stmtData);

            $phpImages = [];

            echo '
            <div class="container-fluid">
            <div class="row">';
            while ($row = mysqli_fetch_assoc($resultData)) {
                $phpImages[] = 'images/' . $row['Directory'] . '/' . $row['FileName'] . '.png';
                echo
                '<div class="col-sm-6 col-md-4 col-lg-2 col-xl-1 mb-4">
                    <div class="card" onclick="openFullscreen(\'images/' . $row['Directory'] . '/' . $row['FileName'] . '.png\')">
                        <img src="' . "images" . "/" . $row['Directory'] . "/" . $row['FileName'] . ".png" . '" class="card-img-top" alt="Image">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">' . substr($row['PositivePrompt'], 0, 80) . '</li>
                                <li class="list-group-item">' . substr($row['NegativePrompt'], 0, 80) . '</li>
                                <li class="list-group-item">' . $row['Steps'] . '</li>
                                <li class="list-group-item">' . $row['Model'] . '</li>
                                <li class="list-group-item">' . $row['NSFWProbability'] . '</li>
                            </ul>
                        </div>
                    </div>
                </div>';
            }
            echo
            '</div>
            </div>';
        } else {
            echo '<p class="text-center">Prepare statement failed for data retrieval.</p>';
        }
    }

    // Close the database connection
    mysqli_close($conn);

    $firstPage = 1;
    $previousPage = ($currentPage != 1) ? $currentPage - 1 : "None";
    $overPreviousPage = ($currentPage > 2) ? $currentPage - 2 : "None";
    $nextPage = ($totalCount > $countmax * $currentPage) ? $currentPage + 1 : "None";
    $overNextPage = ($totalCount > $countmax * ($currentPage - 1)) ? $currentPage + 2 : "None";
    $lastPage = ceil($totalCount / $countmax);
?>

    <!-- Fullscreen Image Container -->
    <div class="fullscreen-container text-center" id="fullscreenContainer" style="display: none;">
        <span class="close-button" onclick="closeFullscreen()">&times;</span>
        <div class="row">
            <div class="col-12">
                <img src="" alt="Fullscreen Image" class="fullscreen-image" id="fullscreenImage">
            </div>
        </div>
        <!-- Button Container -->
        <div class="button-container">
            <div class="row d-flex justify-content-center">
                <div class="col-4">
                    <button class="btn btn-primary btn-block" onclick="prevImage()">Previous</button>
                </div>
                <div class="col-4">
                    <button id="toggleAutoAdvanceButton" class="btn btn-primary btn-block" onclick="toggleAutoAdvance()">Enable Auto-Advance</button>
                </div>
                <div class="col-4">
                    <button class="btn btn-primary btn-block" onclick="nextImage()">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Page indicator -->
    <div>
        <ul class="pagination justify-content-center">
            <?php if ($totalCount > $countmax && $currentPage != 1) : ?>
                <li class='page-item'>
                    <a class='page-link' href='?<?= http_build_query(array_merge($_GET, array('page' => $firstPage))) ?>' aria-label='First'>
                        <span aria-hidden='true'>&lt;&lt;</span>
                    </a>
                </li>

                <li class='page-item'><a class='page-link' href='?<?= http_build_query(array_merge($_GET, array('page' => $previousPage))) ?>' aria-label='Previous'>
                        &lt;</a>
                </li>

                <?php if ($overPreviousPage != "None") : ?>
                    <li class='page-item'><a class='page-link' href='?<?= http_build_query(array_merge($_GET, array('page' => $overPreviousPage))) ?>' aria-label='Previous'><?= $overPreviousPage ?></a></li>
                <?php endif; ?>

                <li class='page-item'><a class='page-link' href='?<?= http_build_query(array_merge($_GET, array('page' => $previousPage))) ?>' aria-label='Previous'><?= $previousPage ?></a></li>
            <?php endif; ?>

            <?php if (isset($_GET['filter'])) : ?>
                <li class='page-item active'><a class='page-link' href='?<?= http_build_query(array_merge($_GET, array('page' => $currentPage))) ?>'><?= $currentPage ?></a></li>
            <?php endif; ?>

            <?php if ($totalCount > $countmax * $currentPage) : ?>
                <li class='page-item'><a class='page-link' href='?<?= http_build_query(array_merge($_GET, array('page' => $nextPage))) ?>' aria-label='Next'><?= $nextPage ?></a></li>

                <?php if ($overNextPage != "None") : ?>
                    <li class='page-item'><a class='page-link' href='?<?= http_build_query(array_merge($_GET, array('page' => $overNextPage))) ?>' aria-label='Previous'><?= $overNextPage ?></a></li>
                <?php endif; ?>

                <li class='page-item'><a class='page-link' href='?<?= http_build_query(array_merge($_GET, array('page' => $nextPage))) ?>' aria-label='Next'>&gt;</a></li>

                <li class='page-item'>
                    <a class='page-link' href='?<?= http_build_query(array_merge($_GET, array('page' => $lastPage))) ?>' aria-label='Last'>
                        <span aria-hidden='true'>&gt;&gt;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php } ?>