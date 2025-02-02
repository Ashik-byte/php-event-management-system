$(document).ready(function() {

    // For datetime input in admin events menu
    flatpickr(".event_date_time", {
        enableTime: true,
        dateFormat: "Y-m-d H:i:S", // Adjust format if needed
        time_24hr: true,
        // defaultDate: "today",
        // minDate: "today" 
    });

    // Details button on click from event section
    $('.details-btn').on('click', function() {
        const eventId = $(this).data('id');
    
        handleAjaxRequest(
            'core/fetch-event-details.php',
            'POST',
            { id: eventId },
            function(data) {
                $('#eventDetailsTitle').text(data.name);
                $('#eventDetailsImage').attr('src', 'assets/img/events/' + data.image);
                $('#eventDetailsText').text(data.description);
                $('#eventDetailsAddress').text(data.address);
                $('#eventDetailsDate').text(data.date_time);
                $('#eventDetailsModal').modal('show');
            },
            function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Details',
                    text: xhr.responseJSON?.message || 'Unable to fetch event details. Please try again later.'
                });
            },
            false
        );
    });

    // Join button on click from event section
    $('.join-btn').on('click', function() {
        $('#joinEventTitle').text('Join ' + $(this).data('name'));
        $('#eventId').val($(this).data('id'));
        $('#eventName').val($(this).data('name'));
        $('#eventTime').val($(this).data('time'));
        $('#eventAddress').val($(this).data('address'));

        $('#joinEventModal').modal('show');
    });

    // Join button on submit from event section
    $('#joinEventForm').on('submit', function(e) {
        e.preventDefault();
    
        const eventId = $('#eventId').val();
        const eventName = $('#eventName').val();
        const eventAddress = $('#eventAddress').val();
        const eventTime = $('#eventTime').val();

        let attendeeName = $('#attendeeName').val().trim();
        let attendeeEmail = $('#attendeeEmail').val().trim();

        // Simple name validation (at least 3 characters)
        if (attendeeName.length < 3) {
            alert("Name must be at least 3 characters long.");
            return;
        }

        // Email validation using regex
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(attendeeEmail)) {
            alert("Please enter a valid email address.");
            return;
        }
        
        $('#joinEventModal').modal('hide');
        
        handleAjaxRequest(
            'core/create-attendee.php',
            'POST',
            { name: attendeeName, email: attendeeEmail, event_id: eventId, event_name: eventName, event_address: eventAddress, event_time: eventTime }, 
            function(data) { 
                $('#attendeeName').val('');
                $('#attendeeEmail').val('');
                Swal.fire({
                    icon: 'success',
                    title: 'Attendee Created',
                    text: data
                }).then(() => {
                    $('#attendeeName').val('');
                    $('#attendeeEmail').val('');
                    // $('#events').load(' #events > *');
                    // Reload events and reinitialize event listeners for modals
                    $('#events').load(' #events > *', function() {
                        // Rebind modal trigger after events are loaded
                        $(document).on('click', '.join-btn', function() {
                            $('#joinEventTitle').text('Join ' + $(this).data('name'));
                            $('#eventId').val($(this).data('id'));
                            $('#eventName').val($(this).data('name'));
                            $('#eventTime').val($(this).data('time'));
                            $('#eventAddress').val($(this).data('address'));

                            $('#joinEventModal').modal('show');
                        });

                        $(document).on('click', '.details-btn', function() {
                            const eventId = $(this).data('id');

                            handleAjaxRequest(
                                'core/fetch-event-details.php',
                                'POST',
                                { id: eventId },
                                function(data) {
                                    $('#eventDetailsTitle').text(data.name);
                                    $('#eventDetailsImage').attr('src', 'assets/img/events/' + data.image);
                                    $('#eventDetailsText').text(data.description);
                                    $('#eventDetailsAddress').text(data.address);
                                    $('#eventDetailsDate').text(data.date_time);
                                    $('#eventDetailsModal').modal('show');
                                },
                                function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error Fetching Details',
                                        text: xhr.responseJSON?.message || 'Unable to fetch event details. Please try again later.'
                                    });
                                }
                            );
                        });
                    });
                });
            },
            function(xhr, status, error) {
                $('#attendeeName').val('');
                $('#attendeeEmail').val('');
    
                let errorMessage = "An error occurred while processing your request.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
    
                Swal.fire({
                    icon: 'error',
                    title: 'Request Error',
                    text: errorMessage
                });
            },
            false
        );
    });

    // User Panel on click event
    $('.login-btn').on('click', function() {
        $('#adminLoginModal').modal('show');
    });

    $('.panel-btn').on('click', function() {
        window.location.href = "admin/dashboard.php";
    });

    // Register form submit event
    $("#registerForm").on("submit", function (e) {
        e.preventDefault();

        let name = $("#regName").val().trim();
        let email = $("#regEmail").val().trim();

        if (name === "" || email === "") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "All fields are required!"
            });
            return;
        }

        let payload = { name, email };

        $("#regName").val('');
        $("#regEmail").val('');
        $('#adminLoginModal').modal('hide');

        handleAjaxRequest(
            "core/new-user-registration.php",
            "POST",
            payload,
            function (data) {
                Swal.fire({
                    icon: "success",
                    title: "Registration Successful!",
                    text: data,
                }).then(() => {
                    $("#registerForm")[0].reset();
                });
            },
            function (xhr, textStatus, errorMessage) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage || "Something went wrong. Please try again."
                });
            },
            false
        );
    }); 
    
    // Reset password form submit event
    $("#resetForm").on("submit", function (e) {
        e.preventDefault();

        let email = $("#resetEmail").val().trim();

        if (email === "") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "All fields are required!"
            });
            return;
        }

        let payload = { email };

        $("#resetEmail").val('');
        $('#adminLoginModal').modal('hide');

        handleAjaxRequest(
            "core/user-pass-reset.php",
            "POST",
            payload,
            function (data) {
                Swal.fire({
                    icon: "success",
                    title: "Reset Successful!",
                    text: data,
                }).then(() => {
                    $("#resetForm")[0].reset();
                });
            },
            function (xhr, textStatus, errorMessage) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage || "Something went wrong. Please try again."
                });
            },
            false
        );
    }); 

    // Register button on click event
    $('#showRegister').on("click", function () {
        $("#loginForm").addClass("d-none");
        $("#resetForm").addClass("d-none");
        $("#registerForm").removeClass("d-none");

        $("#showLogin").removeClass("d-none");
        $("#showRegister").addClass("d-none");
        $("#showReset").removeClass("d-none"); 
    });

    // Reset pass on click event
    $("#showReset").on("click", function () {
        $("#loginForm").addClass("d-none");
        $("#resetForm").removeClass("d-none");
        $("#registerForm").addClass("d-none");

        $("#showLogin").removeClass("d-none");
        $("#showRegister").removeClass("d-none");
        $("#showReset").addClass("d-none");
    });

    // Show login on click event
    $("#showLogin").on("click", function () {      
        $("#loginForm").removeClass("d-none");
        $("#resetForm").addClass("d-none");
        $("#registerForm").addClass("d-none");

        $("#showLogin").addClass("d-none");
        $("#showRegister").removeClass("d-none");
        $("#showReset").removeClass("d-none");
    });

    // Submit Login Form via AJAX
    $("#loginForm").on("submit", function (e) {
        e.preventDefault();
        const email = $("#loginEmail").val().trim();
        const password = $("#loginPass").val().trim();
        const payload = {
            email: email,
            password: password
        };

        if (localStorage.getItem("jwt")) {
            localStorage.removeItem("jwt"); // Remove the existing JWT if present
        }

        handleAjaxRequest(
            "core/login.php",
            "POST",
            payload,
            function (data) {
                localStorage.setItem("jwt", data); // Store JWT for future use
                window.location.href = "admin/dashboard.php";
            },
            function(xhr) {
                $("#errorBox").removeClass("d-none");
                $('#loginEmail').val('');
                $('#loginPass').val('');
    
                let errorMessage = "An error occurred while processing your request.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $("#errorBox").text(errorMessage);
                setTimeout(function() {
                    $("#errorBox").addClass("d-none");
                }, 3000); 
            },
            false
        );
    });

    // General reusable AJAX function
    function handleAjaxRequest(url, method, payload, successCallback, errorCallback, requireAuth) {

        const headers = { "Content-Type": "application/json" };

        if (requireAuth) {
            const token = getTokenOrLogout();
            if (!token) return;
            headers["Authorization"] = "Bearer " + token;
        }
        
        $.ajax({
            url: url, 
            type: method,
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(payload), 
            headers: headers,
            success: function(response, textStatus, xhr) {
                if (response.status === "success") {
                    if (typeof successCallback === 'function') {
                        successCallback(response.data);
                    }
                } else {
                    if (typeof errorCallback === 'function') {
                        errorCallback(xhr, textStatus, response.message || 'Unexpected error.');
                    }
                }
            },
            error: function(xhr) {
                if (typeof errorCallback === 'function') {
                    errorCallback(xhr);
                }
            }
        });
    }

    // AJAX function for sending formdata
    function handleAjaxRequestOnFormData(url, method, payload, successCallback, errorCallback, requireAuth) {
        const headers = {};  
    
        if (requireAuth) {
            const token = getTokenOrLogout();
            if (!token) return;
            headers["Authorization"] = "Bearer " + token;
        }
    
        $.ajax({
            url: url,
            type: method,
            data: payload,  
            processData: false,  
            contentType: false,  // Let jQuery handle the content type (multipart/form-data)
            headers: headers,
            success: function(response, textStatus, xhr) {

                console.log(response.status);
                if (response.status === "success") {
                    if (typeof successCallback === 'function') {
                        successCallback(response.data);
                    }
                } else {
                    if (typeof errorCallback === 'function') {
                        errorCallback(xhr, textStatus, response.message || 'Unexpected error.');
                    }
                }
            },
            error: function(xhr) {
                if (typeof errorCallback === 'function') {
                    errorCallback(xhr);
                }
            }
        });
    }

    // function checkSession() {

    //     // Only check session if JWT exists in localStorage
    //     if (!localStorage.getItem("jwt")) {
    //         clearInterval(sessionCheckInterval);
    //         return; 
    //     }
    //     $.ajaxSetup({ global: false });
    //     url = "http://127.0.0.1/php_projects/Ollyo-Event-Management-System/"; 

    //     $.ajax({
    //         url: 'session-check.php', // The PHP file that checks session status
    //         method: 'POST',
    //         cache: false,
    //         success: function(response) {
    //             console.log(response);
    //             if (response.status === "expired") {
    //                 // Show SweetAlert before redirecting
    //                 Swal.fire({
    //                     icon: 'warning',
    //                     title: 'Session Expired',
    //                     text: response.message,
    //                     confirmButtonText: 'OK',
    //                     allowOutsideClick: false, // Prevent clicking outside to close
    //                     allowEscapeKey: false // Prevent ESC key from closing
    //                 }).then(() => {
    //                     window.location.href = url;
    //                 });
    //             } else if (response.status === "unauthorized") {
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Unauthorized',
    //                     text: response.message,
    //                     confirmButtonText: 'OK'
    //                 }).then(() => {
    //                     window.location.href = url;
    //                 });
    //             }
    //         },
    //         complete: function () {
    //             // Re-enable global AJAX events after the request (if needed)
    //             $.ajaxSetup({ global: true });
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("Error checking session:", error);
    //         }
    //     });
    // }
    
    // // **Check session every 1 minute**
    // let sessionCheckInterval = setInterval(function () {
    //     // Check if JWT exists in local storage before making session check request
    //     if (!localStorage.getItem("jwt")) {  
    //         clearInterval(sessionCheckInterval); // Stop checking session after logout
    //         return; // Exit function if no JWT is found (user is logged out)
    //     }
    
    //     // Call the session check function
    //     checkSession();
    
    // }, 5000);   
    

    
    
    // Get JWT from storage or force logout if expired
    function getTokenOrLogout() {
        if (isTokenExpired()) {
            url = "http://127.0.0.1/php_projects/Ollyo-Event-Management-System/"; 
            $.ajax({
                url: 'session-destroy.php', // The PHP script that destroys the session
                method: 'POST',
                success: function(response) {
                    // After successful session destruction, remove the JWT from localStorage
                    localStorage.removeItem("jwt");
            
                    // Show success message using SweetAlert
                    Swal.fire({
                        icon: 'warning',
                        title: 'Logged out!',
                        text: 'Your token has expired. Please login again.',
                        allowOutsideClick: false,
                        allowEscapeKey: false, 
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6',
                    }).then(() => {
                        // Redirect to the login page after the message is shown
                        window.location.href = url;
                    });
                },
                error: function(xhr, status, error) {
                    // Handle any error in session destruction
                    console.error('Error destroying session:', error);
                    
                    // Show error message using SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while logging you out. Please try again.',
                        timer: 3000, // Time before redirect (optional)
                        showConfirmButton: false // Don't show the confirm button
                    }).then(() => {
                        // Redirect to the login page after the message is shown
                        localStorage.removeItem("jwt");
                        window.location.href = url;
                    });
                }
            });
            
            return null;
        }
        return localStorage.getItem("jwt");
    }

    // Check if JWT is expired
    function isTokenExpired() {
        const token = localStorage.getItem("jwt");
        if (!token) return true;

        const decoded = parseJwt(token);
        if (!decoded || !decoded.exp) return true;

        return Date.now() >= decoded.exp * 1000;
    }

    function parseJwt(token) {
        try {
            const base64Url = token.split(".")[1];
            const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/"); //Converts Base64 URL-encoded JWT payload (- → +, _ → /) into standard Base64 before decoding. Ensures atob() works correctly, even if the token uses URL-safe Base64 encoding.
            return JSON.parse(atob(base64));
        } catch (e) {
            console.error("Invalid JWT:", e);
            return null;
        }
    }    

    $("#sidebarToggleBtn").on("click", function() {
        $("#sidebar").toggleClass("active");
    });

    // Add user button on click from admin users section
    $('#add_user').on('click', function() {
        $('#modal_add_user').modal('show');
    });

    // Add user form submit from admin users section
    $("#user_add_form").on("submit", function (e) {
        e.preventDefault();
        const userName = $("#user_name").val().trim();
        const userEmail = $("#user_email").val().trim();
        const userPass = $("#user_pass").val().trim();
        const isAdmin = $("#user_type").val().trim();
        const isActive = $("#user_active").val().trim();
        
        const payload = {
            "purpose": "user_creation",
            userName: userName,
            userEmail: userEmail,
            userPass: userPass,
            isAdmin: isAdmin,
            isActive: isActive
        };

        handleAjaxRequest(
            "../core/admin/manage-user.php",
            "POST",
            payload,
            function(data) { 
                $('#user_name').val('');
                $('#user_email').val('');
                $('#user_pass').val('');
                $('#user_type').val('');
                $('#user_active').val('');
                $('#modal_add_user').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'User Created',
                    text: data
                }).then(() => {
                    $("#order_data").DataTable().ajax.reload();
                });
            },
            function(xhr) {
                $('#user_name').val('');
                $('#user_email').val('');
                $('#user_pass').val('');
                $('#user_type').val('');
                $('#user_active').val('');
                $('#modal_add_user').modal('hide');
                let errorMessage = "An error occurred while processing your request.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'User Creation Failed',
                    text: errorMessage
                });
            },
            true
        );
    });

    // Edit user on click button event
    $(document).on('click', '.edit-user', function() { // Using event delegation instead of directly binding .on("click")
        let userId = $(this).data('id'); 
        let username = $(this).data('username');
        let email = $(this).data('email');
        let userType = $(this).data('type');
        let userStatus = $(this).data('status');
        
        $('#edit_user_id').val(userId);
        $('#edit_user_name').val(username);
        $('#edit_user_email').val(email);
        $('#edit_user_type').val(userType);
        $('#edit_user_active').val(userStatus);
    
        $('#user_edit_modal').modal('show');
    });
    
    // Edit user form submit from admin users section
    $("#user_edit_form").on("submit", function (e) {
        e.preventDefault();
        
        const userId = $("#edit_user_id").val().trim();
        const userName = $("#edit_user_name").val().trim();
        const userEmail = $("#edit_user_email").val().trim();
        const userPass = $("#edit_user_pass").val().trim();
        const isAdmin = $("#edit_user_type").val().trim();
        const isActive = $("#edit_user_active").val().trim();
        
        const payload = {
            "purpose": "user_update",
            userId: userId,
            userName: userName,
            userEmail: userEmail,
            userPass: userPass,
            isAdmin: isAdmin,
            isActive: isActive
        };

        handleAjaxRequest(
            "../core/admin/manage-user.php",
            "POST",
            payload,
            function(data) {
                $('#edit_user_id').val('');
                $('#edit_user_name').val('');
                $('#edit_user_email').val('');
                $('#edit_user_pass').val('');
                $('#edit_user_type').val('');
                $('#edit_user_active').val('');
                $('#user_edit_modal').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'User Updated',
                    text: data
                }).then(() => {
                    $("#order_data").DataTable().ajax.reload();
                });
            },
            function(xhr) {
                $('#edit_user_id').val('');
                $('#edit_user_name').val('');
                $('#edit_user_email').val('');
                $('#edit_user_pass').val('');
                $('#edit_user_type').val('');
                $('#edit_user_active').val('');
                $('#user_edit_modal').modal('hide');
                
                let errorMessage = "An error occurred while processing your request.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'User Update Failed',
                    text: errorMessage
                });
            },
            true
        );
    });

    // Delete user on click button event
    $(document).on("click", ".delete-user", function (e) { // Using event delegation instead of directly binding .on("click")
        e.preventDefault();
    
        let userId = $(this).data("id"); //$(this).attr("id");
    
        if (confirm("Are you sure you want to delete?")) {
            const payload = {
                "purpose": "user_delete",
                userId: userId
            };
    
            handleAjaxRequest(
                "../core/admin/manage-user.php",
                "POST",
                payload,
                function (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'User Deleted',
                        text: data
                    }).then(() => {
                        $("#order_data").DataTable().ajax.reload();
                    });
                },
                function (xhr) {
                    let errorMessage = "An error occurred while processing your request.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
    
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: errorMessage
                    });
                },
                true
            );
        }
    });  

    // Add event button on click from admin users section
    $('#add_event').on('click', function() {
        $('#event_add_modal').modal('show');
    });

    // Add event form submit from admin event section
    $("#add_event_form").on("submit", function (e) {
        e.preventDefault();

        let fileInput = $('#add_event_image')[0].files[0];

        if (!fileInput) {
            alert("Please select an image.");
            return;
        }

        let fileName = fileInput.name;
        let fileExt = fileName.split('.').pop().toLowerCase();

        if (jQuery.inArray(fileExt, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            alert("Invalid image file. Please select a GIF, PNG, JPG, or JPEG file.");
            return;
        }

        let fileSize = fileInput.size || fileInput.fileSize;
        if (fileSize > 2000000) {
            alert("Image file size is too large. Maximum allowed size is 2MB.");
            return;
        }
    
        let formData = new FormData(this);  // This will include both text fields and files
        formData.append("purpose", "event_creation");
        $('#add_event_form')[0].reset();
        $('#event_add_modal').modal('hide');
        handleAjaxRequestOnFormData(
            "../core/admin/manage-event.php",
            "POST",
            formData,  // Send the formData directly (without converting to JSON)
            function(data) {    
                Swal.fire({
                    icon: 'success',
                    title: 'Event Created',
                    text: data
                }).then(() => {
                    $("#event_data").DataTable().ajax.reload();
                });
            },
            function(xhr) {
                let errorMessage = "An error occurred while processing your request.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
    
                Swal.fire({
                    icon: 'error',
                    title: 'Event Creation Failed',
                    text: errorMessage
                });
            },
            true
        );
    });
    

    // Edit event on click button event
    $(document).on('click', '.edit-event', function() { // Using event delegation instead of directly binding .on("click")
        let eventId = $(this).data('id'); 
        let eventName = $(this).data('name');
        let eventDesc = $(this).data('description');
        let eventDate = $(this).data('time');
        let eventCapacity = $(this).data('capacity');
        let eventAttendeesCount = $(this).data('attendees');
        let eventAddress = $(this).data('address');

        $('#edit_event_id').val(eventId);
        $('#edit_event_name').val(eventName);
        $('#edit_event_date').val(eventDate);
        $('#edit_event_capacity').val(eventCapacity);
        $('#edit_event_attendees').val(eventAttendeesCount);
        $('#edit_event_location').val(eventAddress);
        $('#edit_event_description').val(eventDesc);

        $('#event_edit_modal').modal('show'); 
    });

    // Edit event form on submit event
    $("#edit_event_form").on("submit", function (e) {
        e.preventDefault();

        let fileInput = $('#edit_event_image')[0].files[0];

        if (fileInput) {
            let fileName = fileInput.name;
            let fileExt = fileName.split('.').pop().toLowerCase();
    
            if (jQuery.inArray(fileExt, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                alert("Invalid image file. Please select a GIF, PNG, JPG, or JPEG file.");
                return;
            }
    
            let fileSize = fileInput.size || fileInput.fileSize;
            if (fileSize > 2000000) {
                alert("Image file size is too large. Maximum allowed size is 2MB.");
                return;
            }
        }

        let formData = new FormData(this); 
        formData.append("purpose", "event_update"); 
        
        $('#edit_event_form')[0].reset();
        $('#event_edit_modal').modal('hide');
        handleAjaxRequestOnFormData(
            "../core/admin/manage-event.php",
            "POST",
            formData,  // Send the formData directly (without converting to JSON)
            function(data) {    
                Swal.fire({
                    icon: 'success',
                    title: 'Event Updated',
                    text: data
                }).then(() => {
                    $("#order_data").DataTable().ajax.reload();
                });
            },
            function(xhr) {
                let errorMessage = "An error occurred while processing your request.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
    
                Swal.fire({
                    icon: 'error',
                    title: 'Event Update Failed',
                    text: errorMessage
                });
            },
            true
        );
    });
    
    // Delete event on click from admin events page
    $(document).on("click", ".delete-event", function (e) { // Using event delegation instead of directly binding .on("click")
        e.preventDefault();
    
        let eventId = $(this).data("id"); //$(this).attr("id");
    
        if (confirm("Are you sure you want to delete?")) {
            let formData = new FormData();
            formData.append("purpose", "event_delete");
            formData.append("event_id", eventId);
    
            handleAjaxRequestOnFormData(
                "../core/admin/manage-event.php",
                "POST",
                formData,
                function (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Event Deleted',
                        text: data
                    }).then(() => {
                        $("#order_data").DataTable().ajax.reload();
                    });
                },
                function (xhr) {
                    let errorMessage = "An error occurred while processing your request.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
    
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: errorMessage
                    });
                },
                true
            );
        }
    });  

    // Add attendee button on click from admin attendees menu
    $('#add_attendee').on('click', function() {
        $('#modal_add_attendee').modal('show');
    });

    // Add attendee form submit from admin attendees menu
    $("#add_attendee_form").on("submit", function (e) {
        e.preventDefault();
        const attendeeName = $("#add_attendee_name").val().trim();
        const attendeeEmail = $("#add_attendee_email").val().trim();
        const attendeeEventId = $("#add_attendee_event_id").val().trim();
        
        const payload = {
            "purpose": "attendee_creation",
            attendeeName: attendeeName,
            attendeeEmail: attendeeEmail,
            attendeeEventId: attendeeEventId
        };
        $('#add_attendee_name').val('');
        $('#add_attendee_email').val('');
        $('#add_attendee_event_id').val('');

        $('#modal_add_attendee').modal('hide');
        
        handleAjaxRequest(
            "../core/admin/manage-attendees.php",
            "POST",
            payload,
            function(data) { 
                Swal.fire({
                    icon: 'success',
                    title: 'Attendee Created',
                    text: data
                }).then(() => {
                    $("#order_data").DataTable().ajax.reload();
                });
            },
            function(xhr) {
                let errorMessage = "An error occurred while processing your request.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Attendee Creation Failed',
                    text: errorMessage
                });
            },
            true
        );
    });

    // Edit attendee on click button event from admin attendees menu
    $(document).on('click', '.edit-attendee', function() { 
        let attendeeId = $(this).data('id'); 
        let attendeeName = $(this).data('name');
        let attendeeEmail = $(this).data('email');
        let attendeeEvent = $(this).data('event');
    
        $('#edit_attendee_id').val(attendeeId);
        $('#edit_attendee_name').val(attendeeName);
        $('#edit_attendee_email').val(attendeeEmail);
        $('#edit_attendee_event_id').val(attendeeEvent);
        $('#edit_attendee_prev_event_id').val(attendeeEvent);
        
        $('#modal_edit_attendee').modal('show');
    });
    
    // Edit attendee form submit from admin attendees menu
    $("#edit_attendee_form").on("submit", function (e) {
        e.preventDefault();
        
        const attendeeId = $("#edit_attendee_id").val().trim();
        const attendeeName = $("#edit_attendee_name").val().trim();
        const attendeeEmail = $("#edit_attendee_email").val().trim();
        const attendeeEventId = $("#edit_attendee_event_id").val().trim();
        const attendeePrevEvent = $("#edit_attendee_prev_event_id").val().trim();
        
        const payload = {
            "purpose": "attendee_update",
            attendeeId: attendeeId,
            attendeeName: attendeeName,
            attendeeEmail: attendeeEmail,
            attendeeEventId: attendeeEventId,
            attendeePrevEvent: attendeePrevEvent
        };
        
        $('#edit_attendee_id').val('');
        $('#edit_attendee_name').val('');
        $('#edit_attendee_email').val('');
        $('#edit_attendee_event_id').val('');

        $('#modal_edit_attendee').modal('hide');
        
        handleAjaxRequest(
            "../core/admin/manage-attendees.php",
            "POST",
            payload,
            function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Attendee Updated',
                    text: data
                }).then(() => {
                    $("#order_data").DataTable().ajax.reload();
                });
            },
            function(xhr) {                
                let errorMessage = "An error occurred while processing your request.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'User Update Failed',
                    text: errorMessage
                });
            },
            true
        );
    });

    // Delete attendee on click button event from admin attendees menu
    $(document).on("click", ".delete-attendee", function (e) {
        e.preventDefault();
    
        let attendeeId = $(this).data("id"); //$(this).attr("id");
    
        if (confirm("Are you sure you want to delete?")) {
            const payload = {
                "purpose": "attendee_delete",
                attendeeId: attendeeId
            };
    
            handleAjaxRequest(
                "../core/admin/manage-attendees.php",
                "POST",
                payload,
                function (data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Attendee Deleted',
                        text: data
                    }).then(() => {
                        $("#order_data").DataTable().ajax.reload();
                    });
                },
                function (xhr) {
                    let errorMessage = "An error occurred while processing your request.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
    
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: errorMessage
                    });
                },
                true
            );
        }
    });  

    // Change Password Modal
    $("#openChangePasswordModal").on("click", function(e){
        e.preventDefault();
        $("#changePasswordModal").modal("show");
    });

    // Submit Change Password Form
    $("#changePasswordForm").on("submit", function(e){
        e.preventDefault();

        let currentPassword = $("#currentPassword").val().trim();
        let newPassword = $("#newPassword").val().trim();
        let confirmPassword = $("#confirmPassword").val().trim();

        if(newPassword !== confirmPassword) {
            alert("New passwords do not match!");
            return;
        }
        $("#changePasswordModal").modal("hide");
        $("#changePasswordForm")[0].reset();
        
        $.ajax({
            url: "../core/admin/update-password.php",
            type: "POST",
            data: {
                currentPassword: currentPassword,
                newPassword: newPassword
            },
            success: function(response) {
                alert(response);
            }
        });
    });

    // Change Photo Modal
    $("#openProfileImageModal").on("click", function(e){
        e.preventDefault();
        $("#profileImageModal").modal("show");
    });

    // Profile image submit event
    $("#profileImageForm").on("submit", function(e){
        e.preventDefault();
        
        let formData = new FormData(this);

        $.ajax({
            url: "../core/admin/upload-profile-image.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);
                location.reload();
            }
        });
    });
    

});
