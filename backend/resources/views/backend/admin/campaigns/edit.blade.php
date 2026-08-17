<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h4 class="mb-0">✏️ Edit Campaign</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.campaigns.update', $campaign->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Campaign Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $campaign->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="campaign_template_id" class="form-label">Select Template</label>
                    <select name="campaign_template_id" id="campaign_template_id" class="form-select" required>
                        <option value="" disabled>-- Select a Template --</option>
                        @foreach ($templates as $template)
                            <option value="{{ $template->id }}" {{ $campaign->campaign_template_id == $template->id ? 'selected' : '' }}>
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="mailing_list_id" class="form-label">Select Mailing List</label>
                    <select name="mailing_list_id" id="mailing_list_id" class="form-select" required>
                        <option value="" disabled>-- Select a Mailing List --</option>
                        @foreach ($mailingLists as $list)
                            <option value="{{ $list->id }}" {{ $campaign->mailing_list_id == $list->id ? 'selected' : '' }}>
                                {{ $list->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Campaign Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="" disabled>-- Select Status --</option>
                        <option value="draft" {{ $campaign->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ $campaign->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="sent" {{ $campaign->status == 'sent' ? 'selected' : '' }}>Sent</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="scheduled_at" class="form-label">Scheduled At</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control" value="{{ \Carbon\Carbon::parse($campaign->scheduled_at)->format('Y-m-d\TH:i') }}">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save me-1"></i> Update Campaign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
