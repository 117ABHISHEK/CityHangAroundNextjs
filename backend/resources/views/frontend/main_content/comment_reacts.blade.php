@if(isset($comment_react) && $comment_react == true)

@if(count($user_comment_reacts) > 0)
@php $comment_unique_values = array_unique($user_comment_reacts); @endphp

<span class="reaction-summary">

@foreach($comment_unique_values as $user_comment_react)

@if($user_comment_react == 'like')
<img class="reaction-icon" src="{{asset('storage/images/like.svg')}}">
@endif

@if($user_comment_react == 'love')
<img class="reaction-icon" src="{{asset('storage/images/love.svg')}}">
@endif

@if($user_comment_react == 'sad')
<img class="reaction-icon" src="{{asset('storage/images/sad.svg')}}">
@endif

@if($user_comment_react == 'angry')
<img class="reaction-icon" src="{{asset('storage/images/angry.svg')}}">
@endif

@if($user_comment_react == 'haha')
<img class="reaction-icon" src="{{asset('storage/images/haha.svg')}}">
@endif

@endforeach

<span class="reaction-count">{{count($user_comment_reacts)}}</span>

</span>
@endif

@endif



@if(isset($ajax_call) && $ajax_call)
<hr>
@endif



@if(Auth()->user())
@if(isset($my_react) && $my_react == true)

@if(array_key_exists(Auth()->user()->id, $user_comment_reacts))

@if($user_comment_reacts[Auth()->user()->id] == 'like')
<div class="like-color"><img class="reaction-icon-small" src="{{asset('storage/images/liked.svg')}}"> {{get_phrase('Liked')}}</div>
@endif

@if($user_comment_reacts[Auth()->user()->id] == 'love')
<div class="love-color"><img class="reaction-icon-small" src="{{asset('storage/images/love.svg')}}"> {{get_phrase('Loved')}}</div>
@endif

@if($user_comment_reacts[Auth()->user()->id] == 'haha')
<div class="sad-color"><img class="reaction-icon-small" src="{{asset('storage/images/haha.svg')}}"> {{get_phrase('Haha')}}</div>
@endif

@if($user_comment_reacts[Auth()->user()->id] == 'angry')
<div class="angry-color"><img class="reaction-icon-small" src="{{asset('storage/images/angry.svg')}}"> {{get_phrase('Angry')}}</div>
@endif

@if($user_comment_reacts[Auth()->user()->id] == 'sad')
<div class="sad-color"><img class="reaction-icon-small" src="{{asset('storage/images/sad.svg')}}"> {{get_phrase('Sad')}}</div>
@endif

@else

<div>
<img class="reaction-icon-small" src="{{asset('storage/images/liked.svg')}}">
{{get_phrase('Like')}}
</div>

@endif

@endif
@endif


<style>
  /* summary reaction icons */

.reaction-icon{
width:22px;
height:22px;
margin-right:4px;
}

/* icon near Like / Loved text */

.reaction-icon-small{
width:18px;
height:18px;
margin-right:5px;
}

/* reaction counter */

.reaction-count{
font-size:13px;
margin-left:4px;
color:#666;
}
  
</style>

