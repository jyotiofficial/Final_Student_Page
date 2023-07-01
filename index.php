<?php
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");
// Remove the commented-out code related to authentication
?>
<body>
    <div class="ghi">
    <?php
    include_once("../../components/navbar/index.php");
    ?>
    <div class="container my-2 greet">
        <p>Welcome, Pratik</p>
    </div>
    <div class="container text-center">
        <div class="row mx-auto">
            <div class="col mt-3">
                <a href="./new.php" class="btn btn-primary btn-lg col-md-12 p-sm-4" role="button">
                    Application</a>
            </div>
            <div class="col my-3">
                <a href="./previous.php" class="btn btn-warning btn-lg col-md-12 p-sm-4" role="button">Previous
                    Applications</a>
            </div>
        </div>
    </div>
    <?php
    include_once("../../components/announcement/index.php");
    ?>
    <?php
    include_once("../../components/student-profile/index.php");
    ?>
</div>
<div class="gj"></div>
<style>
.cv{
    height:100vh;
    width:100vw;
    z-index:10000;
    position: absolute;
    top:0;
}
</style> 
<script>
const gj = document.querySelector('.gj')
gj.addEventListener('click',()=>{
   gj.classList.add('cv');
})
</script>
</body>
</html>