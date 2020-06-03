<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Traits\MakesRemoteHttpRequests;
use App\Traits\SendsEmailNotifications;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use stdClass;
use Swift_TransportException;

class AccountController extends Controller
{
    use MakesRemoteHttpRequests, ValidatesHttpRequests, SendsEmailNotifications;

    public function __construct()
    {
        $this->urlEndpoint = env("HRMS_URL");
    }

    public function login(Request $request)
    {
        if (Sentinel::check())
        {
            $service = $request->attributes->get('service');
            if ($service)
            {
                return redirect()->intended(route("{$service}.dashboard"));
            }
            return redirect()->route("service");
        }
        return view('account.login');
    }

    public function processLogin(Request $request)
    {
        try
        {
            if (Sentinel::check())
            {
                $service = $request->attributes->get('service');
                if ($service)
                {
                    return redirect()->intended(route("{$service}.dashboard"));
                }
                return redirect()->route("service");
            }
            $this->validateData($request->all(), [
                'login_name' => 'required',
                'password' => 'required',
            ]);

            $user = User::query()->where('email', $request->get('login_name'))->first();
            if (!$user)
            {
                throw new Exception('Invalid Credentials!');
            }

            $role = $user->roles()->whereIn('type', ['employee', 'both'])->first();
            if ($role)
            {
                $url = "{$this->urlEndpoint}/v1/can-login";
                $params = ['userId' => $user->id];
                $response = $this->get($url, $params);
                //dd($response);
                if (!$response['canLogin'])
                {
                    throw new Exception('Sorry, your account is not active!');
                }
            }


            $credentials = [
                'email' => $request->get('login_name'),
                'password' => $request->get('password'),
            ];
            if ($user = Sentinel::authenticate($credentials))
            {
                session()->flash('success', 'Logged in!');
                return redirect()->route('service');
            }
            throw new Exception('Invalid Credentials!');
        } catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('login')->withInput();
        }
    }

    public function selectService(Request $request)
    {
        $service = $request->session()->get('service');
        if ($service)
        {
            return redirect()->route("{$service}.dashboard");
        }
        return view('account.service');
    }

    public function setService(Request $request)
    {
        try
        {
            $service = $request->session()->get('service');
            if ($service)
            {
                return redirect()->intended(route("{$service}.dashboard"));
            }
            $service = $request->get('service');
            if (!$service)
            {
                return redirect()->route('service');
            }
            $request->session()->put('service', $service);
            return redirect()->intended(route("{$service}.dashboard"));
        } catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('service')->withInput();
        }
    }

    public function changeService(Request $request)
    {
        try
        {
            $request->session()->remove('service');
            return redirect()->route('service');
        } catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('service')->withInput();
        }
    }

    public function resetPassword(Request $request)
    {
        try
        {
            $email = $request->get('email');
            $user = User::query()->where('email', $email)->first();
            if ($user)
            {
                $reminderCode = $user->getPasswordResetReminderCode();
                $this->sendPasswordResetReminderEmail($user->email, $user->fullName(), $reminderCode);
            }
        } catch (Swift_TransportException $ex)
        {
            Log::error("Swift_TransportException: {$ex->getMessage()}");
        } catch (Exception $ex)
        {
            Log::error("Exception: {$ex->getMessage()}");
        }
        session()->flash('success', 'Request to reset password was sent to the email provided. Please check the email inbox for instructions.');
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        $user = Sentinel::getUser();
        Sentinel::logout($user);
        $request->session()->remove('service');
        return redirect()->route('login');
    }

    public function getUserData()
    {
        try
        {
            $user = Sentinel::getUser();
            $loggedInUser = new stdClass();
            $loggedInUser->id = $user->getUserId();
            $loggedInUser->name = $user->fullName();
            $loggedInUser->lastName = $user->last_name;
            $loggedInUser->firstName = $user->first_name;
            $loggedInUser->username = $user->username;
            $loggedInUser->email = $user->email;
            $loggedInUser->avatar = $user->avatar;
            $loggedInUser->permissions = $user->getPermissions();
            $loggedInUser->role = null;
            if ($role = $user->roles()->first())
            {
                $loggedInUser->role = new stdClass();
                $loggedInUser->role->id = $role->id;
                $loggedInUser->role->title = $role->name;
                $loggedInUser->role->slug = $role->slug;
                $loggedInUser->permissions = array_merge($loggedInUser->permissions, $role->getPermissions());
            }
            return response()->json($loggedInUser);
        } catch (Exception $ex)
        {
            Log::error($ex->getMessage());
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
