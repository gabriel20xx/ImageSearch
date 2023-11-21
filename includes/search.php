<?php
if (isset($_GET['search'])) {
    include 'includes/mysql.php';

    $search = '%' . $_GET['search'] . '%';
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'PositivePrompt';
    $countmax = isset($_GET['count']) ? $_GET['count'] : 25;

    $sqlCount = "SELECT COUNT(*) as count FROM Metadata WHERE `" . mysqli_real_escape_string($conn, $filter) . "` LIKE ?";
    $stmtCount = mysqli_prepare($conn, $sqlCount);

    if ($stmtCount) {
        mysqli_stmt_bind_param($stmtCount, "s", $search);
        mysqli_stmt_execute($stmtCount);
        $resultCount = mysqli_stmt_get_result($stmtCount);
        $row = mysqli_fetch_assoc($resultCount);
        $totalCount = $row["count"];
        $count = $totalCount;
        echo '<p class="text-center">Total number of rows matching the query: ' . $totalCount . '</p>';

        $sqlData = "SELECT * FROM Metadata WHERE `" . mysqli_real_escape_string($conn, $filter) . "` LIKE ? LIMIT $countmax OFFSET " . $countmax * ($currentPage - 1);
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
                                <li class="list-group-item">' . substr($row['PositivePrompt'], 0, 50) . '</li>
                                <li class="list-group-item">' . substr($row['NegativePrompt'], 0, 50) . '</li>
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
}
?>
