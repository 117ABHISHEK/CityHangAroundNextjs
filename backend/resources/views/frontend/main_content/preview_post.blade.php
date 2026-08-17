<style>


.preview-container{
max-width:1400px;
margin:auto;
}

/* MEDIA AREA */

.preview-media-area{
background:#000;
display:flex;
align-items:center;
justify-content:center;
height:80vh;
border-radius:10px 0 0 10px;
}

.media-holder{
width:100%;
text-align:center;
}

.preview-media{
max-width:100%;
max-height:80vh;
object-fit:contain;
border-radius:8px;
}

/* SIDE PANEL */

.preview-side-panel{
background:#fff;
height:80vh;
border-radius:0 10px 10px 0;
display:flex;
flex-direction:column;
}

.post-panel{
padding:15px;
overflow-y:auto;
flex:1;
}

/* Beautify reactions */

.post-panel .like-comment-share{
position:sticky;
bottom:0;
background:#fff;
padding-top:10px;
}

/* modal background */

.modal-body{
background:#f0f2f5;
}




</style>


<div class="container-fluid preview-container">

<div class="row g-0">

<!-- LEFT LARGE MEDIA -->
<div class="col-lg-9 preview-media-area">

<div class="media-holder">

@foreach($posts as $post)

@php
$media_files = DB::table('media_files')
->where('post_id',$post->post_id)
->get();
@endphp

@foreach($media_files as $media_file)

@if($media_file->file_type == 'video')

@if(File::exists(public_path('storage/post/videos/'.$media_file->file_name)))

<video controls class="preview-media">

<source src="{{ get_post_video($media_file->file_name) }}">

</video>

@endif

@else

<img src="{{ get_post_image($media_file->file_name) }}" class="preview-media">

@endif

@endforeach

@endforeach

</div>

</div>



<!-- RIGHT POST PANEL -->
<div class="col-lg-3 preview-side-panel">

<div class="post-panel" id="postPreviewSection">

@include('frontend.main_content.posts',['type'=>'user_post'])

</div>

</div>

</div>

</div>