<script>
    (function () {
        var revealed = false;

        function revealPage() {
            if (revealed) {
                return;
            }

            revealed = true;
            requestAnimationFrame(function () {
                document.documentElement.classList.remove('shell-loading');
            });
        }

        if (document.readyState === 'complete') {
            revealPage();
        } else {
            window.addEventListener('load', revealPage, { once: true });
            setTimeout(revealPage, 4000);
        }
    })();
</script>
