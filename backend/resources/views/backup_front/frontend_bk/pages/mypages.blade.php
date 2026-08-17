<div class="marketplace-wrap">
    <div class="d-md-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5"><span><i class="fa fa-flag"></i></span> {{ get_phrase('My Pages') }}</h3>
          <div class="pagebtnListing">
                <!-- <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product'])}}', '{{get_phrase('Create Product')}}');" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createProduct" class="btn btn-primary"> <i class="fa fa-plus-circle"></i></a> -->
                    <a href="{{ route('pages.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i> {{ get_phrase('Create Page') }}
            </a>
            <a href="{{ route('pages') }}" class="btn mx-1"> {{ get_phrase('Pages') }}</a>
            <a href="{{ route('pages.suggested') }}" class="btn mx-1">{{ get_phrase('Suggested Pages') }}</a>
            <a href="{{ route('pages.joined') }}" class="btn mx-1">{{ get_phrase('Joined Pages') }}</a>
            <a href="{{ route('pages.incomplete') }}" class="btn mx-1">{{ get_phrase('Incomplete Pages') }}</a>
            
        </div>
    </div>
   
    <!-- Product Listing Start -->
     
        <div class="row g-3" id="@if(str_contains(url()->current(), '/productdata')) single-item-countable @endif">
            @include('frontend.pages.single-page')
        </div>
    </div>
     <!-- pagination -->
     <div class="pagination-area" style="text-align:center;">
                            <div aria-label="Page navigation example">
                                <ul class="pagination">
                               {{ $mypages->links() }}
                                </ul>
                            </div>
                        </div>
                        <!-- pagination end -->
</div>