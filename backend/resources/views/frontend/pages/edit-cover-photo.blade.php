@php
    $page = \App\Models\Page::find($page_id);
@endphp

@if ($page)
    <img class="uploaded_place_here img-fluid rounded mb-5" width="100%"
         src="{{ get_page_cover_photo($page->coverphoto, 'coverphoto') }}" alt="Cover photo">

    <form class="ajaxForm" action="{{ route('page.coverphoto', $page->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="cover_photo">{{ get_phrase('Cover Photo') }}</label>
            <input type="file" id="cover_photo" class="form-control border-0 bg-secondary" name="cover_photo" accept="image/*">
        </div>
        <div class="mb-3">
            <button class="btn btn-primary w-100" type="submit">{{ get_phrase('Upload') }}</button>
        </div>
    </form>
@else
    <div class="alert alert-warning mb-3">Page not found.</div>
@endif

@include('frontend.initialize')
