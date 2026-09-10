{{-- Recursive comment node. Requires: $comment, $itemType, $itemId, $canDeleteComment(closure). Optional: $depth --}}
@php $depth = $depth ?? 0; @endphp
<div class="comment" id="comment-{{ $comment->id }}">
    <div class="comment-head">
        <div class="comment-avatar">{{ mb_strtoupper(mb_substr($comment->author_name, 0, 1)) }}</div>
        <span class="comment-author">{{ $comment->author_name }}</span>
        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
    </div>
    <div class="comment-body">{!! nl2br(e($comment->body)) !!}</div>
    <div class="comment-actions">
        <button type="button" class="comment-reply-btn" onclick="toggleReplyForm({{ $comment->id }})"><i class="fas fa-reply"></i> Reply</button>
        @if($canDeleteComment($comment))
            <button type="button" class="comment-delete-btn" onclick="deleteComment({{ $comment->id }})"><i class="fas fa-trash"></i> Delete</button>
        @endif
    </div>
    <div class="reply-form" id="reply-form-{{ $comment->id }}" style="display: none;">
        <textarea id="replyBody-{{ $comment->id }}" placeholder="Write a reply..." rows="2" required></textarea>
        <div class="form-actions">
            <button type="button" class="comment-submit" onclick="submitReply({{ $comment->id }})"><i class="fas fa-paper-plane"></i> Post Reply</button>
        </div>
    </div>

    @if($comment->replies->count() > 0)
        <div class="comment-replies">
            @foreach($comment->replies as $reply)
                @include('partials.comment-node', [
                    'comment'         => $reply,
                    'depth'           => $depth + 1,
                    'itemType'        => $itemType,
                    'itemId'          => $itemId,
                    'canDeleteComment'=> $canDeleteComment,
                ])
            @endforeach
        </div>
    @endif
</div>