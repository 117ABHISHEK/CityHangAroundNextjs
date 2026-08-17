<style>
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.eTable {
  min-width: 760px; 
}
.row-number {
  color:black;
}
.eTable th, .eTable td {
 border: none !important;
}
.eTable thead tr {
  border-bottom: 2px solid black !important;
}
.eTable thead th {
  font-weight: 600;
  padding: 0.75rem 0.75rem;
}
</style>
<div class="main_content">
  <div class="mainSection-title">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
          <div class="d-flex flex-column">
            <h4>{{ get_phrase('All User Suggested Event Categories') }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Start Admin area -->
  <div class="row">
    <div class="col-12">
      <div class="eSection-wrap-2">
        <div class="table-responsive">
          <table class="table eTable">
            <thead>
              <tr>
                <th>{{ get_phrase('Sl No') }}</th>
                <th>{{ get_phrase('Category Name') }}</th>
                <th>{{ get_phrase('Parent') }}</th>
                <th>{{ get_phrase('User') }}</th>
                <th>{{ get_phrase('Event') }}</th>
                <th class="text-center">{{ get_phrase('Action') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($all_category as $key => $category)
                <tr>
                  <td><p class="row-number">{{ $loop->iteration }}</p></td>

                  <td>
                    <div class="dAdmin_info_name min-w-100px">
                      <p><span>{{ $category->category_name }}</span></p>
                    </div>
                  </td>

                  <td>
                    <div class="dAdmin_info_name min-w-100px">
                      <p>{{ $category->parent->category_name ?? '-' }}</p>
                    </div>
                  </td>

                  <td>
                    <div class="dAdmin_info_name min-w-100px">
                      <a href="{{ route('user.profile.view', $category->creator->id) }}" target="_blank">
                        {{ $category->creator->name }}
                      </a>
                      <br>
                      <small>{{ $category->creator->email }}</small>
                    </div>
                  </td>

                  <td>
                    @php
                      $event = $category->events->first();
                    @endphp

                    @if($event)
                      <a href="{{ route('single.event', [
                          'id' => $event->id,
                        'city_slug' => $event->city->city_slug ?? 'city',
                        'area_slug' => $event->area->area_slug ?? 'area',
                        'category_slug' => $category->category_slug,
                        'event_slug' => $event->event_slug
                      ]) }}" target="_blank" >
                        {{ $event->title }}
                      </a>
                    @else
                      <span class="text-muted">{{ get_phrase('No Event') }}</span>
                    @endif
                  </td>

                  <td class="text-center">
                    <div class="adminTable-action me-auto">
                      <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ get_phrase('Actions') }}
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                        <li><a class="dropdown-item" href="{{ route('admin.edit.event.category', $category->id) }}">{{ get_phrase('Edit') }}</a></li>
                        <li><a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" href="{{ route('admin.delete.event.category', $category->id) }}">{{ get_phrase('Delete') }}</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <!-- Pagination -->
          <div class="pagination-area">
            <div aria-label="Page navigation example">
              <ul class="pagination">
                {{ $all_category->links() }}
              </ul>
            </div>
          </div>
          <!-- Pagination end -->

        </div>
      </div>
    </div>
  </div>
  <!-- End Admin area -->

  @include('backend.footer')
</div>
