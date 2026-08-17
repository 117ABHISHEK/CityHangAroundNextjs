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
              <h4>{{ get_phrase('All Enquiry') }}</h4>
              
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
                  <th scope="col">{{ get_phrase('User') }}</th>
                  <th scope="col">{{ get_phrase('City') }}</th>
                  <th scope="col">{{ get_phrase('Product') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ( $enquiries as $key => $enquiry )
                    <tr>
                        <th scope="row">
                        <p class="row-number">{{ ++$key }}</p>
                        </th>
                        <td>
                        <div class="dAdmin_info_name min-w-100px">
                            <a href="{{route('user.profile.view', $enquiry->userid)}}" class="text-dark" target="_blank">{{ $enquiry->name ?? "" }}</a>
                            <br><small>{{$enquiry->mobileno}}</small>
                        </div>
                        </td>
                        <td>
                        <div class="dAdmin_info_name min-w-100px">
                            {{ $enquiry->city_name }}
                        </div>
                        </td>
                        <td>
                        <div class="dAdmin_info_name min-w-100px">
                            {{ $enquiry->title }}
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
                                {{ $enquiries->links() }}
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



