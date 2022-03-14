<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created Comment resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comment = new Comment();

        $comment->comment = $request->comment;

        $comment->user()->associate($request->user());

        $task = Task::find($request->get('task_id'));

        if($comment == null){
            return redirect()->back()->with('error', 'You can\'t create a blank comment!');
        }
        $task->comments()->save($comment);

        return back();
    }

    /**
     * Store a newly created Comment resource in storage which has a parent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function replyStore(Request $request)
    {
        $reply = new Comment();

        $reply->comment = $request->get('comment');

        $reply->user()->associate($request->user());

        $reply->parent_id = $request->get('comment_id');

        $task = Task::find($request->get('task_id'));

        if($reply == null){
            return redirect()->back()->with('error', 'You can\'t create a blank reply!');
        }
        $task->comments()->save($reply);

        return back();

    }

    /**
     * Remove the specified Comment resource from storage.
     *
     * @param  \App\Models\Int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment_entry = Comment::find($id);

        if($comment_entry->user_id !== Auth::id()){
            return redirect()->back()->with('error', 'This message belongs to ' . $comment_entry->user->name . '! You can\'t delete it!');
        }
        Comment::destroy($id);

        return redirect()->back()->with('success', 'Comment ' . $comment_entry->comment . ' and all of it\'s replies have been removed.');
    }
}
