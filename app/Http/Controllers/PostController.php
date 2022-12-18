<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Post;

class PostController extends Controller
{
    public function getAllPosts(Request $request)
    {
        $count = 5;
        $pageId = $request->pageId;
        $offset = ($pageId-1) * $count;
        $countItem = Post::where('status', 2)->count();

        $posts1 = Post::selectRaw('posts.*, users.name')
        ->where('posts.status', 2)
        ->orderBy('id', 'desc')->offset($offset)->limit($count)
        ->leftJoin('users', 'posts.user_id', '=', 'users.id')
        ->get();

        $response = [
            'countItem' => $countItem,
            'countPage' => ceil($countItem/$count),
            'posts' => $posts1,
            'url' => $request->url(),
            'method' => $request->method(),
        ];

        return $response;
    }

    public function getAllMyPosts(Request $request)
    {
        $count = 4;
        $pageId = $request->pageId;
        $offset = ($pageId-1) * $count;
        $countItem = Post::where('user_id', auth()->user()->id)->count();
        $posts = Post::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->offset($offset)->limit($count)->get();

        $response = [
            'countItem' => $countItem,
            'countPage' => ceil($countItem/$count),
            'posts' => $posts,
        ];

        return $response;
    }

    public function getPost(Request $request, $slug)
    {
        $posth = Post::where('slug', $slug)->firstOrFail();
        if($posth->status == 2){
            $posth->view_count = $posth->view_count + 1;
            $posth->save();
            $post = DB::table('posts')->join('users', 'posts.user_id', '=', 'users.id')
                    ->where('posts.slug', '=', $slug)
                    ->select('posts.*', 'users.name')->first();

            return $post;
        }else{
            if(auth('sanctum')->user()){
                if(auth('sanctum')->user()->id == $posth->user_id){
                    $post = DB::table('posts')->join('users', 'posts.user_id', '=', 'users.id')
                    ->where('posts.slug', '=', $slug)
                    ->select('posts.*', 'users.name')->first();

                    return $post;
                }else{
                    // abort(404);
                    return null;
                }
            }else{
                // abort(404);
                return null;
            }
        }
    }

    public function createPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:10',
        ]);

        $slug = Str::random(7);

        $post = new Post;
        $post->user_id = auth()->user()->id;
        $post->title = $request->input('title');
        $post->slug = $slug;
        $post->content = $request->input('content');
        $post->save();

        return $post;
    }

    public function editPost(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->where('user_id',auth()->user()->id)->firstOrFail();
        return $post;
    }

    public function updatePost(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->where('user_id',auth()->user()->id)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:10',
        ]);

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->status = 1;
        $post->save();

        return $post;
    }
    

    public function deleteAllPosts()
    {
        return DB::table('posts')->delete();
    }

    public function deletePost($slug)
    {
        $post = Post::where('slug', $slug)->where('user_id',auth()->user()->id)->firstOrFail();
        return $post->delete();
    }

    public function getSearch(Request $request)
    {
        return $request;
    }
 

}
