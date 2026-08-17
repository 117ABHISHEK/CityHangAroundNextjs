<div class="container py-5">
    <article class="mx-auto shadow-lg rounded" style="max-width: 800px; background: #fff; padding: 40px;">
        {{-- Title --}}
        <h1 class="mb-4" style="font-weight: 700; font-size: 2.8rem; line-height: 1.2; color: #222;">
            {{ $article->title }}
        </h1>

        {{-- Back to Search Button --}}
        <a href="{{ route('user.help.search') }}" class="btn btn-outline-primary mb-4">
            ← Back to Search
        </a>

        {{-- Divider line --}}
        <hr style="border: 1px solid #eee; margin-bottom: 30px;">

        {{-- Content --}}
        <div class="article-content" style="font-size: 1.125rem; color: #444; line-height: 1.8;">
            {!! $article->content !!}
        </div>
    </article>
</div>
