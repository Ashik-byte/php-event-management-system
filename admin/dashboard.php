<?php 
    include 'session-check.php';
    include "header.php";
    include "navbar.php"; 
?>

<div class="wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="content-area">
        <h2>Welcome to the Admin Dashboard - <?= $_SESSION["user_name"]; ?></h2>
        <p>This is the main dashboard content area.</p>
    </div>
</div>

<?php 
    include "footer.php"; 
?>

</body>
</html>