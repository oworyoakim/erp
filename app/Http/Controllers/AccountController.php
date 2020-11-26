<?php

namespace App\Http\Controllers;

use App\ErpHelper;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Traits\MakesRemoteHttpRequests;
use App\Traits\SendsEmailNotifications;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
        $this->urlEndpoint = env("HRMS_APP_URL");
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
        $logo = settings()->get('company_logo');
        if (empty($logo))
        {
            $logo = '/storage/images/logo2.png';
        }
        $data = [
            'companyLogo' => $logo,
        ];
        return view('account.login', $data);
    }

    public function processLogin(Request $request)
    {
        try
        {
            if (Sentinel::check())
            {
                throw new Exception("Already logged in. Reload page to continue!");
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
                return response()->json('Login Successful!');
            }
            throw new Exception('Invalid Credentials!');
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
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
                $reminderCode = $user->getPasswordResetReminderCode(true);
                $this->sendPasswordResetReminderEmail($user->email, $user->fullName(), $reminderCode);
            }
        } catch (Swift_TransportException $ex)
        {
            Log::error("Swift_TransportException: {$ex->getMessage()}");
        } catch (Exception $ex)
        {
            Log::error("Exception: {$ex->getMessage()}");
        }
        return response()->json('Request to reset password was sent to the email provided. Please check the email inbox for instructions.');
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
            $loggedInUser->tinymceApiKey = env('TINYMCE_API_KEY', null);
            $loggedInUser->dateFormat = settings()->get('date_format') ?: env('APP_DATE_FORMAT', 'd/m/Y');
            $employeeData = $this->get("{$this->urlEndpoint}/v1/employees/show-for-user/{$user->getUserId()}");
            $loggedInUser->employeeData = ErpHelper::arrayToObject($employeeData);
            return response()->json($loggedInUser);
        } catch (Exception $ex)
        {
            Log::error($ex->getMessage());
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function resetPasswordForm($email, $code)
    {
        try
        {
            $message = "This password reset link has expired!";
            if (empty($email) || empty($code))
            {
                throw new Exception($message);
            }
            $user = User::query()->where('email', $email)->first();
            if (!$user)
            {
                throw new Exception($message);
            }
            $reminderCode = $user->getPasswordResetReminderCode();
            if ($reminderCode != $code)
            {
                throw new Exception($message);
            }
            return view('account.reset-password', ['code' => $code, 'email' => $email]);
        } catch (Exception $ex)
        {
            Log::error("PASSWORD_RESET: {$ex->getMessage()}");
            session()->flash('error', $ex->getMessage());
            return redirect()->route('login');
        }
    }

    public function processResetPasswordForm(Request $request)
    {
        try
        {
            $message = "This password reset link has expired!";
            $rules = [
                'email' => 'required|email',
                'code' => 'required',
                'password' => 'required|confirmed:password_confirmation',
            ];
            $this->validateData($request->all(), $rules);
            $email = $request->get('email');
            $code = $request->get('code');
            if (empty($email) || empty($code))
            {
                throw new Exception($message);
            }
            $user = User::query()->where('email', $email)->first();
            if (!$user)
            {
                throw new Exception($message);
            }
            $reminderCode = $user->getPasswordResetReminderCode();
            if ($reminderCode != $code)
            {
                throw new Exception($message);
            }
            DB::beginTransaction();
            Reminder::complete($user, $code, $request->get('password'));
            DB::commit();
            session()->flash('success', "You have successfully reset your password!");
            return redirect()->route('login');
        } catch (Exception $ex)
        {
            DB::rollBack();
            Log::error("PASSWORD_RESET: {$ex->getMessage()}");
            session()->flash('error', $ex->getMessage());
            return redirect()->route('login');
        }
    }

}
