<!-- Select2 CDN for Autosuggestion -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js"></script>

<div class="custom-modal-body">
     @php 
        $actionRoute = isset($category) ? route('category.group', ['category_slug' => $category->category_slug ?? '']) : route('groups');
    @endphp
<form class="ajaxForm form_sel " action=" {{ $actionRoute }} " method="POST" enctype="multipart/form-data">
    @csrf

    <div class="entry-header d-flex justify-content-between pop-body">
        <div class="ava-info d-flex align-items-center">
        @if(auth()->check())
            <div class="flex-shrink-0">
                <img src="{{ get_user_image(auth()->user()->photo,'optimized') }}" class="rounded-circle user_image_show_on_modal" alt="..." style="height: 40px; width: 40px;">
            </div>
            <div class="ava-desc ms-2">
                <h3 class="mb-0 h6">{{ auth()->user()->name }}</h3>
                <span class="meta-time text-muted">{{ auth()->user()->profession ?? 'Member' }}</span>
            </div>
        @else
            <div class="ava-desc ms-2">
                <h3 class="mb-0 h6">Guest</h3>
                <span class="meta-time text-muted"><a href="{{ route('login') }}">Please log in to post</a></span>
            </div>
        @endif
        </div>
    </div>

    <div class="form-group pt-2">
        <label for="modal_category">{{ get_phrase('Topic') }}</label>
         <select id="modal_category" name="category" class="select2 form-control">
              <option value="">Select a Category</option>
              @if(isset($all_categories))
                        @foreach ($all_categories as $category_item)
                            <option value="{{ $category_item->id }}" {{ request('category_filter') == $category_item->id ? 'selected' : '' }}>
                                {{ $category_item->category_name }}
                            </option>
                        @endforeach
                    @endif
            </select>
    </div>
    <div class="form-group">
        <label for="modal_city">{{ get_phrase('City') }}</label>
        <select id="modal_city" name="city" class="select2 form-control">
                    <option value="">Select a city</option>
                      @if(isset($all_group_cities))
                    @foreach ($all_group_cities as $city)
                    <option value="{{ $city->id }}" {{ ($filter_city ?? '') == $city->id ? 'selected' : '' }}>
                        {{ $city->city_name }}
                    </option>
                @endforeach
                @endif
                </select>
    </div>
    <div class="form-group">
        <label for="modal_area">{{ get_phrase('Area') }}</label>
         <select id="modal_area" name="area" class="select2 form-control">
                    <option value="">Select an area</option>
                </select>
    </div>
    <div class="form-group">
        <label for="about">{{ get_phrase('Description') }}</label>
        <textarea name="about" class="border-0 bg-secondary content" id="about" cols="15" rows="5" placeholder="What's on your mind?"></textarea>
    </div>
    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="button" class="btn btn-secondary w-1/3" onclick="$('#customModal .custom-modal-closed').click();">
            {{ get_phrase('Cancel') }}
        </button>
       <button type="submit" class="btn btn-primary w-1/3" >
            {{ get_phrase('Post Discussion') }}
        </button>
    </div>
</form>
</div>

@include('frontend.initialize')
<script>
$(document).ready(function() {
    function initSelect2() {
        if ($.fn.select2) {
            $('.select2:not(.select2-hidden-accessible)').each(function() {
                var $this = $(this);
                $this.select2({
                    dropdownParent: $('#customModal'),
                    width: '100%',
                    placeholder: $this.find('option:first').text() || 'Select an option'
                });
            });
        }
    }

    // Initialize immediately
    initSelect2();

    // Re-initialize if triggered by the custom modal system
    $(document).off('customModalLoaded').on('customModalLoaded', function () {
        initSelect2();
    });

    if ($.fn.summernote) {
        $('.content:not(.initialized)').summernote({
            height: 150
        }).addClass('initialized');
    }

    // Fetch areas when city changes
    $(document).off('change', '#modal_city').on('change', '#modal_city', function() {
        var city_id = $(this).val();
        if(city_id) {
            $.ajax({
                url: "{{ url('ajax/groupareas/') }}/" + city_id,
                type: "GET",
                success: function(data) {
                    var $area = $('#modal_area');
                    $area.empty();
                    $area.append('<option value="">Select an area</option>');
                    
                    var responseData = typeof data === 'string' ? (typeof data === 'string' ? JSON.parse(data) : data) : data;
                    $.each(responseData, function(key, value) {
                        $area.append('<option value="'+ value.id +'">'+ value.area_name +'</option>');
                    });
                    
                    // Trigger change to update Select2
                    $area.trigger('change');
                }
            });
        }
    });

});
</script>
<style>
.select2-container {
    z-index: 999999 !important;
}
.custom-modal-body .form-group {
    margin-bottom: 15px !important;
}
.custom-modal-body label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}
.select2-container--default .select2-selection--single {
    height: 45px !important;
    padding: 8px !important;
    border: 1px solid #ced4da !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 43px !important;
}
</style>