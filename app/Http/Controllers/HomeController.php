<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $post;
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();
        $popularPosts = Post::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(5)
            ->get();

        return view('users.home', compact('home_posts', 'suggested_users', 'popularPosts'));
            // ->with('home_posts', $home_posts)
            // ->with('suggested_users', $suggested_users);
    }

    public function suggest($id)
    {
        $user = $this->user->findOrFail($id);
        $suggested_users = $this->getAllSuggestedUsers();

        return view('users.profile.suggestions')
            ->with('user', $user)
            ->with('suggested_users', $suggested_users);
    }

    # Get the posts of the users that the Auth user is following
    private function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = []; // In case the $home_posts is empty, it will not return NULL, but empty instead

        foreach ($all_posts as $post) {
            if ($post->user->isFollowed() || $post->user->id === Auth::user()->id) {
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    # Get the users that the Auth user is not  following
    private function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach ($all_users as $user) {
            if (!$user->isFollowed()) {
                $suggested_users[] = $user;
            }
        }

        return array_slice($suggested_users, 0, 5);
        // array_slice(x, y, z)
        // x -- array
        // y -- offset/starting index
        //z -- length/how many
    }

    private function getAllSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach ($all_users as $user) {
            if (!$user->isFollowed()) {
                $suggested_users[] = $user;
            }
        }

        return $suggested_users;
    }

    public function search(Request $request)
    {
        $users = $this->user
            ->where('name', 'like', '%' . $request->search . '%')
            ->where('id', '!=', Auth::user()->id)
            ->get();

        return view('users.search')->with('users', $users)->with('search', $request->search);
    }
}
