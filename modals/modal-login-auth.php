<?php
    debug_backtrace() || die (); 
?>
<!-- Attendee Add Modal -->
<div class="modal fade" id="adminLoginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <img src="assets/img/logo/logo.png" alt="Logo" class="modal-logo position-absolute top-0 start-0 p-2" style="max-height: 50px;">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="errorBox" class="alert alert-danger d-none"></div>
                <!-- Login Form -->
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="loginEmail" autocomplete = "off" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPass" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPass" autocomplete = "off" required>
                    </div>
                    <button type="submit" class="btn btn-success w-50 mx-auto d-block">Login</button>
                </form>
                <!-- Register Form (Hidden Initially) -->
                <form id="registerForm" class="d-none">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="regName" autocomplete = "off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="regEmail" autocomplete = "off" required>
                    </div>
                    <button type="submit" class="btn btn-primary  w-50 mx-auto d-block">Register</button>
                </form>
                <!-- Reset Password Form (Hidden Initially) -->
                <form id="resetForm" class="d-none">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="resetEmail" required>
                    </div>
                    <button type="submit" class="btn btn-warning  w-50 mx-auto d-block">Reset Password</button>
                </form>
            </div>
            <div class="modal-footer">
                <button id="showLogin" class="btn btn-link d-none">Login</button>
                <button id="showRegister" class="btn btn-link">Register</button>
                <button id="showReset" class="btn btn-link">Forgot Password?</button>
            </div>
        </div>
    </div>
</div>