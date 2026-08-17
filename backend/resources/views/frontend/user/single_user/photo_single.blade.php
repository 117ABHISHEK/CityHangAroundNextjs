@foreach($all_photos as $photo)
   <a href="{{ route('single.post',$photo->post_id) }}">
    <div class="single-photo">
        <img src="{{get_post_image($photo->file_name, 'optimized')}}" alt="">
    </div>
   </a>
@endforeach
