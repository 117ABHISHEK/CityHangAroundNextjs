<div class="main_content">
  <!-- Header -->
  <div class="mainSection-title mb-3">
    <div class="row">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div class="d-flex flex-column">
            <h4 class="mb-0">{{ get_phrase('All Product Categories') }}</h4>
          </div>
          <div class="export-btn-area">
            <a href="{{ route('admin.create.product.category') }}" 
               class="btn btn-sm btn-primary" 
               data-bs-toggle="tooltip" 
               data-bs-placement="top" 
               title="Create Product Category">
               <i class="fas fa-plus me-1"></i> {{ get_phrase('Create') }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Admin area -->
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table eTable align-middle table-hover mb-0">
              <thead class="">
                <tr>
                  <th scope="col">{{ get_phrase('Sl No') }}</th>
                  <th scope="col">{{ get_phrase('Category Name') }}</th>
                  <th scope="col">{{ get_phrase('Parent') }}</th>
                  <th scope="col">{{ get_phrase('Product') }}</th>
                  <th scope="col">{{ get_phrase('User') }}</th>
                  <th scope="col">{{ get_phrase('No. of Views') }}</th>
                  <th scope="col">{{ get_phrase('No. of Inquiries') }}</th>
                  <th scope="col" class="text-center">{{ get_phrase('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($all_category as $key => $category)
                <tr>
                  <td>{{ ++$key }}</td>
                  <td>{{ $category->product_category_name }}</td>
                  <td>{{ $category->parent->product_category_name ?? 'Root' }}</td>
                  <td>
                    @php
                      $productCategory = $category->productCategories->last();
                      $marketplace = $productCategory?->marketplace;
                      $page = $marketplace?->page;
                      $pageCategory = $page?->categories?->last();
                    @endphp

                    @if($page && $page->city && $pageCategory && $marketplace)
                      <a href="{{ route('single.product', [
                        'city_slug' => $page->city->city_slug,
                        'area_slug' => $page->area->area_slug,
                        'category_slug' => $pageCategory->category_slug,
                        'item_slug' => $page->item_slug,
                        'product_category_slug' => $category->product_category_slug,
                        'product_slug' => $marketplace->product_slug
                      ]) }}" target="_blank">
                        {{ $marketplace->title }}
                      </a>
                    @else
                      <span class="text-muted">No Product</span>
                    @endif
                  </td>

                  <td>
                    @if($category->creator)
                      <a href="{{ route('user.profile.view', $category->creator->id) }}" class="text-decoration-none">
                        {{ $category->creator->name }}
                      </a>
                      <br>
                      <small class="text-muted">{{ $category->creator->email }}</small>
                    @else
                      <span class="text-muted">No User</span>
                    @endif
                  </td>

                  <td>{{ $category->view_count ?? 0 }}</td>
                  <td>{{ $category->inquiry_count ?? 0 }}</td>

                  <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <a href="{{ route('admin.edit.product.category', $category->id) }}" 
                         class="btn btn-sm btn-outline-primary px-3">
                        Edit
                      </a>
                      <a href="{{ route('admin.delete.product.category', $category->id) }}" 
                         class="btn btn-sm btn-outline-danger px-3"
                         onclick="return confirm('Are you sure?')">
                        Delete
                      </a>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="mt-4 d-flex justify-content-center">
            {{ $all_category->links('pagination::bootstrap-5') }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="container py-4">
    <div class="text-center">
      @include('backend.footer')
    </div>
  </div>
</div>


<style>
  .eTable th, .eTable td {
    border: none !important;
    font-size: 0.92rem;
  }

  .eTable thead tr {
    border-bottom: 2px solid black !important;
  }

  .eTable thead th {
    font-weight: 600;
    color: black;
    padding: 0.75rem;
  }

  .eTable tbody td {
    padding: 0.65rem 0.75rem;
  }

  .eTable tbody tr:hover {
    background-color: #f9fbfd;
  }

  .card {
    border-radius: 10px;
  }
  .eTable .text-center .d-flex {
    flex-wrap: nowrap !important;
  }

  .btn-outline-primary, .btn-outline-danger {
    border-radius: 6px;
    min-width: 80px;
  }

  @media (max-width: 768px) {
    .eTable thead th, .eTable tbody td {
      font-size: 0.85rem;
      white-space: nowrap;
    }
    .btn {
      font-size: 0.8rem;
      padding: 0.3rem 0.6rem;
    }
  }
</style>
