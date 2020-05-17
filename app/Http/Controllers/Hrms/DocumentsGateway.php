<?php

namespace App\Http\Controllers\Hrms;

use App\Http\Controllers\GatewayController;
use App\Traits\ValidatesHttpRequests;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentsGateway extends GatewayController
{
    use ValidatesHttpRequests;

    public function __construct()
    {
        $this->urlEndpoint = env('HRMS_URL') . '/v1/documents';
    }

    public function index(Request $request)
    {
        try
        {
            $params = $request->all();

            $responseData = $this->get($this->urlEndpoint, $params);

            return response()->json($responseData);
        } catch (Exception $ex)
        {
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(Request $request)
    {
        $relativePath = null;
        $fileName = null;
        try
        {
            //dd($request->all());
            if (!$request->hasFile('path'))
            {
                throw new Exception("No file attached!");
            }

            $employeeDocument = $request->file('path');
            $rules = [
                'title' => 'required',
            ];
            $this->validateData($request->all(), $rules);

            $ext = $employeeDocument->clientExtension();
            $size = $employeeDocument->getSize();
            $maxSize = 1024 * 1024;
            if ($size > $maxSize)
            {
                throw new Exception("Your file is larger than  1MB.");
            }
            if (!in_array($ext, ['mimes', 'jpeg', 'jpg', 'bmp', 'png', 'pdf']))
            {
                throw new Exception("The allowed file formats are: mimes,jpeg,jpg,bmp,png,pdf.");
            }
            $title = $request->get('title');
            $employeeNumber = $request->get('employeeNumber');
            $employeeId = $request->get('employeeId');

            // upload the document
            if (!empty($employeeNumber))
            {
                $relativePath = "uploads/employee/" . str_replace('/', '_', $employeeNumber);
            } else
            {
                $relativePath = "uploads/non-employee";
            }
            $fileName = Str::slug($title) . ".{$ext}";

            Storage::disk('local')->putFileAs("public/{$relativePath}", $employeeDocument, $fileName);
            $data = [
                'title' => $title,
                'employeeNumber' => $employeeNumber,
                'employeeId' => $employeeId,
                'type' => $ext,
                'size' => $size,
                'description' => $request->get('description'),
                'documentCategoryId' => $request->get('documentCategoryId'),
                'documentTypeId' => $request->get('documentTypeId'),
                'path' => "/storage/{$relativePath}/{$fileName}",
            ];
            $loggedInUser = Sentinel::getUser();
            $data['userId'] = $loggedInUser->getUserId();
            $responseData = $this->post($this->urlEndpoint, $data);
            return response()->json($responseData);
        } catch (Exception $ex)
        {
            if(!empty($relativePath) && !empty($fileName) && Storage::disk('local')->exists("public/{$relativePath}/{$fileName}")){
                // remove the file
                Storage::disk('local')->delete("public/{$relativePath}/{$fileName}");
            }
            return response()->json($ex->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

}
