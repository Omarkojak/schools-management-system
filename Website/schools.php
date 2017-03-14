<?php
session_start();
include "includes/header.php";
include "includes/footer.php";
?>

<?php
$raw_results = mysqli_query($conn, "CALL View_AllAvailable_Schools()");
if ($raw_results == false) {
    die(formattedMessage(DIE_MSG, 1));
} else {
    if (mysqli_num_rows($raw_results) < 1) {
        die(formattedMessage(DIE_MSG, 1));
    }
    $all_schools = mysqli_fetch_all($raw_results, MYSQL_ASSOC);
    $elem = array();
    $middle = array();
    $high = array();
    for ($x = 0; $x < count($all_schools); $x++) {
        if ($all_schools[$x]["level_id"] == 1) {
            array_push($elem, $all_schools[$x]);
        } else if ($all_schools[$x]["level_id"] == 2) {
            array_push($middle, $all_schools[$x]);
        } else {
            array_push($high, $all_schools[$x]);
        }
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1>Available Schools</h1>
        <p>Browse through our large number of schools!</p>
    </div>


    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#elementary">Elementary Schools</a></li>
        <li><a data-toggle="tab" href="#middle">Middle Schools</a></li>
        <li><a data-toggle="tab" href="#high">High Schools</a></li>
    </ul>

    <div class="tab-content">
        <div id="elementary" class="tab-pane fade in active">
            <table class="table table-user-information">
                <thead>
                <tr>
                    <td><strong>Name</strong></td>
                </tr>
                </thead>
                <tr>
                    <?php
                    foreach ($elem as $e) {
                        echo "<tr>";
                        echo "<td><a href='".$path."/schools/school.php?id=" . $e['school_id'] . "'>" . $e['School Name'] . "</a></td>";
                        echo "</tr>";
                    }
                    ?>
            </table>
        </div>

        <div id="middle" class="tab-pane fade">
            <table class="table table-user-information">
                <thead>
                <tr>
                    <td><strong>Name</strong></td>
                </tr>
                </thead>
                <tr>
                    <?php
                    foreach ($middle as $e) {
                        echo "<tr>";
                        echo "<td><a href='schools/school.php?id=" . $e['school_id'] . "'>" . $e['School Name'] . "</a></td>";
                        echo "</tr>";
                    }
                    ?>
            </table>
        </div>

        <div id="high" class="tab-pane fade">
            <table class="table table-user-information">
                <thead>
                <tr>
                    <td><strong>Name</strong></td>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($high as $e) {
                    echo "<tr>";
                    echo "<td><a href='schools/school.php?id=" . $e['school_id'] . "'>" . $e['School Name'] . "</a></td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>