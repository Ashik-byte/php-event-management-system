<nav class="navbar">
    <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

    <div class="container-fluid">
        <a href="../index.php" class="navbar-logo">
            <img src="../assets/img/logo/logo.png" alt="Logo">
        </a>
        <div class="d-flex">
            <div class="dropdown">
                <img src="uploads/avatars/<?= $_SESSION['image_url']; ?>" alt="Profile Image" 
                     class="profile-img" id="profileDropdown" data-bs-toggle="dropdown">
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" id="openChangePasswordModal">Change Password</a></li>
                    <li><a class="dropdown-item" href="#" id="openProfileImageModal">Change Photo</a></li>
                    <li><a class="dropdown-item" href="session-destroy.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Profile Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="profileImageForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="profileImage" class="form-label">Upload New Profile Image</label>
                        <input type="file" class="form-control" id="profileImage" name="profileImage" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>