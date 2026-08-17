
<div class="main_content">
    <!-- Mani section header and breadcrumb -->
    <div class="mainSection-title">
      <div class="row">
        <div class="col-12">
          <div
            class="d-flex justify-content-between align-items-center flex-wrap gr-15"
          >
            <div class="d-flex flex-column">
              <h4>{{ get_phrase('All Pages') }}</h4>
              
            </div>
            <div class="export-btn-area">
                        <a href="{{ route('user.page.create') }}" class="export_btn">
                            <i class="fas fa-plus me-2"></i> {{ get_phrase('Create') }}
                        </a>
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
            <table class="table eTable " id="">
              <thead>
                <tr>
                  <th scope="col">{{ get_phrase('Sl No') }}</th>
                  <th scope="col">{{ get_phrase('Page') }}</th>
                  <th scope="col">{{ get_phrase('Page owner') }}</th>
                  <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ( $pages as $key => $page )
                    <tr>
                        <th scope="row">
                        <p class="row-number">{{ ++$key }}</p>
                        </th>
                        <td>
                        <div class="dAdmin_info_name min-w-100px">
                            <a href="{{route('single.page',['city_slug'=>$page->city_slug,'area_slug'=>$page->area_slug,'category_slug'=>$page->category_slug,'item_slug'=>$page->item_slug])}}" class="text-dark" target="_blank">{{ $page->title }}</a>
                        </div>
                        </td>
                        <td>
                        <div class="dAdmin_info_name min-w-100px">
                            <a href="{{route('user.profile.view', $page->user_id)}}" class="text-dark" target="_blank">{{ $page->name ?? "" }}</a>
                            <br><small>{{$page->email}}</small>
                        </div>
                        </td>
                        <td class="text-center">
                             <div class="adminTable-action me-auto">
                                <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ get_phrase('Actions') }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                    <li>
                                        <a class="dropdown-item" href="{{route('single.page',['city_slug'=>$page->city_slug,'area_slug'=>$page->area_slug,'category_slug'=>$page->category_slug,'item_slug'=>$page->item_slug])}}" target="_blank">
                                            {{ get_phrase('View on frontend') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pages.edit', $page->id) }}">
                                            {{ get_phrase('Edit') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" 
                                           href="{{ route('pages.delete', ['id' => $page->id]) }}">
                                            {{ get_phrase('Delete') }}
                                        </a>
                                    </li>
                                </ul>
                          </div>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            <!-- pagination -->
            <div class="pagination-area">
                            <div aria-label="Page navigation example">
                                <ul class="pagination">
                                {{ $pages->links() }}
                                </ul>
                            </div>
                        </div>
                        <!-- pagination end -->
          </div>
        </div>
      </div>
    </div>
    <!-- End Admin area -->

   
    <!-- Start Footer -->
    @include('backend.footer')
    <!-- End Footer -->
  </div>



