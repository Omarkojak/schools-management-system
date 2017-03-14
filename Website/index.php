<?php
session_start();
include 'includes/header.php';
include "includes/footer.php";

?>

<div class="container-fluid">
    <div class="jumbotron">
        <h1>Hello,</h1>
        <p>We connect you with thousands of schools from all around the globe with the ability to apply and monitor
            your child's performance. Looking for a school?</p>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <p>Looking for a specific school ? Our sophisticated search engine can help you search among <strong>THOUSANDS</strong>
                of schools in our system, you can search by name , type and school address.</p>
            <a href="search.php" class="btn btn-primary" role="button">Search NOW!</a>
        </div>
        <div class="col-md-6">
            <p>Not sure what you are looking for? View a list of all avaliable schools in our system along with all the
                information about it just in one click!</p>
            <a href="schools.php" class="btn btn-primary" role="button">View Schools!</a>
        </div>
    </div>

    <br />
</div>
