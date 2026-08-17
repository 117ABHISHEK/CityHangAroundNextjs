
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Attribute</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.attributes.update', $attribute->id) }}" method="POST">
                @csrf
                {{-- Use POST since your route supports it --}}
                
                <!-- Attribute Name -->
                <div class="mb-3">
                    <label class="form-label">Attribute Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $attribute->name }}" required>
                </div>

                <!-- Category Selector -->
                <div class="mb-3" hidden>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $attribute->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->product_category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Attribute Values -->
                <div class="mb-3">
                    <label class="form-label">Attribute Values</label>
                    <div id="attribute-values">
                        @foreach($attribute->values as $value)
                            <div class="input-group mb-2 value-row">
                                <input type="text" name="values[{{ $value->id }}]" class="form-control" value="{{ $value->value }}" required>
                                <button type="button" class="btn btn-danger remove-value">Remove</button>
                            </div>
                        @endforeach
                        <div class="input-group mb-2 value-row">
                            <input type="text" name="values[new][]" class="form-control" placeholder="Add new value">
                            <button type="button" class="btn btn-danger remove-value">Remove</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" id="add-value">+ Add Another Value</button>
                </div>

                <!-- Form Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.view.attributes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Attribute</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    document.getElementById('add-value').addEventListener('click', function () {
        const wrapper = document.getElementById('attribute-values');
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2', 'value-row');
        div.innerHTML = `
            <input type="text" name="values[new][]" class="form-control" placeholder="New value" required>
            <button type="button" class="btn btn-danger remove-value">Remove</button>
        `;
        wrapper.appendChild(div);
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-value')) {
            e.target.closest('.value-row').remove();
        }
    });
</script>

