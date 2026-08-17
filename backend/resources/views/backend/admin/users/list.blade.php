<div class="main_content">
  <!-- Main section header and breadcrumb -->
  <div class="mainSection-title">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
          <div class="d-flex flex-column">
            <h4>{{ get_phrase('All Users') }}</h4>
          </div>
          <div class="export-btn-area">
            <a href="{{ route('admin.user.add') }}" class="export_btn" data-bs-toggle="tooltip"
              data-bs-placement="top" data-bs-custom-class="custom-tooltip"
              data-bs-title="{{ get_phrase('Create user') }}">{{ get_phrase('Create a new user') }}</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter -->
  <form id="userFilterForm" class="row g-2 g-md-3 mb-4">
  <div class="col-12 col-md-2">
    <input type="date" name="start_date" class="form-control" placeholder="Start Date">
  </div>
  <div class="col-12 col-md-2">
    <input type="date" name="end_date" class="form-control" placeholder="End Date">
  </div>
  <div class="col-12 col-md-2">
    <select name="state_id" id="filter_state" class="form-control select2">
      <option value="">Select State</option>
      @foreach(App\Models\State::all() as $state)
        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-12 col-md-2">
    <select name="city_id" id="filter_city" class="form-control select2">
      <option value="">Select City</option>
    </select>
  </div>
  <div class="col-12 col-md-2">
    <select name="area_id" id="filter_area" class="form-control select2">
      <option value="">Select Area</option>
    </select>
  </div>
  <div class="col-12 col-md-2">
    <button type="submit" class="btn btn-primary w-100">
      <i class="fas fa-filter me-1 d-none d-sm-inline"></i> Filter
    </button>
  </div>
</form>


      <!-- Table -->
      <div class="row">
        <div class="col-12">
          <div class="eSection-wrap-2">
            <div class="table-responsive">
              <table class="table table-sm table-hover align-middle eTable w-100" id="server_side_users_data">
                <thead class="bg-white">
                  <tr>
                    <th class="ps-3">#</th>
                    <th>{{ get_phrase('Photo') }}</th>
                    <th>{{ get_phrase('Name') }}</th>
                    <th>{{ get_phrase('Email') }}</th>
                    <th>{{ get_phrase('City') }}</th>
                    <th>{{ get_phrase('Date') }}</th>
                    <th>{{ get_phrase('Status') }}</th>
                    <th class="text-center pe-3">{{ get_phrase('Actions') }}</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>

  .eTable th,
  .eTable td {
    border: none !important;
    vertical-align: middle;
    font-size: 0.92rem;
  }
  .eTable thead tr th {
    border-bottom: 2px solid #e9ecef;
  }
  .eTable thead th {
    padding: 0.75rem 0.75rem;
    font-weight: 600;
    color: #495057;
  }
  .eTable tbody td {
    padding: 0.65rem 0.75rem;
    color: #343a40;
  }
  .eTable td img.user-avatar {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    border: 1px solid #eef0f2;
  }
  .eTable tbody tr:hover {
    background-color: #fbfdff;
  }
  .eTable .action-btn {
    min-width: 36px;
  }
  .user-badge {
    font-size: 0.78rem;
    padding: 0.28rem 0.5rem;
    border-radius: 12px;
  }
  @media (max-width: 575.98px) {
    .eTable thead th { font-size: 0.78rem; }
    .eTable tbody td { font-size: 0.82rem; padding: 0.5rem 0.5rem; }
    .export-btn-area .btn { font-size: 0.82rem; padding: 0.38rem 0.6rem; }
  }
</style>

<!-- Scripts -->
<script>
  $(document).ready(function () {
    $('.select2').select2();

    let table = $('#server_side_users_data').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route("admin.server_side_users_data") }}',
        type: 'POST',
        data: function (d) {
          d._token = '{{ csrf_token() }}';
          d.start_date = $('input[name=start_date]').val();
          d.end_date = $('input[name=end_date]').val();
          d.state_id = $('#filter_state').val();
          d.city_id = $('#filter_city').val();
          d.area_id = $('#filter_area').val();
        }
      },
      columns: [
        { data: 'key' },
        { data: 'photo' },
        { data: 'name' },
        { data: 'email' },
        { data: 'city' },
        { data: 'date' },
        { data: 'status' },
        { data: 'action', orderable: false, searchable: false }
      ]
    });

    $('#userFilterForm').on('submit', function (e) {
      e.preventDefault();
      table.ajax.reload();
    });

    $('#filter_state').on('change', function () {
      $('#filter_city').html('<option value="">Loading...</option>');
      $.get('/ajax/cities/' + $(this).val(), function (data) {
        let options = '<option value="">Select City</option>';
        let cities = typeof data === 'string' ? JSON.parse(data) : data;
        cities.forEach(c => {
          options += `<option value="${c.id}">${c.city_name}</option>`;
        });
        $('#filter_city').html(options);
      });
    });

    $('#filter_city').on('change', function () {
      $('#filter_area').html('<option value="">Loading...</option>');
      $.get('/ajax/areas/' + $(this).val(), function (data) {
        let options = '<option value="">Select Area</option>';
        let areas = typeof data === 'string' ? JSON.parse(data) : data;
        areas.forEach(a => {
          options += `<option value="${a.id}">${a.area_name}</option>`;
        });
        $('#filter_area').html(options);
      });
    });
  });
</script>
