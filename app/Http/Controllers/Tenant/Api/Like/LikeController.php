<?php

namespace App\Http\Controllers\Tenant\Api\Like;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            $user->likes()->attach($request->postId);

            return $this->sendSuccess('Gönderi beğenildi.');
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function unlike(Request $request)
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            $user->likes()->detach($request->postId);
            return $this->sendSuccess('Gönderinin beğenisi kaldırıldı.');
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function likeShow($postId)
    {
        try {
            $post = Post::query()->find($postId);

            $users = $post->likes()->get();

            return UserResource::collection($users);
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
