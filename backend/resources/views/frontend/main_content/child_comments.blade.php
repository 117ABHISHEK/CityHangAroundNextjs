@if (session('spam_error'))
<div class="alert alert-danger mt-2">{{ session('spam_error') }}</div>
@endif

@foreach($child_comments as $child_comment)
@php $user_comment_reacts = json_decode($child_comment->user_reacts, true); @endphp

<li class="comment-item" id="comment_{{ $child_comment->comment_id }}">
    <div class="d-flex align-items-start gap-2 mb-1">

        {{-- Avatar --}}
        <div class="flex-shrink-0">
            <img class="rounded-circle"
                 style="width:30px;height:30px;object-fit:cover;"
                 src="{{ get_user_image($child_comment->photo, 'optimized') }}"
                 alt="Profile"
                 onerror="this.onerror=null;this.src='{{ asset('storage/userimage/default.png') }}';">
        </div>

        {{-- Bubble + meta --}}
        <div class="flex-grow-1">
            <div class="comment-content" style="position:relative;padding-bottom:16px;">
                <span class="fw-semibold d-block" style="font-size:12px;color:#1c1e21;margin-bottom:2px;">
                    {{ $child_comment->name }}
                </span>
                <p style="margin:0;font-size:13px;color:#1c1e21;">{{ $child_comment->description }}</p>

                <a class="comment-reaction-capsule" href="javascript:void(0)" id="comment_reacts<?php echo $child_comment->comment_id; ?>">
                    @include('frontend.main_content.comment_reacts', ['comment_react' => true])
                </a>
            </div>

            <div class="d-flex align-items-center gap-3 mt-1" style="padding-left:4px;">
                <span style="font-size:11px;color:#65676b;white-space:nowrap;">{{ date_formatter($child_comment->updated_at, 2) }}</span>

                <div class="post-react" style="position:relative;">
                    <a class="comment-action-link" href="javascript:void(0)"
                       onclick="myCommentReact('like','toggle',{{ $child_comment->comment_id }})"
                       id="my_comment_reacts<?php echo $child_comment->comment_id; ?>">
                        @include('frontend.main_content.comment_reacts', ['my_react' => true])
                    </a>
                    <ul class="react-list">
                        <li><a href="javascript:void(0)" onclick="myCommentReact('like','update',{{ $child_comment->comment_id }})"><img src="{{ asset('storage/images/like.svg') }}" alt="Like"></a></li>
                        <li><a href="javascript:void(0)" onclick="myCommentReact('love','update',{{ $child_comment->comment_id }})"><img src="{{ asset('storage/images/love.svg') }}" alt="Love"></a></li>
                        <li><a href="javascript:void(0)" onclick="myCommentReact('haha','update',{{ $child_comment->comment_id }})"><img src="{{ asset('storage/images/haha.svg') }}" alt="Haha"></a></li>
                        <li><a href="javascript:void(0)" onclick="myCommentReact('sad','update',{{ $child_comment->comment_id }})"><img src="{{ asset('storage/images/sad.svg') }}" alt="Sad"></a></li>
                        <li><a href="javascript:void(0)" onclick="myCommentReact('angry','update',{{ $child_comment->comment_id }})"><img src="{{ asset('storage/images/angry.svg') }}" alt="Angry"></a></li>
                    </ul>
                </div>

                <div class="dropdown" style="margin-left:auto;">
                    <a href="#" class="text-muted" style="font-size:16px;" data-bs-toggle="dropdown">···</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0)"
                               onclick="confirmAction('<?php echo route('comment.delete', ['comment_id' => $child_comment->comment_id]); ?>', true)"
                               class="dropdown-item text-danger">
                                <i class="fa fa-trash me-1"></i> {{ get_phrase('Delete Comment') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</li>

@endforeach