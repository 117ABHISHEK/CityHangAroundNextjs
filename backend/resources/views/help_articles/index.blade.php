
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Help Articles</h1>
        <a href="{{ route('admin.help-articles.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add New Article
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Title</th>
                <th>Created At</th>
                <th style="width:150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($articles as $article)
            <tr>
                <td>{{ $article->title }}</td>
                <td>{{ $article->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('admin.help-articles.edit', $article) }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i> Edit
                    </a>

                    <form action="{{ route('admin.help-articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">No help articles found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div>
        {{ $articles->links() }}
    </div>
</div>
