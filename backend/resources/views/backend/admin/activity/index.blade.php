  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
  <style>
      body {
          background-color: #f8f9fa;
      }

      .card {
          border-radius: 12px;
          box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
      }

      .card-header {
          background: #007bff;
          color: white;
          font-size: 20px;
          font-weight: bold;
          text-align: center;
          padding: 15px;
          border-top-left-radius: 12px;
          border-top-right-radius: 12px;
      }

      .form-label {
          font-weight: bold;
      }

      .btn-custom {
          font-size: 16px;
          font-weight: bold;
      }

      /* Fix Select2 dropdown issues */
      .select2-container--default .select2-selection--single {
          height: 38px;
          /* Match Bootstrap input height */
          padding: 6px;
          border-radius: 5px;
      }

      .select2-container {
          width: 100% !important;
          /* Ensure full width */
      }
  </style>
  <div class="container">
      <h2>User Activity Logs</h2>

      <!-- Filter Form -->
      <form method="GET" action="{{ url()->current() }}" class="row g-3 mb-4">
          <div class="col-md-3">
              <label>Event Name</label>
              <select name="event_name" class="form-select select2">
                  <option value="">-- All --</option>
                  @foreach ($eventNames as $event)
                      <option value="{{ $event }}" {{ request('event_name') == $event ? 'selected' : '' }}>
                          {{ $event }}</option>
                  @endforeach
              </select>
          </div>

          <div class="col-md-3">
              <label>User</label>
              <select name="user_id" class="form-select select2">
                  <option value="">-- All --</option>
                  @foreach ($usersList as $id => $name)
                      <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>
                          {{ $name }}</option>
                  @endforeach
              </select>
          </div>

          <div class="col-md-3">
              <label>Start Date</label>
              <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
          </div>

          <div class="col-md-3">
              <label>End Date</label>
              <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
          </div>

          <div class="col-md-3">
              <label>Page Category</label>
              <select name="page_category" class="form-select select2">
                  <option value="">-- All --</option>
                  @foreach ($pageCategories as $category)
                      <option value="{{ $category->id }}" {{ request('page_category') == $category->id ? 'selected' : '' }}>
                          {{ $category->name }}</option>
                  @endforeach
              </select>
          </div>

          <div class="col-md-3">
              <label>Product Category</label>
              <select name="product_category" class="form-select select2">
                  <option value="">-- All --</option>
                  @foreach ($productCategories as $category)
                      <option value="{{ $category->id }}" {{ request('product_category') == $category->id ? 'selected' : '' }}>
                          {{ $category->name }}</option>
                  @endforeach
              </select>
          </div>

          <div class="col-md-3">
              <label>Blog Category</label>
              <select name="blog_category" class="form-select select2">
                  <option value="">-- All --</option>

                  @foreach ($blogCategories as $category)
                      <option value="{{ $category->id }}"
                          {{ request('blog_category') == $category->id ? 'selected' : '' }}>
                          {{ $category->name }}
                      </option>
                  @endforeach
              </select>
          </div>


          <div class="col-md-3">
              <label>Group Category</label>
              <select name="group_category" class="form-select select2">
                  <option value="">-- All --</option>

                  @foreach ($groupCategories as $category)
                      <option value="{{ $category->id }}"
                          {{ request('group_category') == $category->id ? 'selected' : '' }}>
                          {{ $category->name }}
                      </option>
                  @endforeach
              </select>
          </div>

          <div class="col-md-3">
              <label>Event Category</label>
              <select name="event_category" class="form-select select2">
                  <option value="">-- All --</option>

                  @foreach ($eventCategories as $category)
                      <option value="{{ $category->id }}"
                          {{ request('event_category') == $category->id ? 'selected' : '' }}>
                          {{ $category->name }}
                      </option>
                  @endforeach
              </select>
          </div>

          <div class="col-md-3">
              <label>City</label>
              <select name="city_id" class="form-select select2">
                  <option value="">-- All --</option>
                  @foreach ($cities as $id => $cityName)
                      <option value="{{ $id }}" {{ request('city_id') == $id ? 'selected' : '' }}>
                          {{ $cityName }}</option>
                  @endforeach

              </select>
          </div>
          <div class="col-12 text-end">
              <button type="submit" class="btn btn-primary btn-custom">Filter</button>
              <a href="{{ url()->current() }}" class="btn btn-secondary btn-custom">Reset</a>
          </div>


      </form>

      <!-- Data Table -->
      <table class="table table-bordered">
          <thead>
              <tr>
                  <th>User</th>
                  <th>City</th>
                  <th>Total Score</th>
                  <th>Details</th>
              </tr>
          </thead>
          <tbody>
              @php $grandTotal = 0; @endphp

              @forelse($users as $user)
                  @php $userScore = $userScores[$user->id] ?? 0; @endphp
                  @php $grandTotal += $userScore; @endphp
                  <tr>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->city->city_name ?? 'N/A' }}</td>
                      <td>{{ $userScore }}</td>
                      <td>
                          <a href="{{ route('admin.user.activity.cities', ['user_id' => $user->id] + request()->query()) }}"
                              class="btn btn-sm btn-primary">Detail</a>

                      </td>
                  </tr>
              @empty
                  <tr>
                      <td colspan="4" class="text-center">No records found.</td>
                  </tr>
              @endforelse
          </tbody>
          <tfoot>
              <tr>
                  <th colspan="2" class="text-end">Total Score:</th>
                  <th colspan="2">{{ $grandTotal }}</th>
              </tr>
          </tfoot>
      </table>

      <div class="d-flex justify-content-center">
          {{ $users->appends(request()->query())->links() }}
      </div>
  </div>

  <!-- jQuery & Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

  <script>
      $(document).ready(function() {
          $('.select2').select2({
              placeholder: "Select a City",
              allowClear: true,
              width: 'resolve',
              containerCssClass: 'form-select' // Makes it match Bootstrap styling
          });
      });
  </script>
