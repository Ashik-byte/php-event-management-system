<?php 
    include 'session-check.php';
    include "header.php";
    include "navbar.php";
?>

<div class="wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="content-area">
        <h2>Change Password</h2>
        <form id="changePasswordForm">
            <label for="currentPassword">Current Password:</label>
            <input type="password" id="currentPassword" name="currentPassword" required>

            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>

            <label for="confirmPassword">Confirm New Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>

            <button type="submit">Change Password</button>
        </form>
    </div>
</div>

<?php 
    include "footer.php"; 
?>