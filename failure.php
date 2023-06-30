<?php
$title = "Failure";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");
?>

<body>
    <?php include_once("../../components/navbar/index.php"); ?>

    <div class="container my-2 greet">
        <p>Your Application Failed</p>
    </div>

    <div class="container my-3" id="content">
        <div class="bg-light p-5 rounded">
            <div class="alert alert-failed container col-8" role="alert">
                <h2 class="alert-heading">Application Failed</h2>
                <hr>
                <p><b>Something went wrong</b><br>
            We cannot register your application at this moment. Please try again later or contact TPO for further updates.</p>
            </div>
        </div>
    </div>
</body>

</html>
