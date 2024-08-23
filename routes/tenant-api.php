<?php

use App\Http\Controllers\Tenant\Api\Announcement\AnnouncementController;
use App\Http\Controllers\Tenant\Api\Auth\AuthController;
use App\Http\Controllers\Tenant\Api\Comment\CommentController;
use App\Http\Controllers\Tenant\Api\Company\CompanyController;
use App\Http\Controllers\Tenant\Api\Department\DepartmentController;
use App\Http\Controllers\Tenant\Api\Device\DeviceController;
use App\Http\Controllers\Tenant\Api\ExchangeRate\ExchangeRateController;
use App\Http\Controllers\Tenant\Api\Folder\FolderController;
use App\Http\Controllers\Tenant\Api\Like\LikeController;
use App\Http\Controllers\Tenant\Api\News\NewsController;
use App\Http\Controllers\Tenant\Api\Post\PostController;
use App\Http\Controllers\Tenant\Api\User\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


/** API */
Route::prefix('api')->middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('attach-device', [DeviceController::class, 'store']);
        Route::post('update-device', [DeviceController::class, 'update']);
        Route::post('change-avatar', [UserController::class, 'changeAvatar']);
        Route::post('personal-information', [UserController::class, 'personalInformation']);
        Route::put('education-information/{educationId}', [UserController::class, 'educationInformation']);
        Route::put('address-information/{addressId}', [UserController::class, 'addressInformation']);
        Route::put('emergency-information/{emergencyId}', [UserController::class, 'emergencyInformation']);
        Route::post('createAnnouncement', [AnnouncementController::class, 'createAnnouncement']);
        Route::get('exchange-rate', ExchangeRateController::class);
        Route::get('news', [NewsController::class, 'allNews']);
        Route::get('news-detail/{newsId}', [NewsController::class, 'newsDetail']);
        Route::get('user', [UserController::class, 'getUser']);
        Route::get('my-team', [UserController::class, 'getMyTeam']);
        Route::get('my-managers', [UserController::class, 'getMyManagers']);
        Route::get('user/{id}', [UserController::class, 'getUserDetail']);
        Route::get('user-list', [UserController::class, 'userList']);
        Route::get('special-days', [UserController::class, 'specialDays']);
        Route::get('departments', DepartmentController::class);
        Route::get('companies', CompanyController::class);
        Route::post('create-post', [PostController::class, 'createPost']);
        Route::put('edit-post/{postId}', [PostController::class, 'editPost']);
        Route::delete('delete-post/{postId}', [PostController::class, 'deletePost']);
        Route::get('list-posts', [PostController::class, 'listPosts']);
        Route::get('post-detail/{postId}', [PostController::class, 'getPostDetail']);
        Route::post('create-comment/{commentableType}/{commentableId}', [CommentController::class, 'createComment']);
        Route::get('like-show/{postId}', [LikeController::class, 'likeShow']);
        Route::post('like', [LikeController::class, 'like']);
        Route::post('unlike', [LikeController::class, 'unlike']);
        Route::get('files',[FolderController::class, 'getFiles']);
        Route::post('folder-upload',[FolderController::class, 'folderUpload']);

        Route::get('comments-with-main', [CommentController::class, 'listCommentsWithMain']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('createNewUser', [UserController::class, 'createNewUser']);
    });

});

