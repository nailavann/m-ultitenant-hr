<?php

namespace App\Http\Controllers\Tenant\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageHelper;
use App\Http\Helpers\SpecialDayHelper;
use App\Http\Requests\CreateNewUserRequest;
use App\Http\Requests\Tenant\Api\User\AddressInformationRequest;
use App\Http\Requests\Tenant\Api\User\EducationInformationRequest;
use App\Http\Requests\Tenant\Api\User\EmergencyInformationRequest;
use App\Http\Requests\Tenant\Api\User\PersonalInfoRequest;
use App\Http\Resources\Tenant\Api\Manager\ManagerResource;
use App\Http\Resources\User\UserResource;
use App\Models\Address;
use App\Models\Education;
use App\Models\Emergency;
use App\Models\User;
use App\Notifications\UserInviteNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

class UserController extends Controller
{

    public function getUser(): JsonResponse|UserResource
    {
        try {
            /** @var User $user */
            $user = auth()->user();
            $user->load([
                'information',
                'information.company',
                'information.department',
                'leaveInformation',
                'addresses',
                'educations',
                'emergencies',
                'roles'
            ]);

            return UserResource::make($user);
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function userList(Request $request)
    {
        try {
            $cachePrefix = 'getContact' . $request->department . $request->company . $request->searchKey;
            $users = Cache::remember($cachePrefix, 3600, function () use ($request) {
                /** @var User $user */
                $users = User::query()
                    ->withWhereHas('information', function ($query) use ($request) {
                        if ($request->department) {
                            $query->where('department_id', $request->department);
                        }
                        if ($request->company) {
                            $query->where('company_id', $request->company);
                        }

                    });
                if ($request->searchKey) {
                    $users = $users->whereAny([
                        'name',
                        'surname',
                    ], 'LIKE', "%$request->searchKey%");
                }

                return $users->get();
            });

            return UserResource::collection($users)->additional([
                'userCount' => $users->count()
            ]);
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }


    /**
     * @throws Throwable
     */
    public function getUserDetail($id)
    {
        try {
            $user = User::query()->with([
                'information',
                'information.company',
                'information.department',
                'emergencies',
                'addresses',
                'educations'
            ])->find($id);
            throw_unless($user, \Exception::class, 'Kullanıcı bulunamadı.');
            return UserResource::make($user);
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function getMyTeam()
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            $users = Cache::remember('myTeam', 3600, function () use ($user) {
                return User::query()->whereHas('information', function ($query) use ($user) {
                    $query->where('department_id', $user->information->department_id);
                    $query->where('company_id', $user->information->company_id);
                })
                    ->where('id', '!=', $user->id)
                    ->get();
            });
            return UserResource::collection($users);
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function getMyManagers()
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            $managers = $user->managers()->get();

            return ManagerResource::collection($managers);
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function specialDays(Request $request)
    {
        try {
            /** @var User $currentUser */
            $currentUser = auth()->user();

            /** @var User $users */
            $users = User::query()
                ->select('users.id', 'users.avatar', 'users.name', 'users.surname', 'companies.name as companyName', 'user_information.birth_date', 'user_information.position_started_at')
                ->leftJoin('user_information', 'users.id', '=', 'user_information.user_id')
                ->leftJoin('companies', 'companies.id', '=', 'user_information.company_id')
                ->get();

            $startDate = Carbon::now();
            $filter = $request->input('filter');
            $specialDays = collect();

            for ($i = 0; $i < 1050; $i++) {
                $currentDate = $startDate->clone()->addDays($i);
                $currentDateString = $currentDate->format('Y-m-d');

                $specialUsers = $users->filter(function ($user) use ($currentDate, $filter) {

                    if ($filter == 'birthDate') {
                        return $this->monthAndYearCheck($user->birth_date, $currentDate);
                    }

                    if ($filter == 'position') {
                        return $this->monthAndYearCheck($user->position_started_at, $currentDate);
                    }

                    if ($filter != 'holiday') {
                        return ($this->monthAndYearCheck($user->birth_date, $currentDate))
                            || ($this->monthAndYearCheck($user->position_started_at, $currentDate));
                    }
                })->map(function ($user) use ($currentDate, $currentUser) {
                    if ($this->monthAndYearCheck($user->birth_date, $currentDate)) {
                        $event_title = $this->eventTitleResponse($user, $currentUser, 'birthDate');
                        $event_params = round(Carbon::parse($user->birth_date)->diffInYears($currentDate)) . ' yaşında!';
                        $event_icon = 'birthday-cake';
                    }

                    if ($this->monthAndYearCheck($user->position_started_at, $currentDate)) {
                        $event_title = $this->eventTitleResponse($user, $currentUser, 'positionStarted');
                        $event_params = $user->companyName . ' ile ' . round(Carbon::parse($user->position_started_at)->diffInYears($currentDate)) . '. yılı';
                        $event_icon = 'child';
                    }

                    return [
                        'avatar' => $user->avatar,
                        'eventTitle' => $event_title,
                        'eventParams' => $event_params,
                        'eventIcon' => $event_icon
                    ];
                });

                if (isset(SpecialDayHelper::$specialDays[$currentDateString]) && (!$filter || $filter === 'holiday')) {
                    $specialUsers->push([
                        'holiday' => true,
                        'eventTitle' => SpecialDayHelper::$specialDays[$currentDateString],
                        'eventIcon' => 'concierge-bell'
                    ]);
                }

                if ($specialUsers->isNotEmpty()) {
                    $specialDays->push([
                        'year' => $currentDate->format('Y'),
                        'date' => $currentDate->locale('tr')->isoFormat('dddd, DD MMM'),
                        'users' => $specialUsers->values()
                    ]);
                }
            }


            $page = $request->get('page');
            $perPage = 20;
            $offset = ($page - 1) * $perPage;
            $limitedSpecialDays = $specialDays->slice($offset, $perPage)->values();
            $totalItems = $specialDays->count();
            $hasNextPage = $offset + $perPage < $totalItems;

            return [
                'data' => $limitedSpecialDays,
                'current_page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($totalItems / $perPage),
                'hasNextPage' => $hasNextPage
            ];

        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }


    private function monthAndYearCheck($date, $currentDate)
    {
        $date = Carbon::parse($date);
        if ($date->isSameDay($currentDate)) {
            return null;
        }
        return $date && Carbon::parse($date)->isBirthday($currentDate);
    }

    private function eventTitleResponse($user, $currentUser, $type)
    {
        if ($type == 'birthDate') {
            if ($user->id == $currentUser->id) {
                return 'Mutlu yıllar!';
            } else {
                return ucfirst($user->fullName) . ' doğum gününü kutluyor!';
            }
        } else {
            if ($user->id == $currentUser->id) {
                return 'Çalışma yıl dönümünüzü kutlarız!';
            } else {
                return ucfirst($user->fullName) . ' çalışma yıl dönümünü kutluyor';
            }
        }
    }


    /**
     * @throws Throwable
     */

    public function changeAvatar(Request $request)
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            $user->avatar = (new ImageHelper())->uploadImage($request->image, 'avatar');
            $user->save();

            return $this->sendSuccess('Fotoğraf başarıyla değiştirildi.', [
                'image' => $user->avatar
            ]);
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function personalInformation(PersonalInfoRequest $request)
    {
        try {

            $attributes = collect($request->validated());
            $attributes->put('birth_date', Carbon::parse($attributes->get('birth_date'))->format('Y-m-d'));

            /** @var User $user */
            $user = auth()->user();

            $user->update([
                'name' => $attributes->get('name'),
                'surname' => $attributes->get('surname')
            ]);

            $user->information()->update($attributes->except('name', 'surname')->toArray());


            return $this->sendSuccess('Bilgileriniz başarıyla güncellendi.', UserResource::make($user->load('information')));
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function educationInformation(EducationInformationRequest $request, $educationId)
    {
        try {

            $attributes = collect($request->validated());
            $attributes->put('started_at', Carbon::parse($attributes->get('started_at'))->format('Y-m-d'));
            $attributes->put('ended_at', Carbon::parse($attributes->get('ended_at'))->format('Y-m-d'));

            /** @var User $user */
            $user = auth()->user();


            /** @var Education $education */
            $education = $user->educations()->find($educationId);

            throw_unless($education, \Exception::class, 'Okul bilgisi yok');

            $education->update($attributes->toArray());

            return $this->sendSuccess('Eğitim bilgileriniz başarıyla güncellendi.', UserResource::make($user->load('educations')));
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function addressInformation(AddressInformationRequest $request, $addressId)
    {
        try {

            $attributes = collect($request->validated());

            /** @var User $user */
            $user = auth()->user();


            /** @var Address $address */
            $address = $user->addresses()->find($addressId);
            throw_unless($address, \Exception::class, 'Adres bilgisi yok');

            $address->update($attributes->toArray());

            return $this->sendSuccess('Adres bilgileriniz başarıyla güncellendi.', UserResource::make($user->load('addresses')));
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function emergencyInformation(EmergencyInformationRequest $request, $emergencyId)
    {
        try {

            $attributes = collect($request->validated());

            /** @var User $user */
            $user = auth()->user();

            /** @var Emergency $emergency */
            $emergency = $user->emergencies()->find($emergencyId);
            throw_unless($emergency, \Exception::class, 'Acil durum bilgisi yok');

            $emergency->update($attributes->toArray());

            return $this->sendSuccess('Acil durum bilgileriniz başarıyla güncellendi.', UserResource::make($user->load('emergencies')));
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function createNewUser(CreateNewUserRequest $request): JsonResponse
    {
        try {
            $attributes = collect($request->validated());

            $password = Str::random(8);
            $attributes->put('password', bcrypt($password));

            /** @var User $user */
            $user = User::query()->create($attributes->only('email', 'password')->toArray());
            throw_unless($user, \Exception::class, 'Yeni üye oluştururken bir sorun oluştu..');

            throw_unless($user->information()->create($attributes->except('email', 'password')->toArray()), \Exception::class, 'Üye bilgilerini oluştururken sorun oluştu..');
            $user->notify(new UserInviteNotification($password));
            return $this->sendSuccess('Yeni üye oluşturuldu.');
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}

