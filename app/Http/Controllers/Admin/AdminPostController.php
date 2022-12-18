<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Post;

class AdminPostController extends Controller
{
    public function getAllPosts(Request $request)
    {
        $pageId = +$request->pageId;
        $status = +$request->status;
        $count = +$request->count;
        $offset = ($pageId-1) * $count;

        if($status == 0 || $status == 1 || $status == 2)
        {
            $countItem = Post::where('status', $status)->count();
            $posts = Post::selectRaw('posts.*, users.name')
            ->where('posts.status', $status)
            ->orderBy('id', 'desc')->offset($offset)->limit($count)
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->get();
        }
        else
        {
            $countItem = Post::count();
            $posts = Post::selectRaw('posts.*, users.name')
            ->orderBy('id', 'desc')->offset($offset)->limit($count)
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->get();
        }
        

        $response = [
            'countItem' => $countItem,
            'status' => $status,
            'countPage' => ceil($countItem/$count),
            'posts' => $posts,
            'pageId' => $pageId,
            'cc' => $count,
        ];

        return $response;
    }

    public function getAllPostsUser(Request $request)
    {
        $slug = $request->slug;
        $user = User::where('slug', $slug)->firstOrFail();

        $count = 5;
        $pageId = $request->pageId;
        $status = +$request->status;
        $offset = ($pageId-1) * $count;

        if($status == 0 || $status == 1 || $status == 2)
        {
            $countItem = Post::where('posts.user_id', $user->id)->where('status', $status)->count();
            $posts = Post::selectRaw('posts.*, users.name')
            ->where('posts.user_id', $user->id)
            ->where('posts.status', $status)
            ->orderBy('id', 'desc')->offset($offset)->limit($count)
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->get();
        }
        else
        {
            $countItem = Post::where('posts.user_id', $user->id)->count();
            $posts = Post::selectRaw('posts.*, users.name')
            ->where('posts.user_id', $user->id)
            ->orderBy('id', 'desc')->offset($offset)->limit($count)
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->get();
        }
        

        $response = [
            'countItem' => $countItem,
            'status' => $status,
            'countPage' => ceil($countItem/$count),
            'posts' => $posts,
        ];

        return $response;
    }

    public function getPost(Request $request, $slug)
    {
        $posth = Post::where('slug', $slug)->firstOrFail();
        $post = DB::table('posts')->join('users', 'posts.user_id', '=', 'users.id')
                    ->where('posts.slug', '=', $slug)
                    ->select('posts.*', 'users.name')->first();

        return $post;
    }

    public function updateStatusPost(Request $request)
    {
        $slug = $request->slug;
        $status = +$request->status;
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->status = $status;
        $post->save();

        return $post;
    }

    public function deletePost($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return $post->delete();
    }

    public function deleteAllPosts()
    {
        return DB::table('posts')->delete();
    }
}
