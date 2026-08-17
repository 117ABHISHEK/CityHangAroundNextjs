
    <div class="container mt-5">
        <h2 class="mb-4">Manage Attributes</h2>

        <!-- Category Selector -->
        <div class="card p-4 mb-4">
            <h4>Select Category</h4>
            <form action="{{ route('admin.view.attributes.index') }}" method="GET">
                <div class="form-group">
                    <label for="category_id">Choose Category:</label>
                    <select name="category_id" id="category_id" class="form-select eForm-control select2 @error('category_id') is-invalid @enderror" onchange="this.form.submit()">
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->product_category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Display Selected Category's Attributes -->
        @if($selectedCategory)
            <div class="card p-4">
                <h3 class="mb-4">Attributes for {{ $selectedCategory->name }}</h3>

                <!-- Table for Attributes -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attributes as $attribute)
                            <tr>
                                <td>{{ $attribute->name }}</td>
                                <td>
                                    <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.attributes.destroy', $attribute->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this attribute?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Add New Attribute Button -->
                <a href="{{ route('admin.attributes.create', ['category_id' => request('category_id')]) }}" class="btn btn-primary">Add New Attribute</a>
            </div>
        @endif
    </div>
