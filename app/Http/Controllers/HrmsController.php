<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use stdClass;

class HrmsController extends Controller
{

    public function executiveSecretary()
    {
        return view('hrms.executive-secretary.index');
    }

    public function directorates()
    {
        return view('hrms.directorates.index');
    }

    public function departments()
    {
        return view('hrms.departments.index');
    }

    public function divisions()
    {
        return view('hrms.divisions.index');
    }

    public function sections()
    {
        return view('hrms.sections.index');
    }

    public function designations()
    {
        return view('hrms.designations.index');
    }

    public function salaryScales()
    {
        return view('hrms.salary-scales.index');
    }

    public function employees()
    {
        return view('hrms.employees.index');
    }

    public function createEmployee()
    {
        return view('hrms.employees.create');
    }


}
