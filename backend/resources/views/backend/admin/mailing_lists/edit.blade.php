
    <div class="container">
        <h1>Edit Mailing List</h1>
        <form action="{{ route('mailing_lists.update', $list->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Mailing List Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $list->name }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Mailing List</button>
        </form>
    </div>
