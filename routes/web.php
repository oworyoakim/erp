<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', 'AccountController@login')->name('login');
Route::post('/login', 'AccountController@processLogin')->name('login');
Route::post('/reset-password', 'AccountController@resetPassword')->name('reset-password');
Route::get('/reset-password/{email}/{code}', 'AccountController@resetPasswordForm')->name('reset-password-form');
Route::put('/reset-password', 'AccountController@processResetPasswordForm')->name('do-reset-password');
Route::get('/test', 'HomeController@test')->name('test');
Route::get('/settings', 'HomeController@settings');

Route::middleware('ensure.authenticated')->group(function () {
    Route::post('/logout', 'AccountController@logout')->name('logout');
    //Route::get('/service', 'AccountController@selectService')->name('service');
    //Route::post('/service', 'AccountController@setService')->name('service');

    //Route::get('/service-change', 'AccountController@changeService')->name('service.change');
    Route::post('/remove-service', 'AccountController@removeService')->name('remove-service');
    Route::post('/go-to-service', 'AccountController@goToService')->name('go-to-service');
    Route::get('/user-data', 'AccountController@getUserData')->name('user-data');
    Route::get('/profile', 'AccountController@profile')->name('profile');

    // HRMS
    Route::prefix('hrms')->namespace('Hrms')->group(function () {
        Route::get('', 'HrmsController@indexHrms')->name('hrms.dashboard');
        Route::get('dashboard', 'HrmsController@indexHrms')->name('hrms.dashboard');
        Route::get('employee-profile-view', 'HrmsController@employeeProfileView')->name('hrms.employee-profile-view');
        Route::get('dashboard-statistics', 'HrmsController@getDashboardStatistics')->name('hrms.dashboard.statistics');
        Route::get('form-selections-options', 'HrmsController@getFormSelectionsOptions')->name('hrms.form-selections-options');

        // executive-director
        Route::group(['prefix' => 'executive-director'], function () {
            Route::get('', 'HrmsController@executiveDirector')->name('hrms.executive-director');
        });

        // directorates
        Route::group(['prefix' => 'directorates'], function () {
            Route::get('', 'HrmsController@directorates')->name('hrms.directorates.list');
            Route::get('view/{id}', 'HrmsController@directorateDetails')->name('hrms.directorates.details');
            Route::get('all-json', 'DirectoratesGateway@index');
            Route::get('details', 'DirectoratesGateway@show');
            Route::post('', 'DirectoratesGateway@store');
            Route::put('', 'DirectoratesGateway@update');
            Route::get('unscoped', 'DirectoratesGateway@indexUnscoped');
        });

        // departments
        Route::group(['prefix' => 'departments'], function () {
            Route::get('', 'HrmsController@departments')->name('hrms.departments.list');
            Route::get('view/{id}', 'HrmsController@departmentDetails')->name('hrms.departments.details');
            Route::get('all-json', 'DepartmentsGateway@index');
            Route::get('details', 'DepartmentsGateway@show');
            Route::post('', 'DepartmentsGateway@store');
            Route::put('', 'DepartmentsGateway@update');
            Route::get('unscoped', 'DepartmentsGateway@indexUnscoped');
        });

        // divisions
        Route::group(['prefix' => 'divisions'], function () {
            Route::get('', 'HrmsController@divisions')->name('hrms.divisions.list');
            Route::get('view/{id}', 'HrmsController@divisionDetails')->name('hrms.divisions.details');
            Route::get('all-json', 'DivisionsGateway@index');
            Route::get('details', 'DivisionsGateway@show');
            Route::post('', 'DivisionsGateway@store');
            Route::put('', 'DivisionsGateway@update');
        });

        // sections
        Route::group(['prefix' => 'sections'], function () {
            Route::get('', 'HrmsController@sections')->name('hrms.sections.list');
            Route::get('all-json', 'SectionsGateway@index');
            Route::post('', 'SectionsGateway@store');
            Route::put('', 'SectionsGateway@update');
        });

        // designations
        Route::group(['prefix' => 'designations'], function () {
            Route::get('', 'HrmsController@designations')->name('hrms.designations.list');
            Route::get('all-json', 'DesignationsGateway@index');
            Route::post('', 'DesignationsGateway@store');
            Route::put('', 'DesignationsGateway@update');
        });

        // salary-scales
        Route::group(['prefix' => 'salary-scales'], function () {
            Route::get('', 'HrmsController@salaryScales')->name('hrms.salary-scales.list');
            Route::get('all-json', 'SalaryScalesGateway@index');
            Route::post('', 'SalaryScalesGateway@store');
            Route::put('', 'SalaryScalesGateway@update');

        });

        // employees
        Route::group(['prefix' => 'employees'], function () {
            // employees
            Route::get('', 'HrmsController@employees')->name('hrms.employees.list');
            Route::get('all-json', 'EmployeesGateway@index');
            Route::post('', 'EmployeesGateway@store');
            Route::put('', 'EmployeesGateway@update');
            Route::get('create', 'HrmsController@createEmployee')->name('hrms.employees.create');
            Route::get('details', 'EmployeesGateway@show');
            Route::get('profile/{username}', 'HrmsController@employeeProfile')->name('hrms.employees.profile');
            Route::get('profile-data', 'EmployeesGateway@profileData');
            Route::patch('activate', 'EmployeesGateway@activate');
            Route::patch('suspend', 'EmployeesGateway@suspend');
            Route::patch('dismiss', 'EmployeesGateway@dismiss');
            Route::patch('release', 'EmployeesGateway@release');
            Route::patch('retire', 'EmployeesGateway@retire');
            Route::get('unscoped', 'EmployeesGateway@indexUnscoped');
            Route::post('upload-profile-picture', 'EmployeesGateway@uploadProfilePicture');
            Route::get('download-profile', 'EmployeesGateway@downloadProfile');

            // employees/education
            Route::get('education', 'EducationInfoGateway@index');
            Route::post('education', 'EducationInfoGateway@store');
            Route::put('education', 'EducationInfoGateway@update');

            // employees/bank
            Route::get('bank', 'BankInfoGateway@index');
            Route::post('bank', 'BankInfoGateway@store');
            Route::put('bank', 'BankInfoGateway@update');

            // employees/experience
            Route::get('experience', 'ExperienceInfoGateway@index');
            Route::post('experience', 'ExperienceInfoGateway@store');
            Route::put('experience', 'ExperienceInfoGateway@update');

            // employees/related-persons
            Route::get('related-persons', 'RelatedPersonInfoGateway@index');
            Route::post('related-persons', 'RelatedPersonInfoGateway@store');
            Route::put('related-persons', 'RelatedPersonInfoGateway@update');
        });

        // contacts
        Route::group(['prefix' => 'contacts'], function () {
            Route::post('', 'ContactsGateway@store');
            Route::put('', 'ContactsGateway@update');
        });

        // settings
        Route::prefix('settings')->group(function (){
            // leave settings
            Route::get('leave', 'HrmsController@leaveSettings')->name('hrms.settings.leave');
            // approvals settings
            Route::get('approvals', 'HrmsController@approvalsSettings')->name('hrms.settings.approvals');
            Route::post('approvals/leave-applications-settings', 'SettingsGateway@updateLeaveApplicationsSettings');
        });

        // leaves
        Route::prefix('leaves')->group( function () {
            // leaves
            Route::get('', 'HrmsController@leaves')->name('hrms.leaves.list');
            Route::get('all-json', 'LeavesGateway@index');
            Route::get('history', 'LeavesGateway@history');

            // leaves/types
            Route::prefix('types')->group( function () {
                Route::get('', 'LeaveTypesGateway@index');
                Route::post('', 'LeaveTypesGateway@store');
                Route::put('', 'LeaveTypesGateway@update');
                Route::patch('activate', 'LeaveTypesGateway@activate');
                Route::patch('deactivate', 'LeaveTypesGateway@deactivate');
            });

            // leaves/policies
            Route::prefix('policies')->group( function () {
                Route::get('', 'LeavePoliciesGateway@index');
                Route::post('', 'LeavePoliciesGateway@store');
                Route::put('', 'LeavePoliciesGateway@update');
                Route::delete('', 'LeavePoliciesGateway@delete');
                Route::patch('activate', 'LeavePoliciesGateway@activate');
                Route::patch('deactivate', 'LeavePoliciesGateway@deactivate');
            });

        });

        // leave-applications
        Route::group(['prefix' => 'leave-applications'], function () {
            Route::get('', 'HrmsController@leaveApplications')->name('hrms.leaves.applications');
            Route::get('all-json', 'LeaveApplicationsGateway@index');
            Route::post('', 'LeaveApplicationsGateway@store');
            Route::put('', 'LeaveApplicationsGateway@update');
            Route::patch('verify', 'LeaveApplicationsGateway@verify');
            Route::patch('return', 'LeaveApplicationsGateway@returnApplication');
            Route::patch('approve', 'LeaveApplicationsGateway@approve');
            Route::patch('decline', 'LeaveApplicationsGateway@decline');
            Route::patch('grant', 'LeaveApplicationsGateway@grant');
            Route::patch('reject', 'LeaveApplicationsGateway@reject');
            //Route::delete('', 'LeaveApplicationsGateway@delete');

        });

        // delegations
        Route::group(['prefix' => 'delegations'], function () {
            Route::get('', 'HrmsController@delegations')->name('hrms.delegations');
            Route::get('all-json', 'DelegationsGateway@index');
            Route::post('', 'DelegationsGateway@store');
            Route::put('', 'DelegationsGateway@update');
            //Route::delete('', 'DelegationsGateway@delete');
        });

        // documents
        Route::group(['prefix' => 'documents'], function () {
            Route::get('', 'HrmsController@documents')->name('hrms.documents');
            Route::get('all-json', 'DocumentsGateway@index');
            Route::post('', 'DocumentsGateway@store');
        });

    });

    // SPMS
    Route::prefix('spms')->namespace('Spms')->group(function () {

        Route::get('', 'SpmsController@indexSpms')->name('spms.dashboard');
        Route::get('dashboard', 'SpmsController@indexSpms')->name('spms.dashboard');

        Route::group(['prefix' => 'plans'], function () {
            Route::get('', 'SpmsController@plans')->name('spms.plans.plan');
            Route::get('all-json', 'StrategicPlansGateway@index');
            Route::get('plan', 'SpmsController@plan')->name('spms.plans.plan');
            Route::post('', 'StrategicPlansGateway@store');
            Route::put('', 'StrategicPlansGateway@update');

            // Execution
            Route::prefix('execute')->group(function(){
                Route::get('work-plan', 'SpmsController@workPlan')->name('spms.plans.execute.work-plan');
                Route::get('performance-capture', 'SpmsController@performanceCapture')->name('spms.plans.execute.performance-capture');
                Route::get('resolutions-and-directives', 'SpmsController@resolutionsAndDirectives')->name('spms.plans.execute.resolutions-and-directives');
            });

            // Monitoring
            Route::prefix('monitor')->group(function (){
                Route::prefix('strategy')->group(function (){
                    Route::get('summary', 'SpmsController@monitorStrategySummary')->name('spms.plans.monitor.summary_strategy');
                    Route::get('detailed', 'SpmsController@monitorStrategyDetailed')->name('spms.plans.monitor.detailed_strategy');
                    Route::get('summary-report', 'SpmsReportsGateway@summaryStrategyReport');
                    Route::get('detailed-report', 'SpmsReportsGateway@detailedStrategyReport');
                });

                Route::prefix('activity')->group(function () {
                    Route::get('', 'SpmsController@monitorActivity')->name('spms.plans.monitor.activity');
                    Route::get('report', 'SpmsReportsGateway@activityReport');
                });

                Route::prefix('directives-and-resolutions')->group(function () {
                    Route::get('', 'SpmsController@monitorDirectivesAndResolutions')->name('spms.plans.monitor.directives-and-resolutions');
                    Route::get('report', 'SpmsReportsGateway@directivesAndResolutionsReport');
                });
            });

        });

        Route::prefix("directives-and-resolutions")->group(function (){
            Route::get('', 'DirectivesAndResolutionsGateway@index');
            Route::post('', 'DirectivesAndResolutionsGateway@store');
            Route::put('', 'DirectivesAndResolutionsGateway@update');
            Route::get('selection-options', 'DirectivesAndResolutionsGateway@getSelectionOptions');

            // activities
            Route::prefix("activities")->group(function (){
                Route::get('', 'DirAndResActivitiesGateway@index');
                Route::post('', 'DirAndResActivitiesGateway@store');
                Route::put('', 'DirAndResActivitiesGateway@update');
                Route::patch('', 'DirAndResActivitiesGateway@updateStatus');
                Route::patch('complete', 'DirAndResActivitiesGateway@complete');
            });

            // activities
            Route::prefix("outputs")->group(function (){
                Route::get('', 'DirAndResActivityOutputsGateway@index');
                Route::post('', 'DirAndResActivityOutputsGateway@store');
                Route::put('', 'DirAndResActivityOutputsGateway@update');
            });
        });

        Route::group(['prefix' => 'objectives'], function () {
            Route::get('', 'StrategicObjectivesGateway@index');
            Route::post('', 'StrategicObjectivesGateway@store');
            Route::put('', 'StrategicObjectivesGateway@update');
            Route::get('show/{id}', 'SpmsController@objectiveDetails');
            Route::get('details', 'StrategicObjectivesGateway@getObjectiveDetails');
            Route::get('achievements', 'StrategicObjectivesGateway@getOutputAchievements');
            Route::post('achievements', 'StrategicObjectivesGateway@storeOutputAchievements');
        });

        Route::group(['prefix' => 'swots'], function () {
            Route::get('', 'SwotsGateway@index');
            Route::post('', 'SwotsGateway@store');
            Route::put('', 'SwotsGateway@update');
        });

        Route::group(['prefix' => 'swot-categories'], function () {
            Route::get('', 'SwotCategoriesGateway@index');
            Route::post('', 'SwotCategoriesGateway@store');
            Route::put('', 'SwotCategoriesGateway@update');
        });

        Route::group(['prefix' => 'interventions'], function () {
            Route::get('', 'InterventionsGateway@index');
            Route::post('', 'InterventionsGateway@store');
            Route::put('', 'InterventionsGateway@update');
        });

        Route::group(['prefix' => 'outputs'], function () {
            Route::get('', 'OutputsGateway@index');
            Route::post('', 'OutputsGateway@store');
            Route::put('', 'OutputsGateway@update');
        });

        Route::group(['prefix' => 'output-indicators'], function () {
            Route::get('', 'OutputIndicatorsGateway@index');
            Route::post('', 'OutputIndicatorsGateway@store');
            Route::put('', 'OutputIndicatorsGateway@update');
        });

        Route::group(['prefix' => 'output-indicator-targets'], function () {
            Route::get('', 'OutputIndicatorTargetsGateway@index');
            Route::post('', 'OutputIndicatorTargetsGateway@store');
            Route::put('', 'OutputIndicatorTargetsGateway@update');
        });


        Route::group(['prefix' => 'key-result-areas'], function () {
            Route::get('', 'KeyResultAreasGateway@index');
            Route::post('', 'KeyResultAreasGateway@store');
            Route::put('', 'KeyResultAreasGateway@update');
            Route::get('show/{id}', 'SpmsController@keyResultAreaDetails');
            Route::get('details', 'KeyResultAreasGateway@getKeyResultAreaDetails');
            Route::get('achievements', 'KeyResultAreasGateway@getOutcomeAchievements');
            Route::post('achievements', 'KeyResultAreasGateway@storeOutcomeAchievements');
        });

        Route::group(['prefix' => 'outcomes'], function () {
            Route::get('', 'OutcomesGateway@index');
            Route::post('', 'OutcomesGateway@store');
            Route::put('', 'OutcomesGateway@update');
        });

        Route::group(['prefix' => 'outcome-indicators'], function () {
            Route::post('', 'OutcomeIndicatorsGateway@store');
            Route::put('', 'OutcomeIndicatorsGateway@update');
        });

        Route::group(['prefix' => 'outcome-indicator-targets'], function () {
            Route::post('', 'OutcomeIndicatorTargetsGateway@store');
            Route::put('', 'OutcomeIndicatorTargetsGateway@update');
        });

        Route::group(['prefix' => 'work-plans'], function () {
            Route::get('', 'WorkPlansGateway@index');
            Route::post('', 'WorkPlansGateway@store');
            Route::put('', 'WorkPlansGateway@update');
        });

        Route::group(['prefix' => 'main-activities'], function () {
            Route::get('', 'MainActivitiesGateway@index');
            Route::post('', 'MainActivitiesGateway@store');
            Route::put('', 'MainActivitiesGateway@update');
        });

        Route::group(['prefix' => 'activities'], function () {
            Route::get('', 'ActivitiesGateway@index');
            Route::post('', 'ActivitiesGateway@store');
            Route::put('', 'ActivitiesGateway@update');
            Route::get('performance', 'ActivitiesGateway@performance');
            Route::post('performance', 'ActivitiesGateway@updatePerformance');
        });

        Route::group(['prefix' => 'stages'], function () {
            Route::get('', 'StagesGateway@index');
            Route::post('', 'StagesGateway@store');
            Route::put('', 'StagesGateway@update');
        });

        Route::group(['prefix' => 'tasks'], function () {
            Route::get('', 'TasksGateway@index');
            Route::post('', 'TasksGateway@store');
            Route::put('', 'TasksGateway@update');
        });

    });

    // ACL
    Route::prefix('acl')->group(function (){
        Route::get('', 'UsersController@index')->name('acl.dashboard');
        Route::get('form-selections-options', 'HomeController@getFormSelectionsOptions')->name('acl.form-selections-options');
        // users
        Route::prefix('users')->group(function () {
            Route::get('', 'UsersController@index')->name('users.list');
            Route::get('all-json', 'UsersController@getUsers')->name('users.json');
            Route::post('', 'UsersController@store')->name('users.create');
            Route::put('', 'UsersController@update')->name('users.update');
            Route::patch('activate', 'UsersController@activate');
            Route::patch('deactivate', 'UsersController@deactivate');
        });

        // roles
        Route::prefix('roles')->group(function () {
            Route::get('', 'RolesController@index')->name('roles.list');
            Route::get('all-json', 'RolesController@getRoles')->name('roles.all-json');
            Route::post('', 'RolesController@store')->name('roles.create');
            Route::put('', 'RolesController@update')->name('roles.update');
            Route::patch('', 'RolesController@updatePermissions')->name('roles.update_permissions');
            Route::delete('', 'RolesController@delete')->name('roles.delete');
        });

        // settings
        Route::prefix('settings')->group(function () {
            // general settings
            Route::get('general', 'GeneralSettingsController@index')->name('settings.general');
            Route::get('general/all-json', 'GeneralSettingsController@getSettings')->name('settings.general.all-json');
            Route::put('general', 'GeneralSettingsController@update')->name('settings.general');
            Route::get('modules', 'GeneralSettingsController@modules')->name('settings.modules');
            Route::get('modules/list', 'ModulesController@index');
            Route::post('modules', 'ModulesController@store');
            Route::patch('modules', 'ModulesController@updateModulesAccess');
        });
    });

    Route::middleware('ensure.service.selected')->group(function () {

    });

});
