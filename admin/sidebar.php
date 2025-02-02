<button class="sidebar-toggle" id="sidebarToggleBtn">☰</button>

<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <?php if ($_SESSION["is_admin"] == 1): ?>
        <li><a href="users.php">Users</a></li>
        <?php endif; ?>
        <li><a href="events.php" class="nav-link" data-page="events">Events</a></li>
        <li><a href="attendees.php" class="nav-link" data-page="attendees">Attendees</a></li>
    </ul>
</div>