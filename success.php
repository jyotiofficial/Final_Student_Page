<?php
$title = "Success";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");
?>

<body>
    <?php include_once("../../components/navbar/index.php"); ?>

    <div class="container my-2 greet">
        <p>Application successfully registered</p>
    </div>

    <div class="container my-3" id="content">
        <div class="bg-light p-5 rounded">
            <div class="alert alert-success container col-8" role="alert">
                <h2 class="alert-heading">Application Success</h2>
                <hr>
                <p>Your application was submitted successfully.</p>
            </div>
        </div>
    </div>
</body>

</html>
