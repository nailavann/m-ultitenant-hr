<?php

namespace App\Http\Controllers\Tenant\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageHelper;
use App\Http\Requests\Tenant\Api\Post\CreatePostRequest;
use App\Http\Resources\Tenant\Api\Comment\CommentResource;
use App\Http\Resources\Tenant\Api\Post\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{

    public function listPosts(Request $request)
    {
        $userLikes = auth()->user()->likes()->pluck('posts.id');

        /** @var Post $posts */
        $posts = Post::query()->with(['user'])->withCount('comments', 'likes')->latest()->paginate(20);

        $posts->transform(function (Post $post) use ($userLikes) {
            $post->isLiked = $userLikes->contains($post->id);
            return $post;
        });
        return PostResource::collection($posts);
    }

    public function createPost(CreatePostRequest $request)
    {
        try {
            $attributes = collect($request->validated());

            /** @var User $user */
            $user = auth()->user();

            $user->posts()->create([
                'text' => $attributes->get('text'),
                'image' => $attributes->get('image') === null ? null
                    : (new ImageHelper())->uploadImage($attributes->get('image'), 'posts'),
            ]);

            return $this->sendSuccess('Gönderi oluşturuldu.');
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function editPost(CreatePostRequest $request, $postId)
    {
        try {
            $attributes = collect($request->validated());

            /** @var Post $post */
            $post = Post::query()->find($postId);
            throw_unless($post, \Exception::class, 'Gönderi bulunamadı.');

            $post->text = $attributes->get('text');
            if (!($post->image === $attributes->get('image'))) {
                $post->image = (new ImageHelper())->uploadImage($attributes->get('image'), 'posts');
            }
            $post->save();

            return $this->sendSuccess('Gönderi güncellendi.');
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function deletePost($postId)
    {
        try {
            throw_unless(Post::query()->find($postId)->delete(), \Exception::class, 'Gönderiyi silerken bir sorun oluştu.');
            return $this->sendSuccess('Gönderi silindi.');
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function getPostDetail(Request $request, $postId)
    {
        try {

            /** @var User $user */
            $user = auth()->user();

            /** @var Post $post */
            $post = Post::query()->with('user')->withCount('comments', 'likes')->find($postId);
            throw_unless($post, \Exception::class, 'Gönderi bulunamadı.');

            $post->isLiked = !!($user->likes()->where('posts.id', $post->id)->first());

            /** @var Comment $comments */
            $comments = $post->comments()->with('user')->paginate(25);

            return CommentResource::collection($comments)->additional([
                'post' => PostResource::make($post),
            ]);
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
