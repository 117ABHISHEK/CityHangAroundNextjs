@php 
    $auth_title = get_phrase('Sign Up');
    $meta_description = 'Create a new account on City Hang Around to explore custom local business listings, share posts, and interact with the community.';
@endphp
@include('auth.layout.header')

<style>
    /* Premium Register Style Override */
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
        margin-bottom: 28px;
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
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #334155;
        margin-bottom: 6px;
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
        padding: 11px 16px 11px 46px !important;
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

    /* Terms checkbox */
    .terms-row {
        margin: 18px 0;
    }

    .form-check {
        margin: 0;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        min-height: auto;
        padding-left: 0;
    }

    .form-check-input {
        width: 16px;
        height: 16px;
        margin: 3px 0 0 0 !important;
        border: 1.5px solid var(--border);
        border-radius: 4px;
        cursor: pointer;
        outline: none;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .form-check-label {
        font-size: 13.5px;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
        user-select: none;
        line-height: 1.4;
    }

    .form-check-label a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }

    .form-check-label a:hover {
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

    .btn-submit.disabled {
        opacity: 0.6;
        pointer-events: none;
        box-shadow: none;
        cursor: not-allowed;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #ff7c5e 0%, #e03d2f 100%);
        box-shadow: 0 8px 22px rgba(255, 73, 57, 0.3);
        transform: translateY(-1px);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* Bottom prompt */
    .auth-bottom-prompt {
        text-align: center;
        margin-top: 24px;
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
                    <span class="auth-tagline">Join Community</span>
                    <h2>Start Sharing and Exploring Your <span>City</span></h2>
                    
                    <div class="auth-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Create Custom Profile</h5>
                                <p>Create a profile, share posts, and interact with neighbors.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="feature-text">
                                <h5>List Your Business</h5>
                                <p>Promote your business or list items on the local marketplace.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Create Social Groups</h5>
                                <p>Engage in groups tailored to your local interests and activities.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="auth-hero-img-wrap">
                    <img class="img-fluid" src="{{ asset('assets/frontend/images/login_hero.webp') }}" 
                         alt="City Guide Illustration" loading="eager" width="400">
                </div>
            </div>

            <!-- Right Column: Register Form -->
            <div class="col-lg-6 auth-form-panel">
                <div class="auth-form-header">
                    <h3>{{ get_phrase('Sign Up') }}</h3>
                    <p>Create a new account to get started</p>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <!-- Full Name Input -->
                    <div class="form-group">
                        <label for="name">{{ get_phrase('Full Name') }}</label>
                        <div class="custom-input-wrapper">
                            <input type="text" id="name" name="name" class="form-control"
                                   value="{{ old('name') }}" placeholder="{{ get_phrase('Your full name') }}" required autofocus>
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        @if($errors->has('name'))
                            <p class="error-msg"><i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email">{{ get_phrase('Email') }}</label>
                        <div class="custom-input-wrapper">
                            <input type="email" id="email" name="email" class="form-control"
                                   value="{{ old('email') }}" placeholder="{{ get_phrase('Enter your email address') }}" required>
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
                            <span class="password-toggle" onclick="togglePassword('password', 'eyeIcon1')">
                                <i id="eyeIcon1" class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="form-group">
                        <label for="password_confirmation">{{ get_phrase('Confirm Password') }}</label>
                        <div class="custom-input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                                   placeholder="{{ get_phrase('Confirm password') }}" required>
                            <i class="fas fa-lock input-icon"></i>
                            <span class="password-toggle" onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                                <i id="eyeIcon2" class="fas fa-eye"></i>
                            </span>
                        </div>
                        @if($errors->has('password'))
                            <p class="error-msg"><i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <input type="hidden" name="timezone" id="timezone" value="">

                    <!-- Terms and Conditions Checkbox -->
                    <div class="terms-row">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="check1" id="exampleCheck1" required>
                            <label class="form-check-label" for="exampleCheck1">
                                {{ get_phrase('I accept the') }} 
                                <a href="{{ route('term.view') }}" target="_blank">{{ get_phrase('Terms and Conditions') }}</a>
                            </label>
                        </div>
                    </div>

                    <!-- Sign Up Button -->
                    <button type="submit" class="btn-submit disabled" id="submit">
                        {{ get_phrase('Sign Up') }}
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="auth-bottom-prompt">
                    <span>{{ get_phrase('Already have an account?') }}</span>
                    <a href="{{ route('login') }}">
                        {{ get_phrase('Login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function togglePassword(fieldId, iconId) {
        let passwordField = document.getElementById(fieldId);
        let eyeIcon = document.getElementById(iconId);

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
