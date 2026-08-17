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
              <h4>{{ get_phrase('All Users Area') }}</h4>
              
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
            <table class="table eTable" id="">
  <thead>
    <tr>
      <th scope="col">{{ get_phrase('Sl No') }}</th>
      <th scope="col">{{ get_phrase('City') }}</th>
      <th scope="col">{{ get_phrase('Area') }}</th>
      <th scope="col">{{ get_phrase('User') }}</th>
      <th scope="col">{{ get_phrase('Details') }}</th>
      <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ( $all_category as $key => $category )
     @if ($category->creator)
      <tr>
        <th scope="row"><p class="row-number">{{ ++$key }}</p></th>

        <td><p>{{ $category->city->city_name }}</p></td>

        <td><p>{{ $category->area_name }}</p></td>

        <td>
          <a href="{{ route('user.profile.view', $category->creator->id ?? 0) }}" class="text-dark" target="_blank">
           {{ $category->creator->name ?? 'N/A' }}
          </a><br>
          <small>{{ $category->creator->email }}</small>
        </td>

        <!-- Details column -->
        <td>
          <ul class="nav nav-tabs" id="tab-{{ $category->id }}" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="tab" href="#pages-{{ $category->id }}" role="tab">Page</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#events-{{ $category->id }}" role="tab">Event</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#market-{{ $category->id }}" role="tab">Marketplace</a>
            </li>
          </ul>

          <div class="tab-content pt-2">
            <!-- Page -->
            <div class="tab-pane fade show active" id="pages-{{ $category->id }}" role="tabpanel">
              @php $latestPage = $category->pages->last(); @endphp
              @if ($latestPage)
                <a href="{{ route('single.page', [
                  $latestPage->city->city_slug ?? '-',
                  $latestPage->area->area_slug ?? '-',
                  $latestPage->getCategory->category_slug ?? '-',
                  $latestPage->item_slug
                ]) }}" target="_blank">
                  {{ $latestPage->title }}
                </a>
              @else
                <em>No page</em>
              @endif
            </div>

            <!-- Event -->
            <div class="tab-pane fade" id="events-{{ $category->id }}" role="tabpanel">
              @php $latestEvent = $category->events->last(); @endphp
              @if ($latestEvent)
                <a href="{{ route('single.event', [
                  $latestEvent->city->city_slug ?? '-',
                  $latestEvent->area->area_slug ?? '-',
                  $latestEvent->category->category_slug ?? '-',
                  $latestEvent->event_slug
                ]) }}" target="_blank">
                  {{ $latestEvent->title }}
                </a>
              @else
                <em>No event</em>
              @endif
            </div>

            <!-- Marketplace -->
            <div class="tab-pane fade" id="market-{{ $category->id }}" role="tabpanel">
              @php $latestMarket = $category->marketplaces->last(); @endphp
              @if ($latestMarket && $latestMarket->page && $latestMarket->page->category && $latestMarket->categories)
                <a href="{{ route('single.product', [
                  $latestMarket->city->city_slug ?? '-',
                   $latestMarket->area->area_slug ?? '-',
                  $latestMarket->page->category->category_slug ?? '-',
                  $latestMarket->page->item_slug ?? '-',
                  $latestMarket->categories->category_slug ?? '-',
                  $latestMarket->product_slug
                ]) }}" target="_blank">
                  {{ $latestMarket->product_slug }}
                </a>
              @else
                <em>No product</em>
              @endif
            </div>
          </div>
        </td>

        <!-- Action Column (No change) -->
        <td class="text-center">
          <div class="adminTable-action me-auto">
            <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" data-bs-toggle="dropdown" aria-expanded="false">
              {{ get_phrase('Actions') }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
              <li>
                <a class="dropdown-item" href="{{ route('admin.user.area.edit', $category->id) }}">
                  {{ get_phrase('Edit') }}
                </a>
              </li>
              <li>
                <a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" href="{{ route('admin.delete.user.area', $category->id) }}">
                  {{ get_phrase('Delete') }}
                </a>
              </li>
            </ul>
          </div>
        </td>
      </tr>
      @endif
    @endforeach
  </tbody>
</table>

             <!-- pagination -->
             <div class="pagination-area d-flex justify-content-center">
                            <div aria-label="Page navigation example">
                                <ul class="pagination">
                                {{ $all_category->links() }}
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



