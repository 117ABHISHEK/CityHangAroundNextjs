
<div class="container py-5">
    <h2 class="mb-4">How can we help you?</h2>

    {{-- Search Form --}}
    <form action="{{ route('user.help.search') }}" method="GET" class="input-group mb-4">
        <input type="text" name="query" id="search-input" class="form-control" value="{{ request('query') }}"
               placeholder="Search help articles..." autocomplete="off">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>

    {{-- Search Results --}}
    <div id="search-results">
        @if($articles->count())
            <ul class="list-group">
                @foreach($articles as $article)
                    <li class="list-group-item">
                        <a href="{{ route('user.help.show', $article->id) }}" class="fw-bold text-decoration-none">
                            {{ $article->title }}
                        </a>
                        <p class="mb-0 text-muted">{{ Str::limit(strip_tags($article->content), 100) }}</p>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No results found.</p>
        @endif
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("search-input");
    const resultBox = document.getElementById("search-results");
    let timeout = null;

    input.addEventListener("input", function () {
        const query = this.value.trim();

        if (query.length < 2) {
            resultBox.innerHTML = ''; // Clear results if less than 2 chars
            return;
        }

        // Debounce to reduce request frequency
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            fetch(`/user/search/live?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';

                    if (data.length > 0) {
                        html += '<ul class="list-group">';
                        data.forEach(article => {
                            html += `
                                <li class="list-group-item">
                                    <a href="/user/help/${article.id}" class="fw-bold text-decoration-none">${article.title}</a>
                                    <p class="mb-0 text-muted">${article.content.substring(0, 100)}...</p>
                                </li>`;
                        });
                        html += '</ul>';
                    } else {
                        html = '<p class="text-muted">No results found.</p>';
                    }

                    resultBox.innerHTML = html;
                })
                .catch(() => {
                    resultBox.innerHTML = '<p class="text-danger">Error fetching results.</p>';
                });
        }, 300); // wait 300ms after user stops typing
    });
});

</script>
