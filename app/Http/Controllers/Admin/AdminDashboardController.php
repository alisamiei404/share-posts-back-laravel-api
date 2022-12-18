<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Post;
use App\Models\LogLogin;
use App\Models\LogRequest;

class AdminDashboardController extends Controller
{
    public function getAllLogin(Request $request)
    {
        $count = 10;
        $pageId = $request->pageId;
        $offset = ($pageId-1) * $count;

        $countItem = LogLogin::count();
        $items = DB::table('log_logins')->join('users', 'log_logins.user_id', '=', 'users.id')->orderBy('id', 'desc')
        ->select('log_logins.*', 'users.name', 'users.slug')->offset($offset)->limit($count)->get();

        $response = [
            'countItem' => $countItem,
            'countPage' => ceil($countItem/$count),
            'items' => $items,
        ];

        return $response;
    }

    public function getAllRequest(Request $request)
    {
        $count = 25;
        $pageId = $request->pageId;
        $offset = ($pageId-1) * $count;
        // <td>{{ jdate($comment->created_at)->format('%A, %d %B, H:i') }}</td>
        
        $countItem = LogRequest::count();
        // $items = DB::table('log_requests')->join('users', 'log_requests.user_id', '=', 'users.id')->orderBy('id', 'desc')
        // ->select('log_requests.*', 'users.name', 'users.slug')->offset($offset)->limit($count)->get();

        $items = DB::table('log_requests')->join('users', 'log_requests.user_id', '=', 'users.id')->orderBy('id', 'desc')
        ->select('log_requests.*', 'users.name', 'users.slug')->offset($offset)->limit($count)->get();

        $response = [
            'countItem' => $countItem,
            'countPage' => ceil($countItem/$count),
            'items' => $items,
        ];

        return $response;
    }

    public function getAllRequest1(Request $request)
    {
        $count = 25;
        $pageId = 1;
        $offset = ($pageId-1) * $count;
        
        $countItem = LogRequest::count();
        $items = DB::table('log_requests')->join('users', 'log_requests.user_id', '=', 'users.id')->orderBy('id', 'desc')
        ->select('log_requests.*', 'users.name', 'users.slug')->offset($offset)->limit($count)->get();

        $response = [
            'countItem' => $countItem,
            'countPage' => ceil($countItem/$count),
            'items' => $items,
        ];

        return $response;
    }
}