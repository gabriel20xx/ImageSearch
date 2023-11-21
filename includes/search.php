<?php
if (isset($_GET['search'])) {
    include 'mysql.php';

    $search = '%' . mysqli_real_escape_string($conn, $_GET['search']) . '%';
    $filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'PositivePrompt';
    $countmax = isset($_GET['count']) ? $_GET['count'] : 25;
    $currentPage = isset($_GET["page"]) ? $_GET["page"] : 1;

    $sqlCount = "SELECT COUNT(*) as count FROM Metadata WHERE `$filter` LIKE ?";
    $stmtCount = mysqli_prepare($conn, $sqlCount);

    if ($stmtCount) {
        mysqli_stmt_bind_param($stmtCount, "s", $search);
        mysqli_stmt_execute($stmtCount);
        $resultCount = mysqli_stmt_get_result($stmtCount);
        $row = mysqli_fetch_assoc($resultCount);
        $totalCount = $row["count"];
        echo '<p class="text-center">Total number of results: ' . $totalCount . '</p>';

        $sqlData = "SELECT * FROM Metadata WHERE `$filter` LIKE ? LIMIT $countmax OFFSET " . $countmax * ($currentPage - 1);
        $stmtData = mysqli_prepare($conn, $sqlData);

        if ($stmtData) {
            mysqli_stmt_bind_param($stmtData, "s", $search);
            mysqli_stmt_execute($stmtData);
            $resultData = mysqli_stmt_get_result($stmtData);
            mysqli_stmt_close($stmtData);

            $images = [];

            echo '
            <div class="container-fluid">
            <div class="row">';
            while ($row = mysqli_fetch_assoc($resultData)) {
                $images[] = 'images/' . $row['Directory'] . '/' . $row['FileName'] . '.png';
                echo
                '<div class="col-sm-6 col-md-4 col-lg-2 col-xl-1 mb-4">
                    <div class="card" onclick="openFullscreen(\'images/' . $row['Directory'] . '/' . $row['FileName'] . '.png\')">
                        <img src="' . "images" . "/" . $row['Directory'] . "/" . $row['FileName'] . ".png" . '" class="card-img-top" alt="Image">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">' . substr($row['PositivePrompt'], 0, 80) . '</li>
                                <li class="list-group-item">' . substr($row['NegativePrompt'], 0, 80) . '</li>
                                <li class="list-group-item">' . $row['Model'] . '</li>
                            </ul>
                        </div>
                    </div>
                </div>';
            }
            echo
            '</div>
            </div>';

            echo '<script>';
            echo 'var images = ' . json_encode($images) . ';';
            echo '</script>';
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

            <?php if (isset($_GET['search'])) : ?>
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
