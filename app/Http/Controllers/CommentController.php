<?php

namespace App\Http\Controllers;
use App\Models\Board;
use App\Models\Card;
use App\Models\Comment;
use App\Models\Lists;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($cardId)
    {
        //$list = Lists::findOrFail($listId);

        $card = Card::findOrFail($cardId);
        if(Auth::user()->id !== $card->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }
        //$list = $board->lists()->findOrFail($listId);
        return response()->json(['comments' => $card->comments]);
    }

    public function show($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        if(Auth::user()->id !== $comment->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }
        $user = $comment->user;
        $comment['user_name'] = $user->username;

        return response()->json(['status' => 'success', 'comment' => $comment]);
    }

    public function store(Request $request, $cardId)
    {
        //$board = Board::findOrFail($boardId);

        $card = Card::find($cardId);
           $newComment = $card->comments()->create([
            'description' => $request->description,
               'user_id' => Auth::user()->id
        ]);
        $user = $newComment->user;
        $newComment['user_name'] = $user->username;
        return response()->json(['message' => 'success','comment'=>$newComment], 200);
    }
    public function update(Request $request,  $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        if(Auth::user()->id !== $comment->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }

        $comment->update($request->all());
        $comment->user_id = Auth::user()->id;
        $comment->save();
                //return $board->lists()->find($listId);
        $user = $comment->user;
        $comment['user_name'] = $user->username;
        return response()->json(['message' => 'success','comment'=>$comment], 200);
    }
//    public function updateAll(Request $request)
//    {
//        $newCards = $request->cards;
////        if(Auth::user()->id !== $card->list->board->user_id){
////            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
////        }
//            $cards = Card::all();
//            foreach ($cards as $card) {
//                foreach ($newCards as $newCard) {
//                    if($newCard['id'] == $card->id){
//                        $card->priority = $newCard['priority'];
//                        $card->save();
//                    }
//                }
//            }
////        $card->update($request->all());
////        $card->save();
//        //return $board->lists()->find($listId);
//        return response()->json(['message' => 'success','cards'=>$cards], 200);
//    }
    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        if(Auth::user()->id !== $comment->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }
//        $list = $board->lists()->findOrFail($listId);
//        $card = $list->cards()->findOrFail($cardId);
        if($comment->delete($commentId)) {
            return response()->json(['status' => 'success','message'=>'Comment Deleted successfully','comment'=>$comment], 200);
        }
        return response()->json(['status' => 'error','message'=>'Somthing went WRONG'], 404);
    }
}
