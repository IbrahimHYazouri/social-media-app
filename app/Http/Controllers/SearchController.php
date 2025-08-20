<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $search = $request->input('search');

        if (empty($search)) {
            return redirect()->route('dashboard');
        }

        $users = User::query()
            ->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('username', 'like', '%' . $request->search . '%')
            ->latest()
            ->paginate(7);

        $posts = Post::query()
            ->where('body', 'like', '%' . $request->search . '%')
            ->latest()
            ->paginate(7);

        $groups = Group::query()
            ->where('name', 'like', '%' . $request->search . '%')
            ->latest()
            ->paginate(7);

        return response()->json([
            'search' => $search,
            'users' => UserResource::collection($users),
            'groups' => GroupResource::collection($groups),
            'posts' => PostResource::collection($posts),
        ]);
    }
}
