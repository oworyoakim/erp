<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\GatewayController;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class EmployeesGateway extends GatewayController
{
    use ValidatesHttpRequests;

    public function __construct()
    {
        $this->urlEndpoint = env('HRMS_APP_URL') . '/v1/employees';
    }

    public function index(Request $request)
    {
        try
        {
            if(!Sentinel::hasAnyAccess(['employees', 'employees.view', 'employees.create', 'employees.update', 'employees.delete'])){
                throw new Exception("Permission Denied!");
            }
            $params = $request->all();

            $responseData = $this->get($this->urlEndpoint, $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function indexUnscoped(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/unscoped", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(Request $request)
    {
        try
        {
            if(!Sentinel::hasAnyAccess(['employees.create'])){
                throw new Exception("Permission Denied!");
            }
            $rules = [
                'firstName' => 'required',
                'lastName' => 'required',
                'password' => 'required',
                'email' => 'required|email|unique:users,email',
                'roleId' => 'required',
            ];
            // get all the form data
            $data = $request->all();
            // validate the data for users table
            $this->validateData($data, $rules);

            $loggedInUser = Sentinel::getUser();
            $data['createdBy'] = $loggedInUser->getUserId();

            $role_id = $request->get('roleId');
            $role = Sentinel::getRoleRepository()->findById($role_id);
            //TODO: Create the user first
            if (!$role)
            {
                throw new Exception('The selected role was not found!');
            }

            $credentials = [
                'email' => strtolower($data['email']),
                'username' => strtolower($data['email']),
                'password' => $data['password'],
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'avatar' => '/storage/images/avatar.png',
            ];

            DB::beginTransaction();
            // create the employee
            $user = Sentinel::registerAndActivate($credentials);
            if (!$user)
            {
                throw new Exception('Failed to create employee!');
            }
            $role->users()->attach($user);
            $attachedRole = $user->roles()->find($role->id);
            if (!$attachedRole)
            {
                throw new Exception('Failed to attach role to employee!');
            }

            $data['userId'] = $user->getUserId();
            // create the employee in the remote service
            $responseData = $this->post($this->urlEndpoint, $data);
            // success, commit
            DB::commit();
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            // rollback on any failure
            DB::rollBack();
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function update(Request $request)
    {
        try
        {
            if(!Sentinel::hasAnyAccess(['employees.update'])){
                throw new Exception("Permission Denied!");
            }
            $data = $request->all();
            $user = Sentinel::getUser();
            $data['updatedBy'] = $user->getUserId();
            $employeeUser = Sentinel::findById($data['userId']);
            DB::beginTransaction();
            //TODO: update application user associated with this employee (firstName,lastName,email,password)
            $rules = [
                'firstName' => 'required',
                'lastName' => 'required',
            ];
            if (strtolower($employeeUser->email) !== strtolower($data['email']))
            {
                $rules['email'] = 'required|email|unique:users';
            }
            if (!empty($data['password']))
            {
                $rules['password'] = 'min:6';
            }
            $this->validateData($data, $rules);
            //dd($data);
            if ($employeeUser)
            {
                $credentials = [];
                if (!empty($data['password']))
                {
                    $credentials['password'] = $data['password'];
                }
                if ($data['firstName'] != $employeeUser->first_name)
                {
                    $credentials['first_name'] = $data['firstName'];
                }

                if ($data['lastName'] != $employeeUser->last_name)
                {
                    $credentials['last_name'] = $data['lastName'];
                }

                if (strtolower($data['email']) != strtolower($employeeUser->email))
                {
                    $credentials['email'] = strtolower($data['email']);
                    $credentials['username'] = strtolower($data['email']);
                }

                Sentinel::update($employeeUser, $credentials);
            }
            $responseData = $this->put($this->urlEndpoint, $data);
            DB::commit();
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            DB::rollBack();
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function profileData(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/profile", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function show(Request $request)
    {
        try
        {
            if(!Sentinel::hasAnyAccess(['employees.view', 'employees.show'])){
                throw new Exception("Permission Denied!");
            }
            $params = $request->all();

            $responseData = $this->get("{$this->urlEndpoint}/show", $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function activate(Request $request)
    {
        try
        {
            if(!Sentinel::hasAnyAccess(['employees.activate'])){
                throw new Exception("Permission Denied!");
            }
            $data = $request->all();

            $responseData = $this->patch("{$this->urlEndpoint}/activate", $data);

            $user = Sentinel::findById($responseData['userId']);
            if (!$user)
            {
                throw new Exception("Employee activation failed. Contact admin for help!");
            }

            $activation = Activation::exists($user);
            if ($activation)
            {
                $activation->complete($activation->code);
            } else
            {
                $activation = Activation::create($user);
                $activation->complete($activation->code);
            }
            return response()->json("Employee activated!");
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function uploadProfilePicture(Request $request)
    {
        $filePath = null;
        $imageSave = false;
        try
        {
            if (!$request->hasFile('avatar'))
            {
                throw new Exception("No image was received!");
            }

            $employee = $request->except(['avatar']);
            if (empty($employee['userId']))
            {
                throw new Exception("Employee not found!");
            }

            $user = Sentinel::getUserRepository()->findById($employee['userId']);

            $avatar = $request->file('avatar');
            $file = array(
                'avatar' => $avatar
            );
            $rules = array(
                'avatar' => 'required|mimes:jpeg,jpg,bmp,png'
            );
            $validator = Validator::make($file, $rules);
            if ($validator->fails())
            {
                throw new Exception("Allowed file types: jpeg,jpg,bmp,png!");
            }
            $fileName = str_replace('/', '_', $employee['employeeNumber']) . '.png';
            $filePath = 'storage\\images\\profiles\\' . $fileName;
            $image = Image::make($avatar->getRealPath());
            // resize
            if ($image->width() > 215 || $image->height() > 215)
            {
                $image->resize(215, 215);
            }
            DB::beginTransaction();
            $image->save(public_path($filePath));
            $imageSave = true;
            // update employee data
            $employee['avatar'] = $filePath;
            $responseData = $this->patch("{$this->urlEndpoint}/profile/photo", $employee);

            if ($user)
            {
                $user->update([
                    'avatar' => $filePath
                ]);
            }
            DB::commit();
            return response()->json("Profile picture uploaded!");
        } catch (Exception $ex)
        {
            // first delete the file
            if ($imageSave && !empty($filePath) && File::exists($filePath))
            {
                File::delete($filePath);
            }
            // rollback any local DB changes
            DB::rollBack();
            Log::error("PROFILE_PICTURE_UPLOAD: {$ex->getMessage()}");
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function downloadProfile(Request $request)
    {
        try
        {
            $email = $request->get('email');
            $employee = Employee::query()->where('email', $email)->first();
            if (!$employee)
            {
                throw new Exception("Employee not found!");
            }

            $data = [
                'employee' => $employee
            ];
            // $employeeNumber = str_replace('/', '_', $employee->employee_number);
            // $pdf = new Dompdf();
            // $pdf->setBasePath(public_path());
            // $html = View::make('employees.profile-download', $data)->render();
            // $pdf->loadHtml($html);
            // $pdf->setPaper('a4');
            // $options = new Options(['dpi' => 150, 'margin' => 10]);
            // $pdf->setOptions($options);
            // $fileName = "{$employeeNumber}.pdf";
            // return $pdf->stream($fileName);
            return view('employees.ResumeTemplate', $data);
        } catch (Exception $ex)
        {
            Log::error("Profile Download: {$ex->getMessage()}");
            return redirect()->to('/');
        }
    }

}
