<?php

namespace App\Http\Controllers\Tenant\Api\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Api\Comment\CreateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(CreateCommentRequest $request, $commentableType, $commentableId)
    {
        try {
            $attributes = collect($request->validated());
            switch ($commentableType) {
                case 'post':
                    $model = Post::query()->find($commentableId);
                    break;
                case 'announcement':
                    // $model = Announcement::find($commentableId);
                    break;
                default:
                    throw new \Exception('Geçersiz type.');
            }

            throw_unless($model, \Exception::class, 'Modeli bulurken bir hata oluştu.');

            throw_unless($model->comments()->create([
                'user_id' => auth()->id(),
                'text' => $attributes->get('text')
            ]), \Exception::class, 'Cevap olustururken bir sorun oluştu..');


            return $this->sendSuccess('Cevap oluşturuldu.');
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function listCommentsWithMain()
    {

    }
}
