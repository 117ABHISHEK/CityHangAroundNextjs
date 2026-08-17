<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<div class="row gx-3">
    <div class="col-lg-12">
        <div class="group-inner">
        
            <div class="page-suggest mt-4">
                <h1 class="h1">Top {{ $category->category_name }} Groups – Engage, Share & Grow</h1>
                <div class="ps-wrap mt-3 justify-content-between">
                @include('frontend.groups.custom_single_group')
                </div>
              
            </div>
        </div>
    </div><!--  Group Content Inner Col End -->
    
   
</div>