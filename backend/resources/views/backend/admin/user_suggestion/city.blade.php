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
    <!-- Main section header -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('All Users City') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin area -->
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap-2">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table eTable">
                        <thead>
                            <tr>
                                <th scope="col">{{ get_phrase('Sl No') }}</th>
                                <th scope="col">{{ get_phrase('State') }}</th>
                                <th scope="col">{{ get_phrase('City') }}</th>
                                <th scope="col">{{ get_phrase('User') }}</th>
                                <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                            </tr>
                        </thead>
                       <tbody>
@foreach ($all_category as $key => $category)
    <tr>
        <th scope="row">{{ ++$key }}</th>

        <!-- State -->
        <td>
            <div class="dAdmin_info_name min-w-100px">
                <p>{{ $category->state->state_name ?? 'N/A' }}</p>
            </div>
        </td>

        <!-- City -->
        <td>
            <div class="dAdmin_info_name min-w-100px">
                <p>{{ $category->city_name ?? 'N/A' }}</p>
            </div>
        </td>

        <!-- User -->
        <td>
            <div class="dAdmin_info_name min-w-100px">
                <a href="{{ route('user.profile.view', $category->creator->id ?? 0) }}" class="text-dark" target="_blank">
                    {{ $category->creator->name ?? 'N/A' }}
                </a>
                <br><small>{{ $category->creator->email ?? '' }}</small>
            </div>
        </td>

        <!-- Actions -->
        <td class="text-center">
            <div class="adminTable-action me-auto">
                <button type="button" class="eBtn eBtn-black dropdown-toggle table-action-btn-2" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ get_phrase('Actions') }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action">
                    <li><a class="dropdown-item" href="{{ route('admin.user.city.edit', $category->id) }}">{{ get_phrase('Edit') }}</a></li>
                    <li>
                        <a class="dropdown-item" onclick="return confirm('{{ get_phrase('Are You Sure Want To Delete?') }}')" href="{{ route('admin.delete.user.city', $category->id) }}">
                            {{ get_phrase('Delete') }}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>

    <!-- Tab Section Per City -->
    <tr>
        <td colspan="5">
            <ul class="nav nav-tabs mb-2" id="tabs-{{ $category->id }}" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pages-{{ $category->id }}">Pages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#events-{{ $category->id }}">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#products-{{ $category->id }}">Products</a>
                </li>
            </ul>

            <div class="tab-content" id="tabContent-{{ $category->id }}">
                <!-- Pages Tab -->
                <div class="tab-pane fade show active" id="pages-{{ $category->id }}">
                   @php
                        $lastPage = $category->pages && $category->pages->isNotEmpty() ? $category->pages->last() : null;
                    @endphp

                    @if ($lastPage)
                        <p>
                            📄 Last Page:
                            <a href="{{ route('single.page', [
                                'city_slug'     => $category->city->city_slug ?? 'city',
                                'area_slug'     => $lastPage->area->area_slug ?? 'area',
                                'category_slug' => $lastPage->category->category_slug ?? 'cat',
                                'item_slug'     => $lastPage->item_slug
                            ]) }}" target="_blank">{{ $lastPage->title }}</a>
                        </p>
                    @else
                        <p>No pages available.</p>
                    @endif
                </div>

                <!-- Events Tab -->
                <div class="tab-pane fade" id="events-{{ $category->id }}">
                    @php
                        $lastEvent = $category->events && $category->events->isNotEmpty() ? $category->events->last() : null;
                    @endphp
                    @if ($lastEvent)
                        <p>
                            🎉 Last Event:
                            <a href="{{ route('single.event', [
                                'id' =>  $lastEvent->id,
                                'city_slug'     => $category->city->city_slug ?? 'city',
                                'area_slug'     => $lastEvent->area->area_slug ?? 'area',
                                'category_slug' => optional($lastEvent->categories->last())->category_slug ?? 'cat',
                                'event_slug'    => $lastEvent->event_slug
                            ]) }}" target="_blank">{{ $lastEvent->title }}</a>
                        </p>
                    @else
                        <p>No events available.</p>
                    @endif
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade" id="products-{{ $category->id }}">
                    @php
                      $lastProduct = $category->marketplaces && $category->marketplaces->isNotEmpty() ? $category->marketplaces->last() : null;
                      $lastPage    = optional($lastProduct)->page;
                      $prodCat     = optional(optional($lastProduct)->productCategories)->last()->category_slug ?? 'prodcat';
                  @endphp

                    @if ($lastProduct)
                        <p>
                            🛒 Last Product:
                            <a href="{{ route('single.product', [
                                'city_slug'             => $category->city->city_slug ?? 'city',
                                'area_slug'             => $category->area->area_slug ?? 'area',
                                'category_slug'         => optional($lastPage->category)->category_slug ?? 'cat',
                                'item_slug'             => $lastPage->item_slug ?? 'item',
                                'product_category_slug' => $prodCat,
                                'product_slug'          => $lastProduct->product_slug
                            ]) }}" target="_blank">{{ Str::limit($lastProduct->product_slug, 20) }}</a>
                        </p>
                    @else
                        <p>No products available.</p>
                    @endif
                </div>
            </div>
        </td>
    </tr>
@endforeach
</tbody>

                    </table>

                    <!-- Pagination -->
                    <div class="pagination-area mt-3">
                        <div aria-label="Page navigation example">
                            <ul class="pagination">
                                {{ $all_category->links() }}
                            </ul>
                        </div>
                    </div>
                    <!-- Pagination End -->
                </div>

              
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('backend.footer')
</div>
