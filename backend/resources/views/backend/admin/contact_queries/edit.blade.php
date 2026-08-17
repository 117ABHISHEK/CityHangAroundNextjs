<div class="container my-4">
    <div class="card shadow-sm p-4" style="max-width:680px; margin:auto;">
        <div class="d-flex align-items-center mb-4 gap-2">
            <a href="{{ route('admin.contact.queries') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <h5 class="mb-0 fw-bold text-primary ms-2">Edit Contact Query</h5>
        </div>
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ route('admin.contact.queries.update', $contactQuery->id) }}"
              method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $contactQuery->name) }}" required />
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Phone</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone', $contactQuery->phone) }}" required />
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $contactQuery->email) }}" required />
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">City</label>
                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                       value="{{ old('city', $contactQuery->city) }}" required />
                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Query</label>
                <textarea name="query" rows="5"
                          class="form-control @error('query') is-invalid @enderror"
                          required>{{ old('query', $contactQuery->query) }}</textarea>
                @error('query')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.contact.queries') }}" class="btn btn-secondary px-4">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<style>
    .card { border-radius: 12px; }
</style>
