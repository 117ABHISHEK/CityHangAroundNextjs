
<div class="container mt-4">
    <h1>{{ isset($helpArticle) ? 'Edit Help Article' : 'Create Help Article' }}</h1>

    <form method="POST" action="{{ isset($helpArticle) ? route('admin.help-articles.update', $helpArticle) : route('admin.help-articles.store') }}">
        @csrf
        @if(isset($helpArticle)) @method('PUT') @endif

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   class="form-control @error('title') is-invalid @enderror" 
                   value="{{ old('title', $helpArticle->title ?? '') }}" 
                   required
                   placeholder="Enter article title">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Content --}}
        <div class="mb-3">
            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
            <textarea name="content" id="content" class="form-control eForm-control content" rows="6" placeholder="Write your content here...">{{ old('content') }}</textarea>
            
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn btn-success">
            {{ isset($helpArticle) ? 'Update Article' : 'Create Article' }}
        </button>
        <a href="{{ route('admin.help-articles.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>


