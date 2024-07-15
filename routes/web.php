<?php

use App\Http\Controllers\Admin\BusRouteController;
use App\Http\Controllers\Admin\HostelAttendanceController;
use App\Http\Controllers\Admin\HostelBlockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Admin\GoogleDriveController;

// Route::get('/auth', [GoogleDriveController::class, 'authenticate']);
// Route::get('/auth/callback', [GoogleDriveController::class, 'handleCallback']);
// Route::view('/', 'welcome');
Auth::routes(['register' => false]);

// dd('test');

Route::get('/', function () {

    $routes = Route::getRoutes();
    return redirect('/login');
});

Route::get('/test', function () {
    dd('test');
    return view('test');
});

Route::get('/student-login-from-api', function (Request $request) {
    return view('auth.studentLoginFromApi', compact('request'));
})->name('student-login-from-api');

Route::get('/staff-login-from-api', function (Request $request) {
    return view('auth.staffLoginFromApi', compact('request'));
})->name('staff-login-from-api');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Auth'], function () {
    Route::post('logout-rit', 'LogoutController@logoutRIT')->name('logout-rit');
    Route::get('student-logout', 'LogoutController@logoutRIT')->name('student-logout');
});
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin', 'active_user']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    // Php Artisan Cmds
    Route::get('schedule-clear-cache', 'ArtisanCmdController@ScheduleClearCache');

    Route::delete('sellvehicle/destroy', 'LatestEventController@massDestroy')->name('sellvehicle.massDestroy');
    Route::resource('sellvehicle', 'LatestEventController');
    Route::get('sellvehicle/purchase/{id}', 'LatestEventController@purchase')->name('sellvehicle.purchase');
    Route::post('sellvehicle/purchaseget/{id}', 'LatestEventController@purchaseupdate')->name('sellvehicle.purchaseget');
    Route::resource('sellvehicle', 'LatestEventController');
    Route::post('sellvehicle/{id?}','LatestEventController@StatusUpdate')->name('sellvehicle.StatusUpdate');
    Route::get('model_get','LatestEventController@model_get')->name('model_get');

    Route::get('brand', 'BrandController@index')->name('brand.index');
    Route::post('brand/view', 'BrandController@view')->name('brand.view');
    Route::post('brand/edit', 'BrandController@edit')->name('brand.edit');
    Route::post('brand/store', 'BrandController@store')->name('brand.store');
    Route::post('brand/delete', 'BrandController@destroy')->name('brand.delete');
    Route::delete('brand/destroy', 'BrandController@massDestroy')->name('brand.massDestroy');

    Route::get('buyer_enquire', 'BuyerEnquireController@index')->name('buyer_enquire.index');
    Route::post('buyer_enquire/view', 'BuyerEnquireController@view')->name('buyer_enquire.view');
    Route::post('buyer_enquire/edit', 'BuyerEnquireController@edit')->name('buyer_enquire.edit');
    Route::post('buyer_enquire/store', 'BuyerEnquireController@store')->name('buyer_enquire.store');
    Route::post('buyer_enquire/delete', 'BuyerEnquireController@destroy')->name('buyer_enquire.delete');
    Route::delete('buyer_enquire/destroy', 'BuyerEnquireController@massDestroy')->name('buyer_enquire.massDestroy');

    // Route::post('get-clients', [BookingController::class, 'GetClients'])->name('GetClients');
    Route::post('get-clients', 'ApplicationController@GetClients')->name('GetClients');
    Route::post('get-client_info', 'ApplicationController@GetClientInfo')->name('GetClientInfo');
    Route::post('get-customer_store', 'ApplicationController@customerStore')->name('customerStore');


    Route::get('seller_enquire', 'SellerEnquireController@index')->name('seller_enquire.index');
    Route::post('seller_enquire/view', 'SellerEnquireController@view')->name('seller_enquire.view');
    Route::post('seller_enquire/edit', 'SellerEnquireController@edit')->name('seller_enquire.edit');
    Route::post('seller_enquire/store', 'SellerEnquireController@store')->name('seller_enquire.store');
    Route::post('seller_enquire/delete', 'SellerEnquireController@destroy')->name('seller_enquire.delete');
    Route::delete('seller_enquire/destroy', 'SellerEnquireController@massDestroy')->name('seller_enquire.massDestroy');

    Route::get('customerusers', 'CustomerController@index')->name('customerusers.index');
    Route::post('customerusers/view', 'CustomerController@view')->name('customerusers.view');
    Route::post('customerusers/edit', 'CustomerController@edit')->name('customerusers.edit');
    Route::post('customerusers/store', 'CustomerController@store')->name('customerusers.store');
    Route::post('customerusers/delete', 'CustomerController@destroy')->name('customerusers.delete');
    Route::delete('customerusers/destroy', 'CustomerController@massDestroy')->name('customerusers.massDestroy');

    Route::get('loanType', 'LoanTypeController@index')->name('loanType.index');
    Route::post('loanType/view', 'LoanTypeController@view')->name('loanType.view');
    Route::post('loanType/edit', 'LoanTypeController@edit')->name('loanType.edit');
    Route::post('loanType/store', 'LoanTypeController@store')->name('loanType.store');
    Route::post('loanType/delete', 'LoanTypeController@destroy')->name('loanType.delete');
    Route::delete('loanType/destroy', 'LoanTypeController@massDestroy')->name('loanType.massDestroy');

    Route::get('loanType', 'LoanTypeController@index')->name('loanType.index');
    Route::post('loanType/view', 'LoanTypeController@view')->name('loanType.view');
    Route::post('loanType/edit', 'LoanTypeController@edit')->name('loanType.edit');
    Route::post('loanType/store', 'LoanTypeController@store')->name('loanType.store');
    Route::post('loanType/delete', 'LoanTypeController@destroy')->name('loanType.delete');
    Route::delete('loanType/destroy', 'LoanTypeController@massDestroy')->name('loanType.massDestroy');

    Route::get('document_type', 'DocumenTypeController@index')->name('document_type.index');
    Route::post('document_type/view', 'DocumenTypeController@view')->name('document_type.view');
    Route::post('document_type/edit', 'DocumenTypeController@edit')->name('document_type.edit');
    Route::post('document_type/store', 'DocumenTypeController@store')->name('document_type.store');
    Route::post('document_type/delete', 'DocumenTypeController@destroy')->name('document_type.delete');
    Route::delete('document_type/destroy', 'DocumenTypeController@massDestroy')->name('document_type.massDestroy');



    Route::get('application', 'ApplicationController@index')->name('application.index');
    Route::post('application/view', 'ApplicationController@view')->name('application.view');
    Route::post('application/edit', 'ApplicationController@edit')->name('application.edit');
    Route::post('application/store', 'ApplicationController@store')->name('application.store');
    Route::post('application/delete', 'ApplicationController@destroy')->name('application.delete');
    Route::delete('application/destroy', 'ApplicationController@massDestroy')->name('application.massDestroy');


    Route::get('application-stage', 'ApplicationStageController@index')->name('application-stage.index');
    Route::post('application-stage/view', 'ApplicationStageController@view')->name('application-stage.view');
    Route::get('application-stage/edit/{id}', 'ApplicationStageController@edit')->name('application-stage.edit');
    // Route::post('application-stage/edit', 'ApplicationStageController@edit')->name('application-stage.edit');
    Route::post('application-stage/store', 'ApplicationStageController@store')->name('application-stage.store');
    Route::post('application-stage/delete', 'ApplicationStageController@destroy')->name('application-stage.delete');
    Route::delete('application-stage/destroy', 'ApplicationStageController@massDestroy')->name('application-stage.massDestroy');





    Route::get('vehicleFeature', 'vehicleFeatureController@index')->name('vehicleFeature.index');
    Route::post('vehicleFeature/view', 'vehicleFeatureController@view')->name('vehicleFeature.view');
    Route::post('vehicleFeature/edit', 'vehicleFeatureController@edit')->name('vehicleFeature.edit');
    Route::post('vehicleFeature/store', 'vehicleFeatureController@store')->name('vehicleFeature.store');
    Route::post('vehicleFeature/delete', 'vehicleFeatureController@destroy')->name('vehicleFeature.delete');
    Route::delete('vehicleFeature/destroy', 'vehicleFeatureController@massDestroy')->name('vehicleFeature.massDestroy');

    Route::get('customerSellVehicle', 'customerSellVehicleController@index')->name('customerSellVehicle.index');
    Route::post('customerSellVehicle/view', 'customerSellVehicleController@view')->name('customerSellVehicle.view');
    Route::post('customerSellVehicle/edit', 'customerSellVehicleController@edit')->name('customerSellVehicle.edit');
    Route::post('customerSellVehicle/store', 'customerSellVehicleController@store')->name('customerSellVehicle.store');
    Route::post('customerSellVehicle/delete', 'customerSellVehicleController@destroy')->name('customerSellVehicle.delete');
    Route::delete('customerSellVehicle/destroy', 'customerSellVehicleController@massDestroy')->name('customerSellVehicle.massDestroy');




    Route::get('model', 'ModelController@index')->name('model.index');
    Route::post('model/view', 'ModelController@view')->name('model.view');
    Route::post('model/edit', 'ModelController@edit')->name('model.edit');
    Route::post('model/store', 'ModelController@store')->name('model.store');
    Route::post('model/delete', 'ModelController@destroy')->name('model.delete');
    Route::delete('model/destroy', 'ModelController@massDestroy')->name('model.massDestroy');


    Route::post('/image-upload','LatestEventController@ImageUpload')->name('image-upload.upload-image');
    Route::post('/get-images','LatestEventController@GetImage')->name('get-images.upload-image');
    Route::post('/delete-image','LatestEventController@DeleteImage')->name('delete-image.upload-image');

    Route::get('view-cache', 'ArtisanCmdController@ViewCache');
    Route::get('view-clear', 'ArtisanCmdController@ViewClear');

    Route::get('route-cache', 'ArtisanCmdController@RouteCache');
    Route::get('route-clear', 'ArtisanCmdController@RouteClear');

    Route::get('cache-clear', 'ArtisanCmdController@CacheClear');
    Route::get('cache-forget/{key}', 'ArtisanCmdController@CacheForget');

    Route::get('config-cache', 'ArtisanCmdController@ConfigCache');
    Route::get('config-clear', 'ArtisanCmdController@ConfigClear');
    Route::get('storage-link', 'ArtisanCmdController@StorageLink');
    Route::get('overdue-update', 'ArtisanCmdController@overdue');

    // Staff Biometric modificationRun
    Route::get('biometric-modification', 'StaffBiometricController@modificationRun');

    Route::get('circle', 'ArtisanCmdController@circle');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/parse-csv-import', 'UsersController@parseCsvImport')->name('users.parseCsvImport');
    Route::post('users/process-csv-import', 'UsersController@processCsvImport')->name('users.processCsvImport');
    Route::get('users/block', 'UsersController@block')->name('users.block');
    Route::get('users/fetch_users', 'UsersController@fetchUsers')->name('users.fetch_users');
    Route::get('users/fetch_roles', 'UsersController@fetchRoles')->name('users.fetch_roles');
    Route::get('users/unblock', 'UsersController@unblock')->name('users.unblock');
    Route::get('users/block_list', 'UsersController@block_list')->name('users.block_list');
    Route::post('users/block_user', 'UsersController@block_user')->name('users.block_user');
    Route::match (['get', 'post'], 'users/unblock_user', 'UsersController@unblock_user')->name('users.unblock_user');
    Route::post('users/fetch_role', 'UsersController@fetch_role')->name('users.fetch_role');
    Route::resource('users', 'UsersController');

    // Master Tool
    Route::get('master-tools', function () {
        return view('layouts.admin');
    })->name('master-tools');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController');

    // Tools Degree Type
    // Route::delete('tools-grade-type/destroy', 'ToolsDegreeTypeController@massDestroy')->name('tools-grade-type.massDestroy');
    // Route::resource('tools-grade-type', 'ToolsDegreeTypeController');

    Route::get('tools-grade-type', 'ToolsDegreeTypeController@index')->name('tools-grade-type.index');
    Route::post('tools-grade-type/view', 'ToolsDegreeTypeController@view')->name('tools-grade-type.view');
    Route::post('tools-grade-type/edit', 'ToolsDegreeTypeController@edit')->name('tools-grade-type.edit');
    Route::post('tools-grade-type/store', 'ToolsDegreeTypeController@store')->name('tools-grade-type.store');
    Route::post('tools-grade-type/delete', 'ToolsDegreeTypeController@destroy')->name('tools-grade-type.delete');
    Route::delete('tools-grade-type/destroy', 'ToolsDegreeTypeController@massDestroy')->name('tools-grade-type.massDestroy');

    // Year
    Route::get('year', 'YearController@index')->name('year.index');
    Route::post('year/view', 'YearController@view')->name('year.view');
    Route::post('year/edit', 'YearController@edit')->name('year.edit');
    Route::post('year/store', 'YearController@store')->name('year.store');
    Route::post('year/delete', 'YearController@destroy')->name('year.delete');
    Route::delete('year/destroy', 'YearController@massDestroy')->name('year.massDestroy');

    // Academic Year
    Route::get('academic-years', 'AcademicYearController@index')->name('academic-years.index');
    Route::post('academic-years/view', 'AcademicYearController@view')->name('academic-years.view');
    Route::post('academic-years/edit', 'AcademicYearController@edit')->name('academic-years.edit');
    Route::post('academic-years/store', 'AcademicYearController@store')->name('academic-years.store');
    Route::post('academic-years/delete', 'AcademicYearController@destroy')->name('academic-years.delete');
    Route::post('academic-years/check', 'AcademicYearController@check')->name('academic-years.check');
    Route::post('academic-years/change-status', 'AcademicYearController@changeStatus')->name('academic-years.change-status');
    Route::delete('academic-years/destroy', 'AcademicYearController@massDestroy')->name('academic-years.massDestroy');

    // Settings
    // Route::get('admin/master_settings','HomeController@master_settings')->name('admin.master_settings');

    // Batch
    Route::get('batches', 'BatchController@index')->name('batches.index');
    Route::post('batches/view', 'BatchController@view')->name('batches.view');
    Route::post('batches/edit', 'BatchController@edit')->name('batches.edit');
    Route::post('batches/store', 'BatchController@store')->name('batches.store');
    Route::post('batches/delete', 'BatchController@destroy')->name('batches.delete');
    Route::delete('batches/destroy', 'BatchController@massDestroy')->name('batches.massDestroy');

    // Tools Mainscreen
    // Route::delete('tools/destroy', 'ToolsController@massDestroy')->name('tools-grade.massDestroy');
    Route::resource('tools', 'ToolsController');

    // Tools Course
    Route::get('tools-grade', 'ToolsCourseController@index')->name('tools-grade.index');
    Route::post('tools-grade/view', 'ToolsCourseController@view')->name('tools-grade.view');
    Route::post('tools-grade/edit', 'ToolsCourseController@edit')->name('tools-grade.edit');
    Route::post('tools-grade/store', 'ToolsCourseController@store')->name('tools-grade.store');
    Route::post('tools-grade/delete', 'ToolsCourseController@destroy')->name('tools-grade.delete');
    Route::delete('tools-grade/destroy', 'ToolsCourseController@massDestroy')->name('tools-grade.massDestroy');

    // Tools Department
    // Route::delete('blocks/destroy', 'ToolsDepartmentController@massDestroy')->name('blocks.massDestroy');
    // Route::resource('blocks', 'ToolsDepartmentController');

    Route::get('blocks', 'ToolsDepartmentController@index')->name('blocks.index');
    Route::post('blocks/view', 'ToolsDepartmentController@view')->name('blocks.view');
    Route::post('blocks/edit', 'ToolsDepartmentController@edit')->name('blocks.edit');
    Route::post('blocks/store', 'ToolsDepartmentController@store')->name('blocks.store');
    Route::post('blocks/delete', 'ToolsDepartmentController@destroy')->name('blocks.delete');
    Route::delete('blocks/destroy', 'ToolsDepartmentController@massDestroy')->name('blocks.massDestroy');


    Route::get('tools-grade-group', 'GradeGroupController@index')->name('grade-group.index');
    Route::post('tools-grade-group/view', 'GradeGroupController@view')->name('grade-group.view');
    Route::post('tools-grade-group/edit', 'GradeGroupController@edit')->name('grade-group.edit');
    Route::post('tools-grade-group/store', 'GradeGroupController@store')->name('grade-group.store');
    Route::post('tools-grade-group/delete', 'GradeGroupController@destroy')->name('grade-group.delete');
    Route::delete('tools-grade-group/destroy', 'GradeGroupController@massDestroy')->name('grade-group.massDestroy');

    // Section
    Route::get('sections', 'SectionController@index')->name('sections.index');
    Route::post('sections/view', 'SectionController@view')->name('sections.view');
    Route::post('sections/edit', 'SectionController@edit')->name('sections.edit');
    Route::post('sections/store', 'SectionController@store')->name('sections.store');
    Route::post('sections/delete', 'SectionController@destroy')->name('sections.delete');
    Route::delete('sections/destroy', 'SectionController@massDestroy')->name('sections.massDestroy');
    // Route::post('sections/getGrade', 'SectionController@getGrade')->name('sections.getGrade');

    // Semester
    Route::get('semesters', 'SemesterController@index')->name('semesters.index');
    Route::post('semesters/view', 'SemesterController@view')->name('semesters.view');
    Route::post('semesters/edit', 'SemesterController@edit')->name('semesters.edit');
    Route::post('semesters/store', 'SemesterController@store')->name('semesters.store');
    Route::post('semesters/delete', 'SemesterController@destroy')->name('semesters.delete');
    Route::post('semesters/change-status', 'SemesterController@changeStatus')->name('semesters.change-status');
    Route::delete('semesters/destroy', 'SemesterController@massDestroy')->name('semesters.massDestroy');

    // Course Enroll Master
    Route::post('course_enroll_masters/enroll_index', 'CourseEnrollMasterController@enroll_index')->name('course_enroll_masters.enroll_index');
    Route::get('grade-enroll-masters', 'CourseEnrollMasterController@index')->name('grade-enroll-masters.index');
    Route::post('grade-enroll-masters/view', 'CourseEnrollMasterController@view')->name('grade-enroll-masters.view');
    Route::post('grade-enroll-masters/edit', 'CourseEnrollMasterController@edit')->name('grade-enroll-masters.edit');
    Route::post('grade-enroll-masters/store', 'CourseEnrollMasterController@store')->name('grade-enroll-masters.store');
    Route::post('grade-enroll-masters/delete', 'CourseEnrollMasterController@destroy')->name('grade-enroll-masters.delete');
    Route::delete('grade-enroll-masters/destroy', 'CourseEnrollMasterController@massDestroy')->name('grade-enroll-masters.massDestroy');

    // Toolssyllabus Year
    Route::get('toolssyllabus-years', 'ToolssyllabusYearController@index')->name('toolssyllabus-years.index');
    Route::post('toolssyllabus-years/view', 'ToolssyllabusYearController@view')->name('toolssyllabus-years.view');
    Route::post('toolssyllabus-years/edit', 'ToolssyllabusYearController@edit')->name('toolssyllabus-years.edit');
    Route::post('toolssyllabus-years/store', 'ToolssyllabusYearController@store')->name('toolssyllabus-years.store');
    Route::post('toolssyllabus-years/delete', 'ToolssyllabusYearController@destroy')->name('toolssyllabus-years.delete');
    Route::delete('toolssyllabus-years/destroy', 'ToolssyllabusYearController@massDestroy')->name('toolssyllabus-years.massDestroy');

    // Menu geter
    Route::post('menu/geter', 'MenuController@geter')->name('menu.geter');




    // Personal Details
    Route::delete('personal-details/destroy', 'PersonalDetailsController@massDestroy')->name('personal-details.massDestroy');
    Route::post('personal-details/parse-csv-import', 'PersonalDetailsController@parseCsvImport')->name('personal-details.parseCsvImport');
    Route::post('personal-details/process-csv-import', 'PersonalDetailsController@processCsvImport')->name('personal-details.processCsvImport');
    Route::resource('personal-details', 'PersonalDetailsController');
    Route::get('personal-details/stu_index/{user_name_id}/{name}', 'PersonalDetailsController@stu_index')->name('personal-details.stu_index');
    Route::get('personal-details/staff_index/{user_name_id}/{name}', 'PersonalDetailsController@staff_index')->name('personal-details.staff_index');
    Route::post('personal-details/stu_update', 'PersonalDetailsController@stu_update')->name('personal-details.stu_update');
    Route::post('personal-details/staff_update', 'PersonalDetailsController@staff_update')->name('personal-details.staff_update');

    // Ph.D Details
    Route::delete('phd-details/{id}', 'PhdDetailController@destroy')->name('phd-details.destroy');
    Route::get('phd-details/staff_index/{user_name_id}/{name}', 'PhdDetailController@staff_index')->name('phd-details.staff_index');
    Route::post('phd-details/staff_update', 'PhdDetailController@staff_update')->name('phd-details.staff_update');
    Route::post('phd-details/staff_updater', 'PhdDetailController@staff_index')->name('phd-details.staff_updater');

    // Employement Details
    Route::get('employment-details/staff_index/{user_name_id}/{name}', 'EmploymentDetailsController@staff_index')->name('employment-details.staff_index');
    Route::post('employment-details/staff_update', 'EmploymentDetailsController@staff_update')->name('employment-details.staff_update');
    // Route::post('personal-details/index', 'PersonalDetailsController@index')->name('personal-details.index');

    // Educational Details
    Route::delete('educational-details/destroy', 'EducationalDetailsController@massDestroy')->name('educational-details.massDestroy');
    Route::post('educational-details/parse-csv-import', 'EducationalDetailsController@parseCsvImport')->name('educational-details.parseCsvImport');
    Route::post('educational-details/process-csv-import', 'EducationalDetailsController@processCsvImport')->name('educational-details.processCsvImport');
    Route::resource('educational-details', 'EducationalDetailsController');
    Route::get('educational-details/stu_index/{user_name_id}/{name}', 'EducationalDetailsController@stu_index')->name('educational-details.stu_index');
    Route::get('educational-details/staff_index/{user_name_id}/{name}', 'EducationalDetailsController@staff_index')->name('educational-details.staff_index');
    Route::post('educational-details/stu_update', 'EducationalDetailsController@stu_update')->name('educational-details.stu_update');
    Route::post('educational-details/staff_updater', 'EducationalDetailsController@staff_index')->name('educational-details.staff_updater');
    Route::post('educational-details/stu_updater', 'EducationalDetailsController@stu_index')->name('educational-details.stu_updater');
    Route::post('educational-details/staff_update', 'EducationalDetailsController@staff_update')->name('educational-details.staff_update');

    // Nationality
    Route::get('nationalities', 'NationalityController@index')->name('nationalities.index');
    Route::post('nationalities/view', 'NationalityController@view')->name('nationalities.view');
    Route::post('nationalities/edit', 'NationalityController@edit')->name('nationalities.edit');
    Route::post('nationalities/store', 'NationalityController@store')->name('nationalities.store');
    Route::post('nationalities/delete', 'NationalityController@destroy')->name('nationalities.delete');
    Route::post('nationalities/check', 'NationalityController@check')->name('nationalities.check');
    Route::post('nationalities/change-status', 'NationalityController@changeStatus')->name('nationalities.change-status');
    Route::delete('nationalities/destroy', 'NationalityController@massDestroy')->name('nationalities.massDestroy');

    //Payment Mode
    Route::get('paymentMode', 'PaymentModeController@index')->name('paymentMode.index');
    Route::post('paymentMode/view', 'PaymentModeController@view')->name('paymentMode.view');
    Route::post('paymentMode/edit', 'PaymentModeController@edit')->name('paymentMode.edit');
    Route::post('paymentMode/store', 'PaymentModeController@store')->name('paymentMode.store');
    Route::post('paymentMode/delete', 'PaymentModeController@destroy')->name('paymentMode.delete');
    Route::delete('paymentMode/destroy', 'PaymentModeController@massDestroy')->name('paymentMode.massDestroy');

    // Religion
    Route::get('religions', 'ReligionController@index')->name('religions.index');
    Route::post('religions/view', 'ReligionController@view')->name('religions.view');
    Route::post('religions/edit', 'ReligionController@edit')->name('religions.edit');
    Route::post('religions/store', 'ReligionController@store')->name('religions.store');
    Route::post('religions/delete', 'ReligionController@destroy')->name('religions.delete');
    Route::delete('religions/destroy', 'ReligionController@massDestroy')->name('religions.massDestroy');

    // Blood Group
    Route::get('blood-groups', 'BloodGroupController@index')->name('blood-groups.index');
    Route::post('blood-groups/view', 'BloodGroupController@view')->name('blood-groups.view');
    Route::post('blood-groups/edit', 'BloodGroupController@edit')->name('blood-groups.edit');
    Route::post('blood-groups/store', 'BloodGroupController@store')->name('blood-groups.store');
    Route::post('blood-groups/delete', 'BloodGroupController@destroy')->name('blood-groups.delete');
    Route::delete('blood-groups/destroy', 'BloodGroupController@massDestroy')->name('blood-groups.massDestroy');

    // Community
    Route::get('communities', 'CommunityController@index')->name('communities.index');
    Route::post('communities/view', 'CommunityController@view')->name('communities.view');
    Route::post('communities/edit', 'CommunityController@edit')->name('communities.edit');
    Route::post('communities/store', 'CommunityController@store')->name('communities.store');
    Route::post('communities/delete', 'CommunityController@destroy')->name('communities.delete');
    Route::delete('communities/destroy', 'CommunityController@massDestroy')->name('communities.massDestroy');

    // Mother Tongue
    Route::get('mother-tongues', 'MotherTongueController@index')->name('mother-tongues.index');
    Route::post('mother-tongues/view', 'MotherTongueController@view')->name('mother-tongues.view');
    Route::post('mother-tongues/edit', 'MotherTongueController@edit')->name('mother-tongues.edit');
    Route::post('mother-tongues/store', 'MotherTongueController@store')->name('mother-tongues.store');
    Route::post('mother-tongues/delete', 'MotherTongueController@destroy')->name('mother-tongues.delete');
    Route::delete('mother-tongues/destroy', 'MotherTongueController@massDestroy')->name('mother-tongues.massDestroy');

    // Education Board
    Route::get('education-boards', 'EducationBoardController@index')->name('education-boards.index');
    Route::post('education-boards/view', 'EducationBoardController@view')->name('education-boards.view');
    Route::post('education-boards/edit', 'EducationBoardController@edit')->name('education-boards.edit');
    Route::post('education-boards/store', 'EducationBoardController@store')->name('education-boards.store');
    Route::post('education-boards/delete', 'EducationBoardController@destroy')->name('education-boards.delete');
    Route::delete('education-boards/destroy', 'EducationBoardController@massDestroy')->name('education-boards.massDestroy');



    Route::get('class-rooms', 'ClassRoomsController@index')->name('class-rooms.index');
    Route::post('class-rooms/view', 'ClassRoomsController@view')->name('class-rooms.view');
    Route::post('class-rooms/edit', 'ClassRoomsController@edit')->name('class-rooms.edit');
    Route::post('class-rooms/store', 'ClassRoomsController@store')->name('class-rooms.store');
    Route::post('class-rooms/delete', 'ClassRoomsController@destroy')->name('class-rooms.delete');
    Route::post('class-rooms/change-status', 'ClassRoomsController@changeStatus')->name('class-rooms.change-status');
    Route::post('class-rooms/getGrade', 'ClassRoomsController@getGrade')->name('class-rooms.getGrade');
    Route::delete('class-rooms/destroy', 'ClassRoomsController@massDestroy')->name('class-rooms.massDestroy');

     Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::get('password/Staff-Edit', 'ChangePasswordController@Staff_edit')->name('password.Staff_edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
