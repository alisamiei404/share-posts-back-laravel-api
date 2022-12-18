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

class AdminUserController extends Controller
{
    public function getAllUsers(Request $request)
    {
        $count = 10;
        $pageId = $request->pageId;
        $offset = ($pageId-1) * $count;
        $countItem = User::where('type', 'user')->count();
        $users = User::where('type', 'user')->orderBy('id', 'desc')->offset($offset)->limit($count)->get();

        $response = [
            'countItem' => $countItem,
            'countPage' => ceil($countItem/$count),
            'users' => $users
        ];

        return $response;
    }

    public function updateStatusUser(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        if($user->status == 'active')
        {
            $user->status = 'deactive';
            $tokens = DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();
        }
        else
        {
            $user->status = 'active';
        }
        $user->save();

        return $user;
    }

    public function deleteUser($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $posts = Post::where('user_id', $user->id)->delete();
        $postsLogLogin = LogLogin::where('user_id', $user->id)->delete();
        $postsLogRequest = LogRequest::where('user_id', $user->id)->delete();
        $tokens = DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();
        return $user->delete();
    }
}
