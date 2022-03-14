
@foreach($comments as $comment)
<div class="ml-10 bg-gray-100 shadow-base">
    <p><strong>{{ $comment->user->name }}</strong></p>
    <p>{{ $comment->comment }}</p>
    <a href="" id="reply"></a>
    <form method="post" action="{{ route('reply.add') }}">
        @csrf
        <div class="">
            <input required type="text" name="comment" class="form-control" />
            <input type="hidden" name="task_id" value="{{ $id }}" />
            <input type="hidden" name="comment_id" value="{{ $comment->id }}" />
        </div>
        <div class="flex">
            <input required type="submit" class="text-sm bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded mt-2 mb-3 scale-100"value="Reply"/>
            <p class="ml-3 mt-4">{{ $comment->created_at }}</p>
    </form>
    <form method="POST" action="/destroy/comment/{{ $comment->id }}">
        @method('delete')
        @csrf

        <button type="submit" class="ml-3 text-sm bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 border-b-4 border-red-700 hover:border-red-500 rounded mt-2 mb-3 scale-100">
            Delete
        </button>
    </form>
</div>
    @include('components.replys', ['comments' => $comment->replies])
</div>
@endforeach 