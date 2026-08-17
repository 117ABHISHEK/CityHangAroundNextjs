
@include('auth.layout.header')

<!-- Main Start -->
    <main class="main my-4 p-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="login-img">
                        <img class="img-fluid" src="{{ asset('assets/frontend/images/login.png') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="login-txt ms-5 text-center w-100">
                        <h3>{{ get_phrase('Congratulations')}}</h3>
                        <h4>{{ get_phrase('Your Verification is Done')}}</h4>
                        <h5>{{ get_phrase('Now Explore')}}</h5>
                        <div class="mt-4">
                            <a href="{{ route('timeline') }}" class="btn px-4 py-2" style="background-color: var(--primary); color: #fff; border-color: var(--primary); border-radius: 24px; font-weight: 500; font-size: 15px; box-shadow: 0 4px 14px var(--primary-glow); transition: all 0.25s ease; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                                {{ get_phrase('Explore Now') }}
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                        <p class="text-muted mt-3" style="font-size: 13px;">
                            {{ get_phrase('Redirecting you automatically in 3 seconds...') }}
                        </p>
                    </div>
                </div>
            </div>

        </div> <!-- container end -->
    </main>
    <!-- Main End -->

    <script>
        setTimeout(function() {
            window.location.href = "{{ route('timeline') }}";
        }, 3000);
    </script>

@include('auth.layout.footer')