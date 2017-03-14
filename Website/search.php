<?php
session_start();
include 'includes/header.php';
?>

<div class="container" style="width: 50%">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Search By Name
        </div>
        <div class="panel-body">
            <form action="results.php" method="get">
            <div class="input-group">
                <input type="text" name="nquery" class="form-control" placeholder="School name ..">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Go</button>
                </span>
            </div>
            </form>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Search By Address
        </div>
        <div class="panel-body">
            <form action="results.php" method="get">
                <div class="input-group">
                    <input type="text" name='aquery' class="form-control" placeholder="School Address ..">
                    <span class="input-group-btn">
                        <input type="submit" value="Go!" class="btn btn-default">
                </span>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Search By Type
        </div>
        <div class="panel-body">
            <form action="results.php" method="get">
            <div class="input-group">
                <select name="tquery" class="form-control">
                    <option value="national">national</option>
                    <option value="international">international</option>
                </select>
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Go</button>
                </span>
            </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php' ?>