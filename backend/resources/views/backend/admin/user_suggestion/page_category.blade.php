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
    <!-- Mani section header and breadcrumb -->
    <div class="mainSection-title">
      <div class="row">
        <div class="col-12">
          <div
            class="d-flex justify-content-between align-items-center flex-wrap gr-15"
          >
            <div class="d-flex flex-column">
              <h4>{{ get_phrase('All Page Categories') }}</h4>
              
            </div>
            <div class="export-btn-area">
              <a href="{{ route('admin.create.category') }}" class="export_btn" data-bs-toggle="tooltip" data-bs-placement="top"
              data-bs-custom-class="custom-tooltip"
              data-bs-title="Create Page Category">{{ get_phrase('Create') }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Start Admin area -->
    <div class="row">
      <div class="col-12">
        <div class="eSection-wrap-2">
          <!-- Filter area -->
            <div class="table-responsive">
              <table class="table eTable table-hover align-middle">
                <thead>
                  <tr>
                    <th scope="col">{{ get_phrase('Sl No') }}</th>
                    <th scope="col">{{ get_phrase('Category Name') }}</th>
                    <th scope="col">{{ get_phrase('Parent') }}</th>
                    <th scope="col">{{ get_phrase('Page') }}</th>
                    <th scope="col">{{ get_phrase('User') }}</th>
                    <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ( $all_category as $key => $category )
                    <tr>
                      <th scope="row">
                        <p class="row-number">{{ ++$key }}</p>
                      </th>
            
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="me-2">
                            <!-- optional: add avatar here -->
                          </div>
                          <div class="w-100" style="max-width:220px;">
                            <div class="text-truncate" title="{{ $category->category_name }}">
                              {{ $category->category_name }}
                            </div>
                          </div>
                        </div>
                      </td>
            
                      <td>
                        <div class="w-100" style="max-width:160px;">
                          <div class="text-truncate" title="{{ $category->parent }}">{{ $category->parent ?? '—' }}</div>
                        </div>
                      </td>
            
                      <td>
                        <div class="w-100" style="max-width:220px;">
                          @if($category->city_slug && $category->area_slug && $category->category_slug && $category->item_slug)
                            <a href="{{ route('single.page', [
                                'city_slug' => $category->city_slug,
                                'area_slug' => $category->area_slug,
                                'category_slug' => $category->category_slug,
                                'item_slug' => $category->item_slug,
                            ]) }}" target="_blank" class="text-truncate d-inline-block" style="max-width:220px;">
                              {{ $category->page_name }}
                            </a>
                          @else
                            <span class="text-muted">No Page</span>
                          @endif
                        </div>
                      </td>
            
                      <td>
                        <div class="w-100" style="max-width:220px;">
                          <a href="{{route('user.profile.view', $category->user_id)}}" class="text-dark d-block text-truncate" target="_blank" title="{{ $category->name ?? '' }}">{{ $category->name ?? "" }}</a>
                          <small class="text-truncate d-block" style="max-width:220px;">{{ $category->email }}</small>
                        </div>
                      </td>
            
                      <td class="text-center">
                        <div class="dropdown">
                          <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="actionDropdown{{ $category->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ get_phrase('Actions') }}
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $category->id }}">
                            <li><a class="dropdown-item" href="{{ route('admin.edit.category',$category->id) }}">{{ get_phrase('Edit') }}</a></li>
                            <li><a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" href="{{ route('admin.delete.category',$category->id) }}">{{ get_phrase('Delete') }}</a></li>
                          </ul>
                        </div>
                      </td>
            
                    </tr>
                  @endforeach
                </tbody>
              </table>
            
              <!-- pagination: centered using bootstrap utilities -->
              <div class="mt-3 d-flex justify-content-center">
                {{ $all_category->links() }}
              </div>
            </div>
        </div>
      </div>
    </div>
    <!-- End Admin area -->

   
    <!-- Start Footer -->
    @include('backend.footer')
    <!-- End Footer -->
  </div>



