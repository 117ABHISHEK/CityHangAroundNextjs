<script>
    document.documentElement.classList.add('shell-loading');
</script>
<style>
    html.shell-loading {
        background: #ffffff;
    }

    html.shell-loading body {
        background: #ffffff;
    }

    html.shell-loading body > :not(.page-shell-loader) {
        visibility: hidden;
    }

    .page-shell-loader {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #ffffff 0%, #fff7f5 100%);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }

    html.shell-loading .page-shell-loader {
        opacity: 1;
        visibility: visible;
    }

    .page-shell-loader__inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 18px;
        text-align: center;
        padding: 24px;
    }

    .page-shell-loader__logo {
        max-width: 180px;
        width: 48vw;
        height: auto;
    }

    .page-shell-loader__spinner {
        width: 42px;
        height: 42px;
        border: 3px solid rgba(255, 73, 57, 0.16);
        border-top-color: #ff4939;
        border-radius: 50%;
        animation: page-shell-spin 0.8s linear infinite;
    }

    .page-shell-loader__text {
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.02em;
    }

    @keyframes page-shell-spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
