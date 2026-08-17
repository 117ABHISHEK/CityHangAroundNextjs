@php 
    $auth_title = get_phrase('Login');
    $meta_description = 'Log in to your City Hang Around account to discover events, lists, and local favorites.';
@endphp
@include('auth.layout.header')

<style>
    /* Premium Login Style Override */
    .auth-container {
        max-width: 1060px;
        width: 100%;
        margin: 40px auto;
        padding: 0 20px;
    }

    .auth-card {
        background: var(--card-bg);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.04);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    /* Left panel: Ambient visual display */
    .auth-visual-panel {
        background: linear-gradient(135deg, rgba(255, 73, 57, 0.03) 0%, rgba(255, 140, 0, 0.05) 100%);
        border-right: 1px solid rgba(226, 232, 240, 0.8);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px 48px;
        position: relative;
        overflow: hidden;
    }

    .auth-visual-content {
        position: relative;
        z-index: 2;
    }

    .auth-tagline {
        font-size: 13.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--primary);
        margin-bottom: 12px;
        display: inline-block;
    }

    .auth-visual-panel h2 {
        font-size: 34px;
        font-weight: 700;
        line-height: 1.25;
        color: #0f172a;
        margin-bottom: 20px;
    }

    .auth-visual-panel h2 span {
        color: var(--primary);
        background: linear-gradient(135deg, #ff6b4a 0%, #ff4939 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .auth-features {
        margin-top: 32px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .feature-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 73, 57, 0.08);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .feature-text h5 {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 3px 0;
    }

    .feature-text p {
        font-size: 13.5px;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.4;
    }

    .auth-hero-img-wrap {
        margin-top: 40px;
        display: flex;
        justify-content: center;
        position: relative;
    }

    .auth-hero-img-wrap img {
        max-height: 320px;
        object-fit: contain;
        filter: drop-shadow(0 15px 30px rgba(255, 73, 57, 0.08));
        animation: subtlePulse 6s infinite alternate ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .auth-hero-img-wrap img {
            animation: none;
        }
    }

    @keyframes subtlePulse {
        0% { transform: translateY(0); }
        100% { transform: translateY(-8px); }
    }

    /* Right panel: Focused Form */
    .auth-form-panel {
        padding: 60px 48px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .auth-form-header {
        margin-bottom: 32px;
    }

    .auth-form-header h3 {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .auth-form-header p {
        font-size: 14.5px;
        color: var(--text-muted);
        margin: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #334155;
        margin-bottom: 8px;
    }

    /* Custom Input Wrapper */
    .custom-input-wrapper {
        position: relative;
    }

    .custom-input-wrapper .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        transition: color 0.25s ease;
        font-size: 15px;
    }

    .custom-input-wrapper .form-control {
        width: 100%;
        padding: 12px 16px 12px 46px !important;
        border: 1.5px solid var(--border) !important;
        border-radius: 12px !important;
        font-size: 15px !important;
        color: #1e293b !important;
        background: rgba(255, 255, 255, 0.6) !important;
        outline: none !important;
        box-shadow: none !important;
        height: auto !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .custom-input-wrapper .form-control::placeholder {
        color: #94a3b8;
    }

    .custom-input-wrapper .form-control:focus {
        border-color: var(--primary) !important;
        background: #fff !important;
        box-shadow: 0 0 0 4px var(--primary-glow) !important;
    }

    .custom-input-wrapper .form-control:focus + .input-icon {
        color: var(--primary);
    }

    /* Password visibility toggle */
    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        cursor: pointer;
        font-size: 15px;
        transition: color 0.25s ease;
        z-index: 10;
    }

    .password-toggle:hover {
        color: var(--primary);
    }

    /* Remember me & Forgot Password Row */
    .auth-options-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0 24px 0;
    }

    .form-check {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        min-height: auto;
        padding-left: 0;
    }

    .form-check-input {
        width: 16px;
        height: 16px;
        margin: 0 !important;
        border: 1.5px solid var(--border);
        border-radius: 4px;
        cursor: pointer;
        outline: none;
        transition: all 0.2s ease;
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .form-check-label {
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
        user-select: none;
    }

    .forgot-link {
        font-size: 14px;
        font-weight: 500;
        color: var(--primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .forgot-link:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    /* Actions */
    .btn-submit {
        background: linear-gradient(135deg, #ff6b4a 0%, #ff4939 100%);
        border: none;
        padding: 13px 20px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15.5px;
        color: #fff;
        width: 100%;
        box-shadow: 0 6px 16px var(--primary-glow);
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #ff7c5e 0%, #e03d2f 100%);
        box-shadow: 0 8px 22px rgba(255, 73, 57, 0.3);
        transform: translateY(-1px);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* Divider */
    .auth-divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #94a3b8;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 28px 0;
    }

    .auth-divider::before, .auth-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1.5px solid #f1f5f9;
    }

    .auth-divider:not(:empty)::before {
        margin-right: 16px;
    }

    .auth-divider:not(:empty)::after {
        margin-left: 16px;
    }

    /* Social row */
    .social-row {
        display: flex;
        gap: 12px;
    }

    .btn-social {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border: 1.5px solid var(--border);
        background: #fff;
        padding: 11px 16px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 14px;
        color: #334155;
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-social i {
        font-size: 16px;
    }

    .btn-social.google i {
        color: #ea4335;
    }

    .btn-social.facebook i {
        color: #1877f2;
    }

    .btn-social:hover {
        background: #f8fafc;
        border-color: var(--border-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        color: #1e293b;
    }

    /* Bottom prompt */
    .auth-bottom-prompt {
        text-align: center;
        margin-top: 32px;
        font-size: 14.5px;
        color: var(--text-muted);
    }

    .auth-bottom-prompt a {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
        margin-left: 4px;
        transition: color 0.2s ease;
    }

    .auth-bottom-prompt a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    /* Error presentation */
    .error-msg {
        font-size: 13px;
        font-weight: 500;
        color: #ef4444;
        margin: 4px 0 0 0;
    }

    /* Custom alert */
    .alert-premium {
        background: #fffbeb;
        border: 1.5px solid #fef3c7;
        color: #b45309;
        border-radius: 12px;
        font-size: 14px;
        padding: 12px 16px;
        margin-bottom: 24px;
    }

    /* Responsiveness */
    @media (max-width: 991px) {
        .auth-visual-panel {
            display: none !important;
        }
        .auth-form-panel {
            padding: 40px 24px;
        }
        .auth-container {
            margin: 20px auto;
        }
    }
</style>

<main class="main">
    <div class="container auth-container">
        <div class="row auth-card g-0">
            <!-- Left Column: Premium visual display -->
            <div class="col-lg-6 auth-visual-panel">
                <div class="auth-visual-content">
                    <span class="auth-tagline">Welcome Back</span>
                    <h2>Discover the Best of Your <span>City</span></h2>
                    
                    <div class="auth-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Explore Local Venues</h5>
                                <p>Find top-rated businesses, services, and attractions near you.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Exclusive Hot Deals</h5>
                                <p>Access special discounts and deals from local merchants.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Community Events</h5>
                                <p>Stay updated on upcoming gatherings, events, and local news.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="auth-hero-img-wrap">
                    <img class="img-fluid" src="{{ asset('assets/frontend/images/login_hero.webp') }}" 
                         alt="City Guide Illustration" loading="eager" width="400">
                </div>
            </div>

            <!-- Right Column: Login Form -->
            <div class="col-lg-6 auth-form-panel">
                <div class="auth-form-header">
                    <h3>{{ get_phrase('Log In') }}</h3>
                    <p>Enter your details to access your account</p>
                </div>

                @if($message = Session::get('error_message'))
                    <div class="alert alert-premium alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>{{ get_phrase('Public sign up is not allowed') }}!</strong> 
                        {{ get_phrase('You should contact the site administrator') }}.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email">{{ get_phrase('Email') }}</label>
                        <div class="custom-input-wrapper">
                            <input type="email" id="email" name="email" class="form-control"
                                   value="{{ old('email') }}" 
                                   placeholder="{{ get_phrase('Enter your email address') }}" required autofocus>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @if($errors->has('email'))
                            <p class="error-msg"><i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password">{{ get_phrase('Password') }}</label>
                        <div class="custom-input-wrapper">
                            <input type="password" id="password" name="password" class="form-control"
                                   placeholder="{{ get_phrase('Your password') }}" required>
                            <i class="fas fa-lock input-icon"></i>
                            <span class="password-toggle" onclick="togglePassword()">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </span>
                        </div>
                        @if($errors->has('password'))
                            <p class="error-msg"><i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Options Row: Remember Me & Forgot Password -->
                    <div class="auth-options-row">
                        <div class="form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label class="form-check-label" for="remember_me">{{ get_phrase('Remember me') }}</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                {{ get_phrase('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">
                        {{ get_phrase('Log In') }}
                    </button>
                </form>

                <!-- Social Divider -->
                <div class="auth-divider">Or login with</div>

                <!-- Social Buttons -->
                <div class="social-row">
                    <a href="{{ route('auth.google') }}" class="btn-social google">
                        <i class="fab fa-google"></i> Google
                    </a>
                    <a href="{{ route('facebook.login') }}" class="btn-social facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                </div>

                <!-- Sign up Link -->
                @if (Route::has('register'))
                    <div class="auth-bottom-prompt">
                        <span>{{ get_phrase("Don't have an account?") }}</span>
                        <a href="{{ route('register') }}">
                            {{ get_phrase('Sign Up') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<script>
    function togglePassword() {
        let passwordField = document.getElementById("password");
        let eyeIcon = document.getElementById("eyeIcon");

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
</script>

@include('auth.layout.footer')
