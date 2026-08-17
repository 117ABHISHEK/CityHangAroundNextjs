
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"> <i class="fas fa-map-marked-alt"></i> Add State</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.states.store') }}" method="POST">
                @csrf

                {{-- State Name --}}
                <div class="mb-3">
                    <label class="form-label">State Name:</label>
                    <input type="text" name="state_name" class="form-control @error('state_name') is-invalid @enderror" 
                           placeholder="Enter State Name" required value="{{ old('state_name') }}">
                    @error('state_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- State Abbreviation --}}
                <div class="mb-3">
                    <label class="form-label">State Abbreviation:</label>
                    <input type="text" name="state_abbr" class="form-control @error('state_abbr') is-invalid @enderror" 
                           placeholder="Enter Abbreviation (e.g., AP)" required value="{{ old('state_abbr') }}">
                    @error('state_abbr')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

               

                {{-- Submit Button --}}
                <div class="text-end">
                    <a href="{{ route('admin.state') }}" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Back</a>
                    <button type="submit" class="btn btn-success"> <i class="fas fa-save"></i> Save State</button>
                </div>
            </form>
        </div>
    </div>
</div>
