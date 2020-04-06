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
Route::get('/', function (){
    return redirect()->route('login');
});
Route::get('/login', 'AccountController@login')->name('login');
Route::post('/login', 'AccountController@processLogin')->name('login');
Route::get('/test', 'HomeController@test')->name('test');

Route::group(['middleware' => ['ensure.authenticated']], function () {
    Route::get('/service', 'AccountController@selectService')->name('service');
    Route::post('/service', 'AccountController@setService')->name('service');

    Route::group(['middleware' => ['ensure.service.selected']], function () {
        Route::get('/service-change', 'AccountController@changeService')->name('service.change');
        Route::get('/user-data', 'AccountController@getUserData')->name('user-data');
        Route::get('/profile', 'AccountController@profile')->name('profile');
        Route::post('/logout', 'AccountController@logout')->name('logout');
        Route::get('/form-selections-options', 'HomeController@getFormSelectionsOptions')->name('form-selections-options');

        Route::group(['prefix' => 'hrms'], function () {
            Route::get('', 'HomeController@indexHrms')->name('hrms.dashboard');
            Route::get('dashboard', 'HomeController@indexHrms')->name('hrms.dashboard');

            // executive-secretary
            Route::group(['prefix' => 'executive-secretary'], function () {
                Route::get('', 'HrmsController@executiveSecretary')->name('hrms.executive-secretary');
            });

            // directorates
            Route::group(['prefix' => 'directorates'], function () {
                Route::get('', 'HrmsController@directorates')->name('hrms.directorates.list');
            });

            // departments
            Route::group(['prefix' => 'departments'], function () {
                Route::get('', 'HrmsController@departments')->name('hrms.departments.list');

            });

            // divisions
            Route::group(['prefix' => 'divisions'], function () {
                Route::get('', 'HrmsController@divisions')->name('hrms.divisions.list');
            });

            // sections
            Route::group(['prefix' => 'sections'], function () {
                Route::get('', 'HrmsController@sections')->name('hrms.sections.list');
            });

            // designations
            Route::group(['prefix' => 'designations'], function () {
                Route::get('', 'HrmsController@designations')->name('hrms.designations.list');
            });

            // salary-scales
            Route::group(['prefix' => 'salary-scales'], function () {
                Route::get('', 'HrmsController@salaryScales')->name('hrms.salary-scales.list');

            });

            // employees
            Route::group(['prefix' => 'employees'], function () {
                // employees
                Route::get('', 'HrmsController@employees')->name('hrms.employees.list');
                Route::get('create', 'HrmsController@createEmployee')->name('hrms.employees.create');

                // employees/education
                //Route::get('education', 'EducationInfoController')->name('hrms.employees.education');
                //Route::post('education', 'EducationInfoController@addEducationInfo')->name('hrms.employees.education.add');
                //Route::put('education', 'EducationInfoController@updateEducationInfo')->name('hrms.employees.education.update');

                // employees/bank
                //Route::get('bank', 'BankInfoController')->name('hrms.employees.bank');
                //Route::post('bank', 'BankInfoController@addBankInfo')->name('hrms.employees.bank.add');
                //Route::put('bank', 'BankInfoController@updateBankInfo')->name('hrms.employees.bank.update');

                // employees/experience
                //Route::get('experience', 'ExperienceInfoController')->name('hrms.employees.experience');
                //Route::post('experience', 'ExperienceInfoController@addExperienceInfo')->name('hrms.employees.experience.add');
                //Route::put('experience', 'ExperienceInfoController@updateExperienceInfo')->name('hrms.employees.experience.update');

                // employees/related-persons
                //Route::get('related-persons', 'RelatedPersonInfoController')->name('hrms.employees.related-persons');
                //Route::post('related-persons', 'RelatedPersonInfoController@addRelatedPersonInfo')->name('hrms.employees.related-persons.add');
                //Route::put('related-persons', 'RelatedPersonInfoController@updateRelatedPersonInfo')->name('hrms.employees.related-persons.update');
            });

            // leaves
            Route::group(['prefix' => 'leaves'], function () {
                // leaves
                Route::get('', 'HrmsController@leaves')->name('hrms.leaves.list');

                // leaves/types
                Route::group(['prefix' => 'types'], function () {
                    Route::get('', 'HrmsController@leaveTypes')->name('hrms.leaves.types');
                    // leave type policies
                    //Route::get('policies', 'LeavePoliciesController@getLeavePolicies')->name('hrms.leaves.types.policies');
                    //Route::post('policies', 'LeavePoliciesController@storeLeavePolicy')->name('hrms.leaves.types.policies');
                    //Route::put('policies', 'LeavePoliciesController@updateLeavePolicy')->name('hrms.leaves.types.policies');
                    //Route::delete('policies', 'LeavePoliciesController@deleteLeavePolicy')->name('hrms.leaves.types.policies');
                    //Route::patch('policies/activate', 'LeavePoliciesController@activateLeavePolicy')->name('hrms.leaves.types.policies.activate');
                    //Route::patch('policies/deactivate', 'LeavePoliciesController@deactivateLeavePolicy')->name('hrms.leaves.types.policies.deactivate');
                });
            });

            // leave-applications
            Route::group(['prefix' => 'leave-applications'], function () {
                Route::get('', 'HrmsController@leaveApplications')->name('hrms.leaves.applications');
                //Route::get('all-json', 'LeaveApplicationsController@getLeaveApplications')->name('hrms.leaves.all-applications');
                //Route::post('', 'StoreLeaveApplicationController')->name('hrms.leaves.applications.create');
                //Route::put('', 'UpdateLeaveApplicationController')->name('hrms.leaves.applications.update');
                //Route::patch('verify', 'LeaveApplicationsController@verify')->name('hrms.leaves.applications.verify');
                //Route::patch('return', 'LeaveApplicationsController@returnApplication')->name('hrms.leaves.applications.return');
                //Route::patch('approve', 'LeaveApplicationsController@approve')->name('hrms.leaves.applications.approve');
                //Route::patch('decline', 'LeaveApplicationsController@decline')->name('hrms.leaves.applications.decline');
                //Route::patch('grant', 'LeaveApplicationsController@grant')->name('hrms.leaves.applications.grant');
                //Route::patch('reject', 'LeaveApplicationsController@reject')->name('hrms.leaves.applications.reject');
                //Route::delete('', 'LeaveApplicationsController@delete')->name('hrms.leaves.applications.delete');

            });
            // DELEGATIONS
            Route::group(['prefix' => 'delegations'], function () {
                Route::get('', 'HrmsController@delegations')->name('hrms.delegations');
                //Route::get('all-json', 'DelegationsController@index')->name('hrms.delegations.all-json');
                //Route::post('', 'DelegationsController@store')->name('hrms.delegations.create');
                //Route::put('', 'DelegationsController@update')->name('hrms.delegations.update');
                //Route::delete('', 'DelegationsController@delete')->name('hrms.delegations.delete');
            });

            // DOCUMENTS
            Route::group(['prefix' => 'documents'], function () {
                Route::get('', 'HrmsController@documents')->name('hrms.documents');
                //Route::get('all-json', 'DocumentsController@index')->name('hrms.documents.all-json');
                //Route::post('', 'DocumentsController@store')->name('hrms.documents.create');
            });
        });

        Route::group(['prefix' => 'spms'], function () {
            Route::get('', 'HomeController@indexSpms')->name('spms.dashboard');
            Route::get('dashboard', 'HomeController@indexSpms')->name('spms.dashboard');

            Route::namespace('Spms')->group(function () {
                Route::group(['prefix' => 'plans'], function () {
                    Route::get('', 'SpmsController@plans')->name('spms.plans.plan');
                    Route::get('plan', 'SpmsController@plan')->name('spms.plans.plan');
                    Route::get('execute', 'SpmsController@execute')->name('spms.plans.execute');
                    Route::get('monitor', 'SpmsController@monitor')->name('spms.plans.monitor');
                    Route::get('all-json', 'StrategicPlansGateway@index');
                    Route::post('', 'StrategicPlansGateway@store');
                    Route::put('', 'StrategicPlansGateway@update');
                });

                Route::group(['prefix' => 'objectives'], function () {
                    Route::get('', 'StrategicObjectivesGateway@index');
                    Route::post('', 'StrategicObjectivesGateway@store');
                    Route::put('', 'StrategicObjectivesGateway@update');
                    Route::get('show/{id}', 'SpmsController@objectiveDetails');
                    Route::get('details', 'StrategicObjectivesGateway@getObjectiveDetails');
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
                    Route::post('', 'InterventionsGateway@store');
                    Route::put('', 'InterventionsGateway@update');
                });

                Route::group(['prefix' => 'outputs'], function () {
                    Route::post('', 'OutputsGateway@store');
                    Route::put('', 'OutputsGateway@update');
                });

                Route::group(['prefix' => 'output-indicators'], function () {
                    Route::post('', 'OutputIndicatorsGateway@store');
                    Route::put('', 'OutputIndicatorsGateway@update');
                });

                Route::group(['prefix' => 'output-indicator-targets'], function () {
                    Route::post('', 'OutputIndicatorTargetsGateway@store');
                    Route::put('', 'OutputIndicatorTargetsGateway@update');
                });


                Route::group(['prefix' => 'key-result-areas'], function () {
                    Route::get('', 'KeyResultAreasGateway@index');
                    Route::post('', 'KeyResultAreasGateway@store');
                    Route::put('', 'KeyResultAreasGateway@update');
                    Route::get('show/{id}', 'SpmsController@keyResultAreaDetails');
                    Route::get('details', 'KeyResultAreasGateway@getKeyResultAreaDetails');
                });
                Route::group(['prefix' => 'outcomes'], function () {
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
            });
        });

        // users
        Route::group(['prefix' => 'users'], function () {
            Route::get('', 'UsersController@index')->name('users.list');
            Route::get('all-json', 'UsersController@getUsers')->name('users.json');
            Route::post('', 'UsersController@store')->name('users.create');
            Route::put('', 'UsersController@update')->name('users.update');
        });

        // roles
        Route::group(['prefix' => 'roles'], function () {
            Route::get('', 'RolesController@index')->name('roles.list');
            Route::get('all-json', 'RolesController@getRoles')->name('roles.all-json');
            Route::post('', 'RolesController@store')->name('roles.create');
            Route::put('', 'RolesController@update')->name('roles.update');
            Route::patch('', 'RolesController@updatePermissions')->name('roles.update_permissions');
            Route::delete('', 'RolesController@delete')->name('roles.delete');
        });

        // settings
        Route::group(['prefix' => 'settings', 'namespace' => 'Settings'], function () {
            // general settings
            Route::get('general', 'GeneralSettingsController@index')->name('settings.general');
            Route::get('general/all-json', 'GeneralSettingsController@getSettings')->name('settings.general.all-json');
            Route::put('general', 'GeneralSettingsController@update')->name('settings.general');
            // leave settings
            Route::get('leave', 'LeaveSettingsController@index')->name('settings.leave');
            Route::post('leave', 'LeaveSettingsController@update')->name('settings.leave');
            // approvals settings
            Route::get('approvals', 'ApprovalsSettingsController@index')->name('settings.approvals');
            Route::post('approvals', 'ApprovalsSettingsController@update')->name('settings.approvals');
        });

    });

});
