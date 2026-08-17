<!-- Login/Signup Modal -->
@php
    $public_signup_enabled = get_settings('public_signup') == 1;
@endphp
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0 justify-content-end align-items-center px-3 pt-3">
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body pt-0 px-4 pb-4">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs justify-content-center" id="authTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane" aria-selected="true">
                            <i class="fas fa-sign-in-alt me-2"></i>{{ get_phrase('Log In') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-tab-pane" type="button" role="tab" aria-controls="register-tab-pane" aria-selected="false">
                            <i class="fas fa-user-plus me-2"></i>{{ get_phrase('Sign Up') }}
                        </button>
                    </li>
                </ul>

                <!-- Alert box for generic error messages -->
                <div id="modalAlert" class="alert alert-danger d-none alert-dismissible fade show" role="alert">
                    <span id="modalAlertMessage"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="authTabsContent">
                    <!-- Login Pane -->
                    <div class="tab-pane fade show active" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                        <form id="modalLoginForm" method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Input with Icon -->
                            <div class="form-group mb-3">
                                <label for="modalEmail" class="form-label font-medium mb-1" style="font-size: 14px; color: #475569;">{{ get_phrase('Email') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" id="modalEmail" name="email" class="form-control"
                                           placeholder="{{ get_phrase('Enter your email address') }}" required autocomplete="email">
                                </div>
                                <p class="text-danger mb-0 mt-1" id="errorModalEmail" style="font-size: 12px;"></p>
                            </div>

                            <!-- Password Input with Show/Hide Toggle -->
                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="modalPassword" class="form-label font-medium mb-0" style="font-size: 14px; color: #475569;">{{ get_phrase('Password') }}</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" style="font-size: 12px; color: #ff4939; text-decoration: none;" class="hover-underline">
                                            {{ get_phrase('Forgot password?') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" id="modalPassword" name="password" class="form-control"
                                           placeholder="{{ get_phrase('Your password') }}" required autocomplete="current-password">
                                    <span class="input-group-text toggle-password cursor-pointer" onclick="toggleModalPassword('modalPassword', 'modalEyeIcon')" style="cursor: pointer;">
                                        <i id="modalEyeIcon" class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <p class="text-danger mb-0 mt-1" id="errorModalPassword" style="font-size: 12px;"></p>
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-4">
                                <input id="modalRememberMe" type="checkbox" class="form-check-input" name="remember">
                                <label class="form-check-label" for="modalRememberMe" style="font-size: 13px; color: #64748b; cursor: pointer;">{{ get_phrase('Remember me') }}</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="modalSubmitBtn" class="btn btn-primary w-100 d-flex justify-content-center align-items-center gap-2">
                                <span class="spinner-border spinner-border-sm d-none" id="modalSubmitSpinner" role="status" aria-hidden="true"></span>
                                <span id="modalSubmitText">{{ get_phrase('Log In') }}</span>
                            </button>
                        </form>
                    </div>

                    <!-- Register Pane -->
                    <div class="tab-pane fade" id="register-tab-pane" role="tabpanel" aria-labelledby="register-tab" tabindex="0">
                        @if($public_signup_enabled)
                            <form id="modalRegisterForm" method="POST" action="{{ route('register') }}">
                                @csrf

                                <!-- Name Input with Icon -->
                                <div class="form-group mb-3">
                                    <label for="modalRegName" class="form-label font-medium mb-1" style="font-size: 14px; color: #475569;">{{ get_phrase('Full Name') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" id="modalRegName" name="name" class="form-control"
                                               placeholder="{{ get_phrase('Your full name') }}" required autocomplete="name">
                                    </div>
                                    <p class="text-danger mb-0 mt-1" id="errorModalRegName" style="font-size: 12px;"></p>
                                </div>

                                <!-- Email Input with Icon -->
                                <div class="form-group mb-3">
                                    <label for="modalRegEmail" class="form-label font-medium mb-1" style="font-size: 14px; color: #475569;">{{ get_phrase('Email') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" id="modalRegEmail" name="email" class="form-control"
                                               placeholder="{{ get_phrase('Enter your email address') }}" required autocomplete="email">
                                    </div>
                                    <p class="text-danger mb-0 mt-1" id="errorModalRegEmail" style="font-size: 12px;"></p>
                                </div>

                                <!-- Password Input with Show/Hide Toggle -->
                                <div class="form-group mb-3">
                                    <label for="modalRegPassword" class="form-label font-medium mb-1" style="font-size: 14px; color: #475569;">{{ get_phrase('Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" id="modalRegPassword" name="password" class="form-control"
                                               placeholder="{{ get_phrase('Your password') }}" required autocomplete="new-password">
                                        <span class="input-group-text toggle-password cursor-pointer" onclick="toggleModalPassword('modalRegPassword', 'modalRegEyeIcon1')" style="cursor: pointer;">
                                            <i id="modalRegEyeIcon1" class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                    <p class="text-danger mb-0 mt-1" id="errorModalRegPassword" style="font-size: 12px;"></p>
                                </div>

                                <!-- Confirm Password Input with Show/Hide Toggle -->
                                <div class="form-group mb-3">
                                    <label for="modalRegConfirmPassword" class="form-label font-medium mb-1" style="font-size: 14px; color: #475569;">{{ get_phrase('Confirm Password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" id="modalRegConfirmPassword" name="password_confirmation" class="form-control"
                                               placeholder="{{ get_phrase('Confirm password') }}" required autocomplete="new-password">
                                        <span class="input-group-text toggle-password cursor-pointer" onclick="toggleModalPassword('modalRegConfirmPassword', 'modalRegEyeIcon2')" style="cursor: pointer;">
                                            <i id="modalRegEyeIcon2" class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Timezone Hidden Input -->
                                <input type="hidden" name="timezone" id="modalRegTimezone" value="">

                                <!-- Terms and Conditions Checkbox -->
                                <div class="form-check mb-4">
                                    <input type="checkbox" class="form-check-input" name="check1" id="modalRegTerms" required>
                                    <label class="form-check-label" for="modalRegTerms" style="font-size: 13px; color: #64748b; cursor: pointer;">
                                        {{ get_phrase('I accept the') }} 
                                        <a href="{{ route('term.view') }}" target="_blank" style="color: #ff4939; text-decoration: none;" class="hover-underline">{{ get_phrase('Terms and Conditions') }}</a>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" id="modalRegSubmitBtn" class="btn btn-primary w-100 d-flex justify-content-center align-items-center gap-2">
                                    <span class="spinner-border spinner-border-sm d-none" id="modalRegSubmitSpinner" role="status" aria-hidden="true"></span>
                                    <span id="modalRegSubmitText">{{ get_phrase('Sign Up') }}</span>
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning text-center py-4 my-2">
                                <i class="fas fa-exclamation-triangle fa-2x mb-3 text-warning"></i>
                                <h5>{{ get_phrase('Registration Disabled') }}</h5>
                                <p class="text-muted mb-0" style="font-size: 14px;">{{ get_phrase('Public sign up is currently disabled by the site administrator.') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Social Login Section -->
                <div class="social-login mt-4">
                    <div class="position-relative text-center mb-3">
                        <hr class="my-0" style="border-color: #cbd5e1;" />
                        <span class="position-absolute translate-middle px-3 bg-white text-muted font-medium" style="font-size: 12px; top: 50%; font-family: 'Poppins', sans-serif;">
                            {{ get_phrase('Or connect with') }}
                        </span>
                    </div>

                    <div class="row g-2 justify-content-center mt-2">
                        <div class="col-12 col-md-6 d-grid">
                            <a href="{{ route('auth.google') }}" class="btn btn-google d-flex align-items-center justify-content-center gap-2">
                                <i class="fab fa-google text-danger"></i> {{ get_phrase('Google') }}
                            </a>
                        </div>
                        <div class="col-12 col-md-6 d-grid">
                            <a href="{{ route('facebook.login') }}" class="btn btn-facebook d-flex align-items-center justify-content-center gap-2">
                                <i class="fab fa-facebook-f"></i> {{ get_phrase('Facebook') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Backdrop blur styling */
    .modal-backdrop.show {
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        background-color: rgba(15, 23, 42, 0.4) !important;
    }

    #loginModal .modal-content {
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: #ffffff;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    /* Modern Premium Tabs Styling */
    #loginModal .nav-tabs {
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 24px;
    }

    #loginModal .nav-tabs .nav-item {
        flex: 1;
        text-align: center;
    }

    #loginModal .nav-tabs .nav-link {
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 600;
        font-size: 15px;
        padding: 12px 16px;
        width: 100%;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
    }

    #loginModal .nav-tabs .nav-link:hover {
        color: #ff4939;
        border-bottom-color: rgba(255, 73, 57, 0.2);
    }

    #loginModal .nav-tabs .nav-link.active {
        color: #ff4939;
        border-bottom: 2px solid #ff4939;
    }

    #loginModal .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        padding: 10px 14px;
        transition: all 0.2s ease;
        box-shadow: none;
        color: #334155;
    }

    #loginModal .form-control:focus {
        border-color: #ff4939;
        box-shadow: 0 0 0 3px rgba(255, 73, 57, 0.12);
        background-color: #ffffff;
    }

    #loginModal .input-group-text {
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        color: #94a3b8;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    #loginModal .input-group:focus-within .input-group-text {
        border-color: #ff4939;
        color: #ff4939;
    }

    #loginModal .btn-primary {
        background-color: #ff4939;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.2s ease;
        color: #ffffff;
    }

    #loginModal .btn-primary:hover {
        background-color: #e03d2f;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 73, 57, 0.25);
    }

    #loginModal .btn-primary:active {
        transform: translateY(0);
        box-shadow: none;
    }

    #loginModal .btn-google {
        background-color: #ffffff;
        color: #334155;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px;
        font-size: 14px;
        font-weight: 550;
        transition: all 0.2s ease;
    }

    #loginModal .btn-google:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #1e293b;
    }

    #loginModal .btn-facebook {
        background-color: #1877f2;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 10px;
        font-size: 14px;
        font-weight: 550;
        transition: all 0.2s ease;
    }

    #loginModal .btn-facebook:hover {
        background-color: #166fe5;
        box-shadow: 0 4px 12px rgba(24, 119, 242, 0.25);
    }

    #loginModal .hover-underline:hover {
        text-decoration: underline !important;
    }

    /* Modal animation scaling */
    #loginModal.modal.fade .modal-dialog {
        transform: scale(0.95);
        transition: transform 0.25s ease-out;
    }

    #loginModal.modal.show .modal-dialog {
        transform: scale(1);
    }

    .btn-close-custom {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        font-size: 16px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
        flex-shrink: 0;
    }

    .btn-close-custom:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: rotate(90deg);
    }

    .btn-close-custom:focus {
        outline: 2px solid #0d6efd;
        outline-offset: 2px;
    }
</style>

<script>
    // Global function to trigger modal open
    function openLoginModal(event, tabType) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Hide sidebar menu if it is open on mobile
        let sidebar = document.getElementById("sidebar");
        if (sidebar && sidebar.classList.contains("active")) {
            sidebar.classList.remove("active");
        }

        // Switch to the correct tab if requested
        if (tabType === 'register') {
            let regTab = document.getElementById('register-tab');
            if (regTab) {
                let tab = bootstrap.Tab.getOrCreateInstance(regTab);
                tab.show();
            }
        } else {
            let loginTab = document.getElementById('login-tab');
            if (loginTab) {
                let tab = bootstrap.Tab.getOrCreateInstance(loginTab);
                tab.show();
            }
        }

        // Initialize and show modal safely using getOrCreateInstance
        let loginModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('loginModal'), {
            keyboard: true
        });
        loginModal.show();
    }

    // Toggle Password visibility for modal
    function toggleModalPassword(fieldId, iconId) {
        let passwordField = document.getElementById(fieldId);
        let eyeIcon = document.getElementById(iconId);

        if (passwordField && eyeIcon) {
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    }

    // Handle AJAX Form Submission & Modal Events
    document.addEventListener("DOMContentLoaded", function() {
        // Set timezone in registration form
        let tzInput = document.getElementById("modalRegTimezone");
        if (tzInput) {
            try {
                tzInput.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
            } catch(e) {
                console.warn("Could not determine timezone:", e);
            }
        }

        // Handle Modal Close Reset
        let modalElement = document.getElementById('loginModal');
        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', function () {
                // Clear validation errors for Login
                document.getElementById("errorModalEmail").innerText = "";
                document.getElementById("errorModalPassword").innerText = "";
                
                // Clear validation errors for Register
                let regNameErr = document.getElementById("errorModalRegName");
                let regEmailErr = document.getElementById("errorModalRegEmail");
                let regPassErr = document.getElementById("errorModalRegPassword");
                if (regNameErr) regNameErr.innerText = "";
                if (regEmailErr) regEmailErr.innerText = "";
                if (regPassErr) regPassErr.innerText = "";
                
                let alertBox = document.getElementById("modalAlert");
                if (alertBox) {
                    alertBox.classList.add("d-none");
                    document.getElementById("modalAlertMessage").innerText = "";
                }

                // Reset the forms
                let loginForm = document.getElementById("modalLoginForm");
                let registerForm = document.getElementById("modalRegisterForm");
                if (loginForm) loginForm.reset();
                if (registerForm) registerForm.reset();

                // Reset Submit Button States for Login
                let submitBtn = document.getElementById("modalSubmitBtn");
                let submitText = document.getElementById("modalSubmitText");
                let spinner = document.getElementById("modalSubmitSpinner");
                if (submitBtn) submitBtn.disabled = false;
                if (spinner) spinner.classList.add("d-none");
                if (submitText) submitText.innerText = "{{ get_phrase('Log In') }}";

                // Reset Submit Button States for Register
                let regSubmitBtn = document.getElementById("modalRegSubmitBtn");
                let regSubmitText = document.getElementById("modalRegSubmitText");
                let regSpinner = document.getElementById("modalRegSubmitSpinner");
                if (regSubmitBtn) regSubmitBtn.disabled = false;
                if (regSpinner) regSpinner.classList.add("d-none");
                if (regSubmitText) regSubmitText.innerText = "{{ get_phrase('Sign Up') }}";

                // Reset password input type to password and toggle icons
                let passwordFields = ['modalPassword', 'modalRegPassword', 'modalRegConfirmPassword'];
                let eyeIcons = ['modalEyeIcon', 'modalRegEyeIcon1', 'modalRegEyeIcon2'];
                passwordFields.forEach(function(fid) {
                    let pf = document.getElementById(fid);
                    if (pf) pf.type = "password";
                });
                eyeIcons.forEach(function(iid) {
                    let icon = document.getElementById(iid);
                    if (icon) {
                        icon.classList.remove("fa-eye-slash");
                        icon.classList.add("fa-eye");
                    }
                });
            });
        }

        // Login AJAX handler
        let loginForm = document.getElementById("modalLoginForm");
        if (loginForm) {
            loginForm.addEventListener("submit", function(e) {
                e.preventDefault();

                // Reset Error states
                document.getElementById("errorModalEmail").innerText = "";
                document.getElementById("errorModalPassword").innerText = "";
                
                let alertBox = document.getElementById("modalAlert");
                alertBox.classList.add("d-none");
                document.getElementById("modalAlertMessage").innerText = "";

                // Disable Submit Button and show loading
                let submitBtn = document.getElementById("modalSubmitBtn");
                let submitText = document.getElementById("modalSubmitText");
                let spinner = document.getElementById("modalSubmitSpinner");

                submitBtn.disabled = true;
                spinner.classList.remove("d-none");
                submitText.innerText = "{{ get_phrase('Logging In...') }}";

                // Prepare Form Data
                let formData = new FormData(loginForm);

                // Perform Fetch/AJAX call
                fetch(loginForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect/reload on successful login
                        window.location.reload();
                    } else if (response.status === 422) {
                        // Validation failed
                        return response.json().then(data => {
                            if (data.errors) {
                                if (data.errors.email) {
                                    document.getElementById("errorModalEmail").innerText = data.errors.email[0];
                                }
                                if (data.errors.password) {
                                    document.getElementById("errorModalPassword").innerText = data.errors.password[0];
                                }
                            }
                            throw new Error("Validation Failed");
                        });
                    } else {
                        return response.json()
                            .catch(() => ({ message: "An unexpected error occurred. Please try again." }))
                            .then(data => {
                                throw new Error(data.message || "An unexpected error occurred. Please try again.");
                            });
                    }
                })
                .catch(error => {
                    console.error("Login AJAX Error:", error);
                    
                    if (error.message !== "Validation Failed") {
                        alertBox.classList.remove("d-none");
                        document.getElementById("modalAlertMessage").innerText = error.message;
                    }

                    // Reset button states
                    submitBtn.disabled = false;
                    spinner.classList.add("d-none");
                    submitText.innerText = "{{ get_phrase('Log In') }}";
                });
            });
        }

        // Register AJAX handler
        let registerForm = document.getElementById("modalRegisterForm");
        if (registerForm) {
            registerForm.addEventListener("submit", function(e) {
                e.preventDefault();

                // Reset Error states
                document.getElementById("errorModalRegName").innerText = "";
                document.getElementById("errorModalRegEmail").innerText = "";
                document.getElementById("errorModalRegPassword").innerText = "";
                
                let alertBox = document.getElementById("modalAlert");
                alertBox.classList.add("d-none");
                document.getElementById("modalAlertMessage").innerText = "";

                // Disable Submit Button and show loading
                let submitBtn = document.getElementById("modalRegSubmitBtn");
                let submitText = document.getElementById("modalRegSubmitText");
                let spinner = document.getElementById("modalRegSubmitSpinner");

                submitBtn.disabled = true;
                spinner.classList.remove("d-none");
                submitText.innerText = "{{ get_phrase('Signing Up...') }}";

                // Prepare Form Data
                let formData = new FormData(registerForm);

                // Perform Fetch/AJAX call
                fetch(registerForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Redirect/reload on successful registration
                        window.location.reload();
                    } else if (response.status === 422) {
                        // Validation failed
                        return response.json().then(data => {
                            if (data.errors) {
                                if (data.errors.name) {
                                    document.getElementById("errorModalRegName").innerText = data.errors.name[0];
                                }
                                if (data.errors.email) {
                                    document.getElementById("errorModalRegEmail").innerText = data.errors.email[0];
                                }
                                if (data.errors.password) {
                                    document.getElementById("errorModalRegPassword").innerText = data.errors.password[0];
                                }
                            }
                            throw new Error("Validation Failed");
                        });
                    } else {
                        return response.json()
                            .catch(() => ({ message: "An unexpected error occurred. Please try again." }))
                            .then(data => {
                                throw new Error(data.message || "An unexpected error occurred. Please try again.");
                            });
                    }
                })
                .catch(error => {
                    console.error("Register AJAX Error:", error);
                    
                    if (error.message !== "Validation Failed") {
                        alertBox.classList.remove("d-none");
                        document.getElementById("modalAlertMessage").innerText = error.message;
                    }

                    // Reset button states
                    submitBtn.disabled = false;
                    spinner.classList.add("d-none");
                    submitText.innerText = "{{ get_phrase('Sign Up') }}";
                });
            });
        }
    });

    // Timed Popup Trigger for Guests
    @guest
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            // Do not trigger popup on auth/admin pages
            let pathname = window.location.pathname;
            if (pathname.indexOf('/admin') !== -1 || 
                pathname.indexOf('/login') !== -1 || 
                pathname.indexOf('/register') !== -1 || 
                pathname.indexOf('/forgot-password') !== -1 || 
                pathname.indexOf('/reset-password') !== -1) {
                return;
            }

            // Check if shown in this browser session
            if (!sessionStorage.getItem('login_popup_shown')) {
                setTimeout(function() {
                    // Check if modal is already open
                    let modalEl = document.getElementById('loginModal');
                    if (modalEl && !modalEl.classList.contains('show')) {
                        console.log("Automatically opening Login/Signup popup after 7.5s...");
                        openLoginModal(null);
                        sessionStorage.setItem('login_popup_shown', 'true');
                    }
                }, 7500); // 7.5 seconds
            }
        });
    })();
    @endguest
</script>