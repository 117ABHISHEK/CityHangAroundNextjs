
    <div class="container">
        <h1>Create Mailing List</h1>
        <form action="{{ route('mailing_lists.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Mailing List Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Mailing List</button>
        </form>
    </div>
