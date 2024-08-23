<?php

namespace App\Http\Controllers\Tenant\Api\Folder;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageHelper;
use App\Http\Helpers\PDFHelper;
use App\Http\Requests\Tenant\Api\Folder\FolderRequest;
use App\Http\Resources\Tenant\Api\FolderResource;
use App\Models\User;
use Throwable;

class FolderController extends Controller
{
    private array $allowedMimeType = [
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/heic',
    ];

    public function getFiles()
    {
        try {
            /** @var User $user */
            $user = auth()->user();
            $files = $user->folders()->get();

            return FolderResource::collection($files);
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    public function folderUpload(FolderRequest $request)
    {
        try {
            if (!in_array($request->mimeType, $this->allowedMimeType)) {
                return $this->sendError('Geçersiz dosya tipi');
            }

            /** @var User $user */
            $user = auth()->user();

            if ($request->mimeType === 'application/pdf') {
                $type ='pdf';
                $path = (new PDFHelper())->uploadPDF($request->base64, 'pdf');
            } else {
                $type = 'image';
                $path = (new ImageHelper())->uploadImage($request->base64, 'images');
            }

            $user->folders()->create([
                'name' => $request->name,
                'type' => $type,
                'path' => $path,
            ]);

            return $this->sendSuccess('Dosya başarıyla yüklendi.');
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }

    }

}
