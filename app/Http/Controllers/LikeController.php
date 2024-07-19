<?php

namespace App\Http\Controllers;

use App\Events\LikeUpdated;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    private $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    // public function store($post_id)
    // {
    //     $this->like->user_id = Auth::user()->id;
    //     $this->like->post_id = $post_id;
    //     $this->like->save();

    //     $post = Post::findOrFail($post_id);
    //     broadcast(new LikeUpdated($post));

    //     return response()->json(['likes_count' => $post->likes->count()]);
    // }

    // public function destroy($post_id)
    // {
    //     $this->like
    //         ->where('user_id', Auth::user()->id)
    //         ->where('post_id', $post_id)
    //         ->delete();

    //     $post = Post::findOrFail($post_id);
    //     broadcast(new LikeUpdated($post));

    //     return response()->json(['likes_count' => $post->likes->count()]);
    // }

    public function store($post_id)
    {
        $userId = Auth::user()->id;

        // ライクが既に存在するかを確認
        $existingLike = Like::where('user_id', $userId)->where('post_id', $post_id)->first();

        if (!$existingLike) {
            $like = new Like();
            $like->user_id = $userId;
            $like->post_id = $post_id;
            $like->save();
        }

        $post = Post::findOrFail($post_id);
        broadcast(new LikeUpdated($post));

        return response()->json(['likes_count' => $post->likes->count()]);
    }

    public function destroy($post_id)
    {
        $userId = Auth::user()->id;

        Like::where('user_id', $userId)->where('post_id', $post_id)->delete();

        $post = Post::findOrFail($post_id);
        broadcast(new LikeUpdated($post));

        return response()->json(['likes_count' => $post->likes->count()]);
    }
}
