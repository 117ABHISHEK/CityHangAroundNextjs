<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📢 Create Campaign</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.campaigns.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Campaign Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter campaign name" required>
                </div>

                <div class="mb-3">
                    <label for="campaign_template_id" class="form-label">Select Template</label>
                    <select name="campaign_template_id" id="campaign_template_id" class="form-select" required>
                        <option value="" disabled selected>-- Select a Template --</option>
                        @foreach ($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="mailing_list_id" class="form-label">Select Mailing List</label>
                    <select name="mailing_list_id" id="mailing_list_id" class="form-select" required>
                        <option value="" disabled selected>-- Select a Mailing List --</option>
                        @foreach ($mailingLists as $list)
                            <option value="{{ $list->id }}">{{ $list->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Campaign Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="" disabled selected>-- Select Status --</option>
                        <option value="draft">Draft</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="sent">Sent</option> 
                    </select>
                </div>

                <div class="mb-4">
                    <label for="scheduled_at" class="form-label">Scheduled At</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">

                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle me-1"></i> Create Campaign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
