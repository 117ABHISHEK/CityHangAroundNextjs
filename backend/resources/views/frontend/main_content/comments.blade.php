@foreach($comments as $comment)
@php
    $total_child_comments = DB::table('comments')->where('comments.is_type', $type)->where('comments.parent_id', $comment->comment_id)->get()->count();
    $child_comments = DB::table('comments')
        ->join('users', 'comments.user_id', '=', 'users.id')
        ->where('comments.parent_id', $comment->comment_id)
        ->select('comments.*', 'users.name', 'users.photo')
        ->orderBy('comment_id', 'DESC')->take(1)->get();
    $user_comment_reacts = json_decode($comment->user_reacts, true);
@endphp

@if (session('spam_error'))
<div class="alert alert-danger mt-2">{{ session('spam_error') }}</div>
@endif

{{-- Comment item --}}
<li class="comment-item" id="comment_{{ $comment->comment_id }}">

    {{-- Avatar + bubble row --}}
    <div class="d-flex align-items-start gap-2 mb-1">

        {{-- Avatar --}}
        <div class="flex-shrink-0">
            @if (isset($type) && $type == "page")
                <img class="rounded-circle"
                     style="width:36px;height:36px;object-fit:cover;"
                     src="{{ get_page_logo($comment->photo, 'logo') }}"
                     alt="Profile"
                     onerror="this.onerror=null;this.src='{{ asset('storage/pages/logo/default.png') }}';">
            @else
                <img class="rounded-circle"
                     style="width:36px;height:36px;object-fit:cover;"
                     src="{{ get_user_image($comment->photo, 'optimized') }}"
                     alt="Profile"
                     onerror="this.onerror=null;this.src='{{ asset('storage/userimage/default.png') }}';">
            @endif
        </div>

        {{-- Bubble + actions --}}
        <div class="flex-grow-1">

            {{-- Comment bubble --}}
            <div class="comment-content" style="position:relative;padding-bottom:16px;">
                <span class="fw-semibold d-block" style="font-size:13px;color:#1c1e21;margin-bottom:2px;">
                    {{ $comment->name }}
                </span>
                <p style="margin:0;font-size:14px;color:#1c1e21;">{{ $comment->description }}</p>

                {{-- Reaction count on bubble --}}
                <a class="comment-reaction-capsule" href="javascript:void(0)" id="comment_reacts<?php echo $comment->comment_id; ?>">
                    @include('frontend.main_content.comment_reacts', ['comment_react' => true])
                </a>
            </div>

            {{-- Timestamp + action bar --}}
            <div class="d-flex align-items-center gap-3 mt-1" style="padding-left:4px;">
                <span style="font-size:11px;color:#65676b;white-space:nowrap;">{{ date_formatter($comment->updated_at, 2) }}</span>

                {{-- Like reaction with popup --}}
                <div class="post-react" style="position:relative;">
                    <a class="comment-action-link" href="javascript:void(0)"
                       onclick="myCommentReact('like','toggle',{{ $comment->comment_id }})"
                       id="my_comment_reacts<?php echo $comment->comment_id; ?>">
                        @include('frontend.main_content.comment_reacts', ['my_react' => true])
                    </a>
                    <ul class="react-list">
                        <li><a href="javascript:void(0)" onclick="myCommentReact('like','update',{{ $comment->comment_id }})"><img src="{{ asset('storage/images/like.svg') }}" alt="Like"></a></li>
                        <li><a href="javascript:void(0)" onclick="myCommentReact('love','update',{{ $comment->comment_id }})"><img src="{{ asset('storage/images/love.svg') }}" alt="Love"></a></li>
                        <li><a href="javascript:void(0)" onclick="myCommentReact('haha','update',{{ $comment->comment_id }})"><img src="{{ asset('storage/images/haha.svg') }}" alt="Haha"></a></li>
                        <li><a href="javascript:void(0)" onclick="myCommentReact('sad','update',{{ $comment->comment_id }})"><img src="{{ asset('storage/images/sad.svg') }}" alt="Sad"></a></li>
                        <li><a href="javascript:void(0)" onclick="myCommentReact('angry','update',{{ $comment->comment_id }})"><img src="{{ asset('storage/images/angry.svg') }}" alt="Angry"></a></li>
                    </ul>
                </div>

                {{-- Reply link --}}
                <a class="comment-action-link" href="javascript:void(0)"
                   onclick="$('.parent_comment_reply_fields').not('#reply_field{{$comment->comment_id}}').slideUp(150); $('#reply_field{{$comment->comment_id}}').slideToggle(150); setTimeout(function(){ $('#reply_field{{$comment->comment_id}} .comment-input').focus(); }, 160);">
                    Reply
                </a>

                {{-- Delete dropdown --}}
                <div class="dropdown" style="margin-left:auto;">
                    <a href="#" class="text-muted" style="font-size:16px;line-height:1;" data-bs-toggle="dropdown" aria-expanded="false">···</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0)"
                               onclick="confirmAction('<?php echo route('comment.delete', ['comment_id' => $comment->comment_id]); ?>', true)"
                               class="dropdown-item text-danger">
                                <i class="fa fa-trash me-1"></i> {{ get_phrase('Delete Comment') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Reply input (hidden by default) --}}
            <div class="parent_comment_reply_fields" id="reply_field{{ $comment->comment_id }}"
                 style="margin-top:8px;display:none;">
                <div class="d-flex align-items-center gap-2">
                    @if(Auth()->user())
                    <img src="{{ get_user_image(Auth()->user()->photo, 'optimized') }}"
                         alt=""
                         class="rounded-circle flex-shrink-0"
                         style="width:28px;height:28px;object-fit:cover;"
                         onerror="this.src='/storage/pages/logo/default.png'">
                    @endif
                    <div class="comment-input-wrap flex-grow-1">
                        <input class="form-control comment-input"
                               onkeypress="postComment(this, {{ $comment->comment_id }}, {{ $post_id }}, 0, '{{ $type }}');"
                               placeholder="Write your reply…">
                        <button class="send-btn" type="button"
                                onclick="submitComment(this, {{ $comment->comment_id }}, {{ $post_id }}, 0, '{{ $type }}')"
                                title="Send reply">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- end avatar+bubble row --}}

    {{-- Nested child comments --}}
    <ul class="comment-item-nested list-unstyled" id="child_comments{{ $comment->comment_id }}">
        @include('frontend.main_content.child_comments')
    </ul>

    @if($child_comments->count() < $total_child_comments)
        <a class="btn btn-sm" style="color:#1877f2;padding-left:44px;font-size:12px;font-weight:600;"
           onclick="loadMoreComments(this, {{ $post_id }}, {{ $comment->comment_id }}, {{ $total_child_comments }}, '{{ $type }}')">
            {{ get_phrase('View more replies') }}
        </a>
    @endif

</li>

@endforeach