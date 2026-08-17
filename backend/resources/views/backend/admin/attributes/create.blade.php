
    <div class="container mt-5">
        <h2 class="mb-4">Create Attribute</h2>

        <!-- Success message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Create Attribute Form -->
        <div class="card p-4">
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf

                <!-- Attribute Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Attribute Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Attribute Name (e.g., Color)" required>
                </div>

                <!-- Category Selector -->
                <div class="mb-3" hidden>
                    <label for="category_id" class="form-label">Select Category</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->product_category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Attribute Values Section -->
                <div id="attribute-values" class="mb-3">
                    <label class="form-label">Attribute Values</label>
                    <div class="input-group mb-3">
                        <input type="text" name="values[]" class="form-control" placeholder="Value (e.g., Red)" required>
                        <button type="button" id="add-value" class="btn btn-outline-secondary">Add More</button>
                    </div>
                </div>

                <!-- Save Button -->
                <button type="submit" class="btn btn-primary">Save Attribute</button>
            </form>
        </div>
    </div>

    <!-- Include scripts -->
    <script>
        // Add new input field for values
        document.getElementById('add-value').addEventListener('click', function() {
            var newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group', 'mb-3');
            
            var newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = 'values[]';
            newInput.classList.add('form-control');
            newInput.placeholder = 'Value (e.g., Red)';
            
            var newButton = document.createElement('button');
            newButton.type = 'button';
            newButton.classList.add('btn', 'btn-outline-secondary', 'remove-value');
            newButton.textContent = 'Remove';
            
            newButton.addEventListener('click', function() {
                newInputGroup.remove();
            });

            newInputGroup.appendChild(newInput);
            newInputGroup.appendChild(newButton);

            document.getElementById('attribute-values').appendChild(newInputGroup);
        });
    </script>