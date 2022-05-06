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
    return redirect('/dashboard');
});

Auth::routes(['register' => false]);

Route::group(['middleware' => 'guest'], function () {
    Route::get('/confirm-email/{module}/{token}', 'UserController@verify_email')->name('verify.email');
    Route::get('/user-invitation/{token}', 'UserController@user_invitation')->name('verify.user_invitation');
    Route::put('/user/reset-password/{token}', 'UserController@reset_password')->name('user.reset-password');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::put('/confirm-email/{id}', 'UserController@confirm_email')->name('confirm.email');

    Route::get('/firefighter/paginate', 'FirefighterController@paginate')->name('firefighter.paginate');
    Route::get('/firefighter/history/{id}', 'FirefighterController@history')->name('firefighter.history');
    Route::post('/firefighter/archive-create', 'FirefighterController@archive_create')->name('firefighter.archive-create');
    Route::post('/firefighter/unarchive', 'FirefighterController@unarchive')->name('firefighter.unarchive');
    Route::get('/firefighter/archive', 'FirefighterController@archive')->name('firefighter.archive');
    Route::get('/firefighter/course/{id}', 'FirefighterController@course')->name('firefighter.course');
    Route::get('/firefighter/course/{id}/paginate', 'FirefighterController@paginate_course')->name('firefighter.paginate-course');
    Route::get('/firefighter/course/{course_id}/{id}/attendance', 'FirefighterController@attendance')->name('firefighter.attendance');
    Route::get('/firefighter/course/{course_id}/{id}/paginate-attendance', 'FirefighterController@paginate_attendance')->name('firefighter.paginate-attendance');
    Route::put('/firefighter/course/{course_id}/{id}/update-attendance', 'FirefighterController@update_attendance')->name('firefighter.update-attendance');
    Route::get('/firefighter/certifications/{id}', 'FirefighterController@certifications')->name('firefighter.certifications');
    Route::get('/firefighter/certifications/{id}/paginate', 'FirefighterController@paginate_certifications')->name('firefighter.paginate-certifications');
    Route::get('/firefighter/view-certification/{id}/{certificate_id}', 'FirefighterController@view_certification')->name('firefighter.view-certification');
    Route::put('/firefighter/renew-certification/{id}', 'FirefighterController@renew_certification');
    Route::get('/firefighter/certifications/{id}/{certificate_id}/past-records', 'FirefighterController@certifications_past_records')->name('firefighter.certifications-past-records');
    Route::get('/firefighter/certifications/{id}/{certificate_id}/past-records/paginate', 'FirefighterController@paginate_certifications_past_records')->name('firefighter.paginate-certifications-past-records');

    // Create
    Route::get('/firefighter/create','FirefighterController@create')->name('firefighter.create');
    Route::post('/firefighter','FirefighterController@store')->name('firefighter.store');

    // Read
    Route::get('/firefighter','FirefighterController@index')->name('firefighter.index');
    Route::get('/firefighter/{firefighter}','FirefighterController@show')->name('firefighter.show');

    // Update
    Route::get('/firefighter/{firefighter}/edit','FirefighterController@edit')->name('firefighter.edit');
    Route::put('/firefighter/{firefighter}','FirefighterController@update')->name('firefighter.update');
    Route::patch('/firefighter/{firefighter}','FirefighterController@update')->name('firefighter.update');

    // Delete
    Route::delete('/firefighter/{firefighter}','FirefighterController@destroy')->name('firefighter.destroy');


    //Route::resource('firefighter', 'FirefighterController');

    Route::get('/credit-type/paginate', 'CreditTypeController@paginate')->name('credit-type.paginate');
    Route::resource('credit-type', 'CreditTypeController');

    Route::get('/course/paginate', 'CourseController@paginate')->name('course.paginate');
    Route::get('/course/history/{id}', 'CourseController@history')->name('course.history');
    Route::post('/course/archive-create', 'CourseController@archive_create')->name('course.archive-create');
    Route::post('/course/unarchive', 'CourseController@unarchive')->name('course.unarchive');
    Route::get('/course/archive', 'CourseController@archive')->name('course.archive');
    Route::get('/course/search-credit-type', 'CourseController@search_credit_type')->name('course.search-credit-type');
    Route::resource('course', 'CourseController');

    Route::post('/class/unarchive', 'ClassController@unarchive')->name('class.unarchive');
    Route::post('/class/archive-create', 'ClassController@archive_create')->name('class.archive-create');
    Route::get('/class/search-organization', 'ClassController@search_organization')->name('class.search-organization');
    Route::get('/class/search-instructor', 'ClassController@search_instructor')->name('class.search-instructor');
    Route::get('/class/search-facility', 'ClassController@search_facility')->name('class.search-facility');
    Route::get('/class/search-facility-type', 'ClassController@search_facility_type')->name('class.search-facility-type');
    Route::get('/class/search-firefighter', 'ClassController@search_firefighter')->name('class.search-firefighter');
    Route::get('/class/search-semester', 'ClassController@search_semester')->name('class.search-semester');
    Route::get('/class/{course_id}', 'ClassController@index')->name('class.index');
    Route::get('/class/{course_id}/paginate', 'ClassController@paginate')->name('class.paginate');
    Route::get('/class/history/{id}', 'ClassController@history')->name('class.history');
    Route::get('/class/{course_id}/create', 'ClassController@create')->name('class.create');
    Route::post('/class/{course_id}', 'ClassController@store')->name('class.store');
    Route::get('/class/{course_id}/archive', 'ClassController@archive')->name('class.archive');
    Route::get('/class/{course_id}/{class_id}', 'ClassController@show')->name('class.show');
    Route::get('/class/{course_id}/{class_id}/edit', 'ClassController@edit')->name('class.edit');
    Route::put('/class/{course_id}/{class_id}', 'ClassController@update')->name('class.update');
    Route::get('/class/{course_id}/{class_id}/attendance', 'ClassController@attendance')->name('class.attendance');
    Route::put('/class/{course_id}/{class_id}/attendance', 'ClassController@update_attendance')->name('class.update-attendance');
    Route::get('/class/{course_id}/{class_id}/paginate-attendance', 'ClassController@paginate_attendance')->name('class.paginate-attendance');
    Route::get('/class/{course_id}/{class_id}/attendance/history', 'ClassController@history_attendance')->name('class.history-attendance');
    Route::delete('/class/{class_id}', 'ClassController@destroy');

    Route::get('/course-class/{course_id}/{firefighter_id}/history', 'CourseClassController@history')->name('course-classes.history');

    Route::get('/semester/paginate', 'SemesterController@paginate')->name('semester.paginate');
    Route::get('/semester/history/{id}', 'SemesterController@history')->name('semester.history');
    Route::post('/semester/archive-create', 'SemesterController@archive_create')->name('semester.archive-create');
    Route::post('/semester/unarchive', 'SemesterController@unarchive')->name('semester.unarchive');
    Route::get('/semester/archive', 'SemesterController@archive')->name('semester.archive');
    Route::get('/semester/search-courses', 'SemesterController@search_courses')->name('semester.search-courses');
    Route::resource('semester', 'SemesterController');

    Route::get('/organization/paginate', 'OrganizationController@paginate')->name('organization.paginate');
    Route::get('/organization/history/{id}', 'OrganizationController@history')->name('organization.history');
    Route::post('/organization/archive-create', 'OrganizationController@archive_create')->name('organization.archive-create');
    Route::post('/organization/unarchive', 'OrganizationController@unarchive')->name('organization.unarchive');
    Route::get('/organization/archive', 'OrganizationController@archive')->name('organization.archive');
    Route::resource('organization', 'OrganizationController');

    Route::get('/facility-type/paginate', 'FacilityTypeController@paginate')->name('facility-type.paginate');
    Route::resource('facility-type', 'FacilityTypeController');

    Route::get('/facility/paginate', 'FacilityController@paginate')->name('facility.paginate');
    Route::get('/facility/history/{id}', 'FacilityController@history')->name('facility.history');
    Route::post('/facility/archive-create', 'FacilityController@archive_create')->name('facility.archive-create');
    Route::post('/facility/unarchive', 'FacilityController@unarchive')->name('facility.unarchive');
    Route::get('/facility/archive', 'FacilityController@archive')->name('facility.archive');
    Route::get('/facility/search-facility-type', 'FacilityController@search_facility_type')->name('facility.search-facility-type');
    Route::get('/facility/search-organization', 'FacilityController@search_organization')->name('facility.search-organization');
    Route::resource('facility', 'FacilityController');

    Route::get('/fire-department/paginate', 'FireDepartmentController@paginate')->name('fire-department.paginate');
    Route::get('/fire-department/archive', 'FireDepartmentController@archive')->name('fire-department.archive');
    Route::post('/fire-department/archive-create', 'FireDepartmentController@archive_create')->name('fire-department.archive-create');
    Route::post('/fire-department/unarchive', 'FireDepartmentController@unarchive')->name('fire-department.unarchive');
    Route::get('/fire-department/history/{id}', 'FireDepartmentController@history')->name('fire-department.history');
    Route::resource('fire-department', 'FireDepartmentController');

    Route::get('/completed-course/{firefighter_id}', 'CompletedCourseController@index')->name('completed-course.index');
    Route::post('completed-course/mark-completed/{firefighter_id}', 'CompletedCourseController@mark_completed')->name('completed-course.mark-completed');
    Route::get('/completed-course/{firefighter_id}/paginate', 'CompletedCourseController@paginate')->name('completed-course.paginate');
    Route::get('/completed-course/{firefighter_id}/archive', 'CompletedCourseController@archive')->name('completed-course.archive');
    Route::post('/completed-course/archive-create', 'CompletedCourseController@archive_create')->name('completed-course.archive-create');
    Route::post('/completed-course/unarchive', 'CompletedCourseController@unarchive')->name('completed-course.unarchive');
    Route::get('/completed-course/{firefighter_id}/{semester_id}/{course_id}/{code}/process-transcript', 'FirefighterController@process_transcript');
    Route::post('/completed-course/{firefighter_id}/{semester_id}/{course_id}/{code}/process-transcript', 'FirefighterController@process_transcript');

    Route::get('/certification/paginate', 'CertificationController@paginate')->name('certification.paginate');
    Route::get('/certification/search-certification', 'CertificationController@search_certifications')->name('certification.search-certifications');
    Route::get('/certification/history/{id}', 'CertificationController@history')->name('certification.history');
    Route::get('/certification/award/{id}', 'CertificationController@award')->name('certification.award');
    Route::post('/certification/award/{id}', 'CertificationController@award_firefighters')->name('certification.award-firefighters');
    Route::get('/certification/search-firefighter', 'CertificationController@search_firefighter')->name('certification.search-firefighter');
    Route::resource('certification','CertificationController');

    Route::get('/settings', 'SettingsController@index')->name('settings.index');

    Route::get('/user/archive', 'UserController@archive')->name('user.archive');
    Route::get('/user/paginate', 'UserController@paginate')->name('user.paginate');
    Route::post('/user/archive-create', 'UserController@archive_create')->name('user.archive-create');
    Route::post('/user/unarchive', 'UserController@unarchive')->name('user.unarchive');
    Route::resource('user', 'UserController');


    Route::get('/create_permissions', 'UserController@create_permissions');
    Route::get('/permissions', 'UserController@permissions');

});