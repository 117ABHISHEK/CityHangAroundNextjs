@php 
    $auth_title = get_phrase('Verify Email');
    $meta_description = 'Verify your email address to complete your registration and secure your City Hang Around account.';
@endphp
@include('auth.layout.header')

<style>
    /* Premium Verify Email Style Override */
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

    /* Right panel: Focused Content & Forms */
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
        margin-bottom: 12px;
    }

    .auth-form-header p {
        font-size: 14.5px;
        color: #475569;
        margin: 0;
        line-height: 1.6;
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
        text-align: center;
        display: block;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #ff7c5e 0%, #e03d2f 100%);
        box-shadow: 0 8px 22px rgba(255, 73, 57, 0.3);
        transform: translateY(-1px);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .btn-logout {
        display: block;
        text-align: center;
        background: transparent;
        border: 1.5px solid var(--border);
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15.5px;
        color: #475569;
        text-decoration: none;
        width: 100%;
        margin-top: 12px;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .btn-logout:hover {
        background: #f8fafc;
        border-color: var(--border-hover);
        color: #1e293b;
        transform: translateY(-1px);
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
                    <span class="auth-tagline">Verification</span>
                    <h2>Check Your <span>Inbox</span></h2>
                    
                    <div class="auth-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Confirmation Link Sent</h5>
                                <p>We've sent a secure confirmation link to verify your ownership of the account.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Protect Your Account</h5>
                                <p>Email verification adds an essential layer of security to block unauthorized registrations.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="auth-hero-img-wrap">
                    <img class="img-fluid" src="{{ asset('assets/frontend/images/login_hero.webp') }}" 
                         alt="City Guide Illustration" loading="eager" width="400">
                </div>
            </div>

            <!-- Right Column: Verification Status & Forms -->
            <div class="col-lg-6 auth-form-panel">
                <div class="auth-form-header">
                    <h3>{{ get_phrase('Verify Your Email') }}</h3>
                    <p>{{ get_phrase('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>
                </div>

                <!-- Session Status Alert -->
                @if(session('status') == 'verification-link-sent')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ get_phrase('A new verification link has been sent to the email address you provided during registration.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Resend Verification Action -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-submit">
                        {{ get_phrase('Resend Verification Email') }}
                    </button>
                </form>

                <!-- Logout Action -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        {{ get_phrase('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

@include('auth.layout.footer')
