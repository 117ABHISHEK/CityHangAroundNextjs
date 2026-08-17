
<div class="main_content">
    <!-- Mani section header and breadcrumb -->
    <div class="mainSection-title">
      <div class="row">
        <div class="col-12">
          <div
            class="d-flex justify-content-between align-items-center flex-wrap gr-15"
          >
            <div class="d-flex flex-column">
              <h4>{{ get_phrase('All Events') }}</h4>
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
                  <th scope="col">{{ get_phrase('Event') }}</th>
                  <th scope="col">{{ get_phrase('Owner') }}</th>
                  <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ( $events as $key => $event )
                    <tr>
                        <th scope="row">
                        <p class="row-number">{{ ++$key }}</p>
                        </th>
                        <td>
                        <div class="dAdmin_info_name min-w-100px">
                            <a href="{{route('single.event',['city_slug'=>$event->city_slug,'area_slug'=>$event->area_slug,'category_slug'=>$event->category_slug,'event_slug'=>$event->event_slug])}}" class="text-dark" target="_blank">{{ $event->title }}</a>
                        </div>
                        </td>
                        <td>
                        <div class="dAdmin_info_name min-w-100px">
                            <a href="{{route('user.profile.view', $event->user_id)}}" class="text-dark" target="_blank">{{ $event->name ?? "" }}</a>
                            <br><small>{{$event->email}}</small>
                        </div>
                        </td>
                        <td class="text-center">
                            <div class="adminTable-action d-flex justify-content-center align-items-center">
                                <div class="dropdown">
                                    <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ get_phrase('Actions') }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                                        <li>
                                            <a class="dropdown-item" href="{{route('single.event',['city_slug'=>$event->city_slug,'area_slug'=>$event->area_slug,'category_slug'=>$event->category_slug,'event_slug'=>$event->event_slug])}}" target="_blank">
                                                {{ get_phrase('View on frontend') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('events.edit', $event->id) }}">
                                                {{ get_phrase('Edit') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" 
                                               href="{{ route('event.delete', ['event_id' => $event->id]) }}">
                                                {{ get_phrase('Delete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
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
                                {{ $events->links() }}
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



