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

// Route::get('/new', 'FirefighterController@sdsadsd')->name('neww');
Route::post('/ajaxRequest.post', 'FirefighterController@certificate_history')->name('ajaxRequest.post');

Auth::routes(['register' => false]);

// verify firefighter old work
// Route::get('/firefighter-invitation/{token}', 'FirefighterSettingController@verify_firefighter_invitation')->name('firefighter.setting.verify-firefighter-invitation');
// Route::put('/user/firefighter-reset-password/{token}', 'FirefighterSettingController@firefighter_reset_password')->name('firefighter.setting.firefighter.reset-password');

Route::prefix('firefighters')->group(function () {
    // Dashboard route
    Route::get('/', 'Firefighter\FirefighterDashboardController@index')->name('firefighters.dashboard');

    // Login routes
    Route::get('/login', 'Auth\FirefighterLoginController@showLoginForm')->name('firefighters.login');
    Route::post('/login', 'Auth\FirefighterLoginController@login')->name('firefighters.login.submit');

    // Logout route
    Route::post('/logout', 'Auth\FirefighterLoginController@logout')->name('firefighters.logout');

    // Register routes
    Route::get('/register', 'Auth\FirefighterLoginController@showRegistrationForm')->name('firefighters.register');
    Route::post('/register', 'Auth\FirefighterLoginController@register')->name('firefighters.register.submit');

    // Password reset routes
    Route::get('/password/reset', 'Auth\FirefighterForgotPasswordController@showLinkRequestForm')->name('firefighters.password.request');
    Route::post('/password/email', 'Auth\FirefighterForgotPasswordController@sendResetLinkEmail')->name('firefighters.password.email');
    Route::get('/password/reset/{token}', 'Auth\FirefighterResetPasswordController@showResetForm')->name('firefighters.password.reset');
    Route::post('/password/reset', 'Auth\FirefighterResetPasswordController@reset')->name('firefighters.password.update');
    Route::get('/firefighter-invitation/{token}', 'FirefighterSettingController@verify_firefighter_invitation')->name('verify.verify_firefighter_invitation');
    Route::put('/firefighter-invitation/firefighter_reset_password/{token}', 'FirefighterSettingController@firefighter_reset_password')->name('firefighter.setting.firefighter.reset-password');

    Route::group(['middleware' => 'auth:firefighters'], function () {

        Route::get('profile', 'Firefighter\FirefighterDashboardController@profile')->name('firefighters.profile');
        Route::put('/profile','Firefighter\FirefighterDashboardController@update_profile')->name('firefighters.update-profile');

        // firefighter today's classes
        Route::get('today-classes', 'Firefighter\FirefighterDashboardController@today_classes')->name('firefighters.today-classes');
        Route::get('today-classes/paginate', 'Firefighter\FirefighterDashboardController@today_classes_paginate')->name('firefighters.today-classes.paginate');

        // firefighter tommorows's classes
        Route::get('tomorrow-classes', 'Firefighter\FirefighterDashboardController@tomorrow_classes')->name('firefighters.tomorrow-classes');
        Route::get('tomorrow-classes/paginate', 'Firefighter\FirefighterDashboardController@tomorrow_classes_paginate')->name('firefighters.tomorrow-classes.paginate');

        // firefighter yesterday's classes
        Route::get('yesterday-classes', 'Firefighter\FirefighterDashboardController@yesterday_classes')->name('firefighters.yesterday-classes');
        Route::get('yesterday-classes/paginate', 'Firefighter\FirefighterDashboardController@yesterday_classes_paginate')->name('firefighters.yesterday-classes.paginate');

        /* Semester */
        Route::get('/semester','Firefighter\SemesterController@index')->name('firefighters.semester.index');
        Route::get('/semester/paginate', 'Firefighter\SemesterController@paginate')->name('firefighters.semester.paginate');
        Route::get('/semester/{semester}','Firefighter\SemesterController@show')->name('firefighters.semester.show');


        /* Courses */

        // Route::get('/course','Firefighter\CourseController@index')->name('firefighters.course.index');
        // Route::get('/course/paginate', 'Firefighter\CourseController@paginate')->name('firefighters.course.paginate');
        // Route::get('/course/{course}','Firefighter\CourseController@show')->name('firefighters.course.show');

        /* My Courses */

        Route::get('/my-courses','Firefighter\CourseController@my_courses_index')->name('firefighters.my-courses.index');
        Route::get('/my-courses-paginate','Firefighter\CourseController@my_courses_paginate')->name('firefighters.my-courses.paginate');
        Route::get('/my-courses/{course}','Firefighter\CourseController@my_courses_show')->name('firefighters.my-courses.show');

        /* View Course Classes */
        Route::get('/view-classes/{course_id}', 'Firefighter\ClassController@index')->name('firefighters.classes.index');
        Route::get('/view-classes-paginate/{course_id}', 'Firefighter\ClassController@paginate')->name('firefighters.classes.paginate');

        /*  Firefighter apply courses controller */
        Route::post('/firefighter-apply-courses','FirefighterCoursesController@store')->name('firefighters.apply.courses');

        /* All Credentials */
        Route::get('/all-certification','Firefighter\CertificationController@all_credential_index')->name('firefighters.all.certification.index');
        Route::get('/all-certification-paginate','Firefighter\CertificationController@all_credential_paginte')->name('firefighters.all.certification.paginate');
        Route::get('/all-certification/{certification}','Firefighter\CertificationController@all_credential_show')->name('firefighters.all.certification.show');

        /*  Firefighter apply Credentials controller */
        Route::post('/firefighter-apply-certificates','Firefighter\CertificationController@store')->name('firefighters.apply.certificates');



        // history
        Route::get('/certification/All-history/', 'CertificationController@view_all_history')->name('firefighters.certicates.history');

        /*  Firefighter supply apply Credentials controller */
        Route::post('/firefighter-supply-certificates','Firefighter\CertificationController@supply_store')->name('firefighters.supply.apply.certificates');

        /* Old Credentials work for firefighter */
        // Route::get('/certification', 'Firefighter\CertificationController@index')->name('firefighters.certification.index');
        // Route::get('/certification/paginate', 'Firefighter\CertificationController@paginate')->name('firefighters.certification.paginate');
        // Route::get('/certification/{certification}','Firefighter\CertificationController@show')->name('firefighters.certification.show');

        /* My Applied Credentials  */
        Route::get('/apply-certification','Firefighter\CertificationController@apply_certification_index')->name('firefighters.apply-certificates.index');
        Route::get('/view-apply-certification-paginate', 'Firefighter\CertificationController@apply_certification_paginate')->name('firefighters.apply-certificates.paginate');
        Route::get('/apply-certification/{certification}','Firefighter\CertificationController@apply_certification_show')->name('firefighters.apply-certificates.show');

        /* My Approved Credentials  */
        Route::get('/approved-certification','Firefighter\CertificationController@approved_certification_index')->name('firefighters.approved-certificates.index');
        Route::get('/view-approved-certification-paginate', 'Firefighter\CertificationController@approved_certification_paginate')->name('firefighters.approved-certificates.paginate');
        Route::get('/approved-certification/{certification}','Firefighter\CertificationController@approved_certification_show')->name('firefighters.approved-certificates.show');

        /* My Rejected Credentials  */
        Route::get('/reject-certification','Firefighter\CertificationController@reject_certification_index')->name('firefighters.reject-certificates.index');
        Route::get('/view-reject-certification-paginate', 'Firefighter\CertificationController@reject_certification_paginate')->name('firefighters.reject-certificates.paginate');
        Route::get('/reject-certification/{certification}','Firefighter\CertificationController@reject_certification_show')->name('firefighters.reject-certificates.show');

        /* My Failed Credentials  */
        Route::get('/failed-certification','Firefighter\CertificationController@failed_certification_index')->name('firefighters.failed-certificates.index');
        Route::get('/view-failed-certification-paginate', 'Firefighter\CertificationController@failed_certification_paginate')->name('firefighters.failed-certificates.paginate');
        Route::get('/failed-certification/{certification}','Firefighter\CertificationController@failed_certification_show')->name('firefighters.failed-certificates.show');

        /* My Awarded Credentials  */
        Route::get('/awarded-certification','Firefighter\CertificationController@awarded_certification_index')->name('firefighters.awarded-certificates.index');
        Route::get('/view-awarded-certification-paginate', 'Firefighter\CertificationController@awarded_certification_paginate')->name('firefighters.awarded-certificates.paginate');
        Route::get('/awarded-certification/{certification}','Firefighter\CertificationController@awarded_certification_show')->name('firefighters.awarded-certificates.show');
        Route::get('/view-certification/{certificate_id}', 'Firefighter\CertificationController@view_all_credential')->name('firefighters.view-all-certification');
        Route::get('/certifications/{certificate_id}/past-records', 'Firefighter\CertificationController@credential_past_records')->name('firefighters.certifications-past-records');
        Route::get('/certifications/{certificate_id}/past-records/paginate', 'Firefighter\CertificationController@paginate_credentials_past_records')->name('firefighters.paginate-certifications-past-records');

    });
    /* Credit types */
    // Route::get('/credit-type','Firefighter\CreditTypeController@index')->name('firefighters.credit-type.index');
    // Route::get('/credit-type/paginate', 'Firefighter\CreditTypeController@paginate')->name('firefighters.credit-type.paginate');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/confirm-email/{module}/{token}', 'UserController@verify_email')->name('verify.email');
    Route::get('/user-invitation/{token}', 'UserController@user_invitation')->name('verify.user_invitation');
    Route::put('/user/reset-password/{token}', 'UserController@reset_password')->name('user.reset-password');
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/reports/history', 'ReportController@history')->name('reports.history');
    Route::get('/reports/paginate-history', 'ReportController@paginate_history')->name('reports.paginate-history');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/user/profile','UserController@profile')->name('user.profile');
    Route::put('/user/profile','UserController@update_profile')->name('user.update-profile');

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/dashboard/renewal-certifications', 'DashboardController@renewal_certifications')->middleware('permission:certifications.read')->name('dashboard.renewal-certifications');
    Route::get('/dashboard/renewal-certifications/paginate', 'DashboardController@paginate_renewal_certifications')->middleware('permission:certifications.read')->name('dashboard.paginate-renewal-certifications');
    Route::group(['middleware' => ['permission:courses.read']], function () {
        Route::get('/dashboard/today-classes', 'DashboardController@today_classes')->name('dashboard.today-classes');
        Route::get('/dashboard/today-classes/paginate', 'DashboardController@paginate_today_classes')->name('dashboard.paginate-today-classes');
        Route::get('/dashboard/yesterday-classes', 'DashboardController@yesterday_classes')->name('dashboard.yesterday-classes');
        Route::get('/dashboard/yesterday-classes/paginate', 'DashboardController@paginate_yesterday_classes')->name('dashboard.paginate-yesterday-classes');
        Route::get('/dashboard/tomorrow-classes', 'DashboardController@tomorrow_classes')->name('dashboard.tomorrow-classes');
        Route::get('/dashboard/tomorrow-classes/paginate', 'DashboardController@paginate_tomorrow_classes')->name('dashboard.paginate-tomorrow-classes');
    });
    Route::put('/confirm-email/{id}', 'UserController@confirm_email')->name('confirm.email');
    /*
    |--------------------------------------------------------------------------
    | Firefighters
    |--------------------------------------------------------------------------
    */
    Route::post('/instructor-level', 'InstructorPrerequisitesController@store')->middleware('permission:firefighters.create')->name('instructor-level.store');
    Route::get('/instructor-level/create', 'InstructorPrerequisitesController@create')->middleware('permission:firefighters.create')->name('instructor-level.create');
    Route::delete('/instructor-level/{instructor_level}', 'InstructorPrerequisitesController@destroy')->middleware('permission:firefighters.delete')->name('instructor-level.delete');
    Route::group(['middleware' => ['permission:firefighters.read']], function () {
        Route::get('/firefighter/paginate', 'FirefighterController@paginate')->name('firefighter.paginate');
        Route::get('/firefighter/history/{id}', 'FirefighterController@history')->name('firefighter.history');
        Route::get('/firefighter/archive', 'FirefighterController@archive')->name('firefighter.archive');
        Route::get('/firefighter/course/{id}', 'FirefighterController@course')->name('firefighter.course');
        Route::get('/firefighter/course/{id}/paginate', 'FirefighterController@paginate_course')->name('firefighter.paginate-course');
        Route::get('/firefighter/course/{course_id}/{id}/attendance', 'FirefighterController@attendance')->name('firefighter.attendance');
        Route::get('/firefighter/course/{course_id}/{id}/paginate-attendance', 'FirefighterController@paginate_attendance')->name('firefighter.paginate-attendance');
        Route::get('/firefighter/certifications/{id}', 'FirefighterController@certifications')->name('firefighter.certifications');

       

        Route::get('/firefighter/certifications/{id}/paginate', 'FirefighterController@paginate_certifications')->name('firefighter.paginate-certifications');


     

        Route::get('firefighter/certifications/lapse/{id}/edit', 'FirefighterController@edit_lapse');

       
        Route::post('firefighter/certifications/update-lapse-date', 'FirefighterController@update_lapse')->name('lapse-date.store');


        Route::post('firefighter/certifications/manual-CEUs', 'FirefighterController@manual_ceus')->name('manual.ceu.store');



        Route::get('/firefighter/view-certification/{id}/{certificate_id}', 'FirefighterController@view_certification')->name('firefighter.view-certification');
        Route::get('/firefighter/certifications/{id}/{certificate_id}/past-records', 'FirefighterController@certifications_past_records')->name('firefighter.certifications-past-records');
        Route::get('/firefighter/certifications/{id}/{certificate_id}/past-records/paginate', 'FirefighterController@paginate_certifications_past_records')->name('firefighter.paginate-certifications-past-records');
        Route::get('/completed-course/{firefighter_id}', 'CompletedCourseController@index')->name('completed-course.index');
        Route::get('/completed-course/{firefighter_id}/paginate', 'CompletedCourseController@paginate')->name('completed-course.paginate');
        Route::get('/completed-course/{firefighter_id}/archive', 'CompletedCourseController@archive')->name('completed-course.archive');
        Route::get('/completed-course/{firefighter_id}/{semester_id}/{course_id}/{code}/process-transcript', 'FirefighterController@process_transcript');
        Route::get('/instructor-level', 'InstructorPrerequisitesController@index')->name('instructor-level.index');
        Route::get('/instructor-level/paginate', 'InstructorPrerequisitesController@paginate')->name('instructor-level.paginate');
        Route::get('/instructor-level/{instructor_level}', 'InstructorPrerequisitesController@show')->name('instructor-level.show');
        Route::get('/firefighter/get_municode/', 'FirefighterController@get_municode');
        Route::post('/firefighter/change_role/', 'FirefighterController@change_role');
    });

    Route::group(['middleware' => ['permission:firefighters.update']], function () {
        Route::post('/firefighter/archive-create', 'FirefighterController@archive_create')->name('firefighter.archive-create');
        Route::post('/firefighter/unarchive', 'FirefighterController@unarchive')->name('firefighter.unarchive');
        Route::put('/firefighter/course/{course_id}/{id}/update-attendance', 'FirefighterController@update_attendance')->name('firefighter.update-attendance');
        Route::put('/firefighter/renew-certification/{id}', 'FirefighterController@renew_certification');
        Route::post('/firefighter/bulk-renew-cert', 'FirefighterController@bulk_renew_cert')->name('firefighter.bulk-renew-cert');
        Route::post('completed-course/mark-completed/{firefighter_id}', 'CompletedCourseController@mark_completed')->name('completed-course.mark-completed');
        Route::post('/completed-course/archive-create', 'CompletedCourseController@archive_create')->name('completed-course.archive-create');
        Route::post('/completed-course/unarchive', 'CompletedCourseController@unarchive')->name('completed-course.unarchive');
        Route::post('/completed-course/{firefighter_id}/{semester_id}/{course_id}/{code}/process-transcript', 'FirefighterController@process_transcript');
        Route::get('/instructor-level/{instructor_level}/edit', 'InstructorPrerequisitesController@edit')->name('instructor-level.edit');
        Route::put('/instructor-level/{instructor_level}', 'InstructorPrerequisitesController@update')->name('instructor-level.update');
    });
    /* Resource Routes */
    Route::get('/firefighter/create','FirefighterController@create')->middleware('permission:firefighters.create')->name('firefighter.create');
    Route::post('/firefighter','FirefighterController@store')->middleware('permission:firefighters.create')->name('firefighter.store');
    Route::get('/firefighter','FirefighterController@index')->middleware('permission:firefighters.read')->name('firefighter.index');
    Route::get('/firefighter/{firefighter}','FirefighterController@show')->middleware('permission:firefighters.read')->name('firefighter.show');
    Route::get('/firefighter/{firefighter}/edit','FirefighterController@edit')->middleware('permission:firefighters.update')->name('firefighter.edit');
    Route::put('/firefighter/{firefighter}','FirefighterController@update')->middleware('permission:firefighters.update')->name('firefighter.update');
    Route::patch('/firefighter/{firefighter}','FirefighterController@update')->middleware('permission:firefighters.update')->name('firefighter.update');
    Route::delete('/firefighter/{firefighter}','FirefighterController@destroy')->middleware('permission:firefighters.delete')->name('firefighter.destroy');

    /*
    |--------------------------------------------------------------------------
    | Semester
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:semesters.read']], function () {
        Route::get('/semester/paginate', 'SemesterController@paginate')->name('semester.paginate');
        Route::get('/semester/history/{id}', 'SemesterController@history')->name('semester.history');
        Route::get('/semester/archive', 'SemesterController@archive')->name('semester.archive');
        Route::get('/semester/search-courses', 'SemesterController@search_courses')->name('semester.search-courses');
    });
    Route::group(['middleware' => ['permission:semesters.update']], function () {
        Route::post('/semester/archive-create', 'SemesterController@archive_create')->name('semester.archive-create');
        Route::post('/semester/unarchive', 'SemesterController@unarchive')->name('semester.unarchive');
    });
    /* Resource Routes */
    Route::get('/semester/create','SemesterController@create')->middleware('permission:semesters.create')->name('semester.create');
    Route::post('/semester','SemesterController@store')->middleware('permission:semesters.create')->name('semester.store');
    Route::get('/semester','SemesterController@index')->middleware('permission:semesters.read')->name('semester.index');
    Route::get('/semester/{semester}','SemesterController@show')->middleware('permission:semesters.read')->name('semester.show');
    Route::get('/semester/{semester}/edit','SemesterController@edit')->middleware('permission:semesters.update')->name('semester.edit');
    Route::put('/semester/{semester}','SemesterController@update')->middleware('permission:semesters.update')->name('semester.update');
    Route::patch('/semester/{semester}','SemesterController@update')->middleware('permission:semesters.update')->name('semester.update');
    Route::delete('/semester/{semester}','SemesterController@destroy')->middleware('permission:semesters.delete')->name('semester.destroy');

    /*
    |--------------------------------------------------------------------------
    | Credit types
    |--------------------------------------------------------------------------
    */
    Route::get('/credit-type/paginate', 'CreditTypeController@paginate')->middleware('permission:courses.read')->name('credit-type.paginate');
    /* Resource Routes */
    Route::get('/credit-type/create','CreditTypeController@create')->middleware('permission:courses.create')->name('credit-type.create');
    Route::post('/credit-type','CreditTypeController@store')->middleware('permission:courses.create')->name('credit-type.store');
    Route::get('/credit-type','CreditTypeController@index')->middleware('permission:courses.read')->name('credit-type.index');
    Route::get('/credit-type/{credit_type}','CreditTypeController@show')->middleware('permission:courses.read')->name('credit-type.show');
    Route::get('/credit-type/{credit_type}/edit','CreditTypeController@edit')->middleware('permission:courses.update')->name('credit-type.edit');
    Route::put('/credit-type/{credit_type}','CreditTypeController@update')->middleware('permission:courses.update')->name('credit-type.update');
    Route::patch('/credit-type/{credit_type}','CreditTypeController@update')->middleware('permission:courses.update')->name('credit-type.update');
    Route::delete('/credit-type/{credit_type}','CreditTypeController@destroy')->middleware('permission:courses.delete')->name('credit-type.destroy');

    Route::get('/group-credit-types', 'GroupCreditTypeController@index')->name('group-credit-types.index');
    Route::get('/group-credit-types/create', 'GroupCreditTypeController@create')->name('group-credit-types.create');
    Route::post('/group-credit-types/store', 'GroupCreditTypeController@store')->name('group-credit-types.store');
    Route::get('/group-credit-types/paginate', 'GroupCreditTypeController@paginate')->name('group-credit-types.paginate');
    Route::get('/group-credit-types/{credit_code}/edit', 'GroupCreditTypeController@edit')->name('group-credit-types.edit');
    Route::put('/group-credit-types-update/{credit_code}', 'GroupCreditTypeController@update')->name('group-credit-types.update');
    Route::delete('/group-credit-types/{credit_code}', 'GroupCreditTypeController@destroy')->name('group-credit-types.delete');

    Route::get('/courses-credit-types', 'GroupCreditTypeController@courses_credit_type_index')->name('courses-credit-types.index');
    Route::get('/courses-credit-types-paginate', 'GroupCreditTypeController@courses_credit_type_paginate')->name('courses-credit-types.paginate');

    Route::get('/view-courses-credit-types/{credit_type_id}', 'GroupCreditTypeController@view_courses_credit_type')->name('view-courses-credit-types.index');

    /*
    |--------------------------------------------------------------------------
    | Courses
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:courses.read']], function () {
        Route::get('/course/paginate', 'CourseController@paginate')->name('course.paginate');
        Route::get('/course/history/{id}', 'CourseController@history')->name('course.history');
        Route::get('/course/archive', 'CourseController@archive')->name('course.archive');
        Route::get('/course/search-credit-type', 'CourseController@search_credit_type')->name('course.search-credit-type');
        Route::get('/course/search-courses', 'CourseController@search_courses')->name('course.search-courses');
    });
    Route::group(['middleware' => ['permission:courses.update']], function () {
        Route::post('/course/archive-create', 'CourseController@archive_create')->name('course.archive-create');
        Route::post('/course/unarchive', 'CourseController@unarchive')->name('course.unarchive');
    });

    /* View Firefighter in Courses */
    Route::get('/course/{semester_id}/{course_id}/view-firefighters','CourseController@view_firefighters')->name('course.view-firefighters');
    Route::get('/course/{semester_id}/{course_id}/view-firefighters-paginate','CourseController@view_firefighters_paginate')->name('course.view-firefighters-paginate');
    Route::post('/course/approved-firefighters-courses','CourseController@approved_firefighters_courses')->name('course.approved-firefighters-courses');
    Route::post('/firefighter-reject-course','CourseController@firefighters_courses_reject')->name('course.firefighters.reject.course');

    /* Resource Routes */
    Route::get('/course/create','CourseController@create')->middleware('permission:courses.create')->name('course.create');
    Route::post('/course','CourseController@store')->middleware('permission:courses.create')->name('course.store');
    Route::get('/course','CourseController@index')->middleware('permission:courses.read')->name('course.index');
    Route::get('/course/{course}/edit','CourseController@edit')->middleware('permission:courses.update')->name('course.edit');
    Route::get('/course/{course}','CourseController@course_show')->middleware('permission:courses.read')->name('course.course_show');
    Route::put('/course/{course}','CourseController@update')->middleware('permission:courses.update')->name('course.update');
    Route::patch('/course/{course}','CourseController@update')->middleware('permission:courses.update')->name('course.update');
    Route::delete('/course/{course}','CourseController@destroy')->middleware('permission:courses.delete')->name('course.destroy');
    Route::get('/course/{semester_id}/{course}','CourseController@show')->middleware('permission:courses.read')->name('course.show');

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:courses.update']], function () {
        Route::post('/class/unarchive', 'ClassController@unarchive')->name('class.unarchive');
        Route::post('/class/archive-create', 'ClassController@archive_create')->name('class.archive-create');
        Route::get('/class/{semester_id}/{course_id}/{class_id}/edit', 'ClassController@edit')->name('class.edit');
        Route::put('/class/{course_id}/{class_id}', 'ClassController@update')->name('class.update');
    });
    Route::group(['middleware' => ['permission:courses.create']], function () {
        Route::get('/class/{semester_id}/{course_id}/create', 'ClassController@create')->name('class.create');
        Route::post('/class/{semester_id}/{course_id}', 'ClassController@store')->name('class.store');
        Route::get('/class/{semester_id}/{course_id}/{class_id}/attendance', 'ClassController@attendance')->name('class.attendance');
        Route::put('/class/{semester_id}/{course_id}/{class_id}/attendance', 'ClassController@update_attendance')->name('class.update-attendance');
    });
    Route::group(['middleware' => ['permission:courses.read']], function () {
        Route::get('/class/{course_id}/archive', 'ClassController@archive')->name('class.archive');
        Route::get('/class/search-organization', 'ClassController@search_organization')->name('class.search-organization');
        Route::get('/class/search-firedepartment', 'ClassController@search_firedepartment')->name('class.search-firedepartment');
        Route::get('/class/search-instructor', 'ClassController@search_instructor')->name('class.search-instructor');
        Route::get('/class/search-facility', 'ClassController@search_facility')->name('class.search-facility');
        Route::get('/class/search-facility-type', 'ClassController@search_facility_type')->name('class.search-facility-type');
        Route::get('/class/search-firefighter', 'ClassController@search_firefighter')->name('class.search-firefighter');
        Route::get('/class/search-semester', 'ClassController@search_semester')->name('class.search-semester');
        Route::get('/class/{semester_id}/{course_id}', 'ClassController@index')->name('class.index');
        Route::get('/class/{semester_id}/{course_id}/paginate', 'ClassController@paginate')->name('class.paginate');
        Route::get('/class/history/{id}', 'ClassController@history')->name('class.history');
        Route::get('/class/{semester_id}/{course_id}/{class_id}', 'ClassController@show')->name('class.show');
        Route::get('/class/{semester_id}/{course_id}/{class_id}/paginate-attendance', 'ClassController@paginate_attendance')->name('class.paginate-attendance');
        Route::get('/class/{course_id}/{class_id}/attendance/history', 'ClassController@history_attendance')->name('class.history-attendance');
        Route::get('/course-class/{course_id}/{firefighter_id}/history', 'CourseClassController@history')->name('course-classes.history');
    });
    Route::delete('/class/{class_id}', 'ClassController@destroy')->middleware('permission:courses.delete');

    /*
    |--------------------------------------------------------------------------
    | Organizations
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:organizations.read']], function () {
        Route::get('/organization/paginate', 'OrganizationController@paginate')->name('organization.paginate');
        Route::get('/organization/history/{id}', 'OrganizationController@history')->name('organization.history');
        Route::get('/organization/archive', 'OrganizationController@archive')->name('organization.archive');
    });
    Route::group(['middleware' => ['permission:organizations.update']], function () {
        Route::post('/organization/archive-create', 'OrganizationController@archive_create')->name('organization.archive-create');
        Route::post('/organization/unarchive', 'OrganizationController@unarchive')->name('organization.unarchive');
    });
    /* Resource Routes */
    Route::get('/organization/create','OrganizationController@create')->middleware('permission:organizations.create')->name('organization.create');
    Route::post('/organization','OrganizationController@store')->middleware('permission:organizations.create')->name('organization.store');
    Route::get('/organization','OrganizationController@index')->middleware('permission:organizations.read')->name('organization.index');
    Route::get('/organization/{organization}','OrganizationController@show')->middleware('permission:organizations.read')->name('organization.show');
    Route::get('/organization/{organization}/edit','OrganizationController@edit')->middleware('permission:organizations.update')->name('organization.edit');
    Route::put('/organization/{organization}','OrganizationController@update')->middleware('permission:organizations.update')->name('organization.update');
    Route::patch('/organization/{organization}','OrganizationController@update')->middleware('permission:organizations.update')->name('organization.update');
    Route::delete('/organization/{organization}','OrganizationController@destroy')->middleware('permission:organizations.delete')->name('organization.destroy');

    /*
    |--------------------------------------------------------------------------
    | Facility types
    |--------------------------------------------------------------------------
    */
    Route::get('/facility-type/paginate', 'FacilityTypeController@paginate')->middleware('permission:facilities.read')->name('facility-type.paginate');
    /* Resource Routes */
    Route::get('/facility-type/create','FacilityTypeController@create')->middleware('permission:facilities.create')->name('facility-type.create');
    Route::post('/facility-type','FacilityTypeController@store')->middleware('permission:facilities.create')->name('facility-type.store');
    Route::get('/facility-type','FacilityTypeController@index')->middleware('permission:facilities.read')->name('facility-type.index');
    Route::get('/facility-type/{facility_type}','FacilityTypeController@show')->middleware('permission:facilities.read')->name('facility-type.show');
    Route::get('/facility-type/{facility_type}/edit','FacilityTypeController@edit')->middleware('permission:facilities.update')->name('facility-type.edit');
    Route::put('/facility-type/{facility_type}','FacilityTypeController@update')->middleware('permission:facilities.update')->name('facility-type.update');
    Route::patch('/facility-type/{facility_type}','FacilityTypeController@update')->middleware('permission:facilities.update')->name('facility-type.update');
    Route::delete('/facility-type/{facility_type}','FacilityTypeController@destroy')->middleware('permission:facilities.delete')->name('facility-type.destroy');

    /*
    |--------------------------------------------------------------------------
    | Facilities
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:facilities.read']], function () {
        Route::get('/facility/paginate', 'FacilityController@paginate')->name('facility.paginate');
        Route::get('/facility/history/{id}', 'FacilityController@history')->name('facility.history');
        Route::get('/facility/archive', 'FacilityController@archive')->name('facility.archive');
        Route::get('/facility/search-facility-type', 'FacilityController@search_facility_type')->name('facility.search-facility-type');
        Route::get('/facility/search-organization', 'FacilityController@search_organization')->name('facility.search-organization');
    });
    Route::group(['middleware' => ['permission:facilities.update']], function () {
        Route::post('/facility/archive-create', 'FacilityController@archive_create')->name('facility.archive-create');
        Route::post('/facility/unarchive', 'FacilityController@unarchive')->name('facility.unarchive');
    });

    /* Resource Routes */
    Route::get('/facility/create','FacilityController@create')->middleware('permission:facilities.create')->name('facility.create');
    Route::post('/facility','FacilityController@store')->middleware('permission:facilities.create')->name('facility.store');
    Route::get('/facility','FacilityController@index')->middleware('permission:facilities.read')->name('facility.index');
    Route::get('/facility/{facility}','FacilityController@show')->middleware('permission:facilities.read')->name('facility.show');
    Route::get('/facility/{facility}/edit','FacilityController@edit')->middleware('permission:facilities.update')->name('facility.edit');
    Route::put('/facility/{facility}','FacilityController@update')->middleware('permission:facilities.update')->name('facility.update');
    Route::patch('/facility/{facility}','FacilityController@update')->middleware('permission:facilities.update')->name('facility.update');
    Route::delete('/facility/{facility}','FacilityController@destroy')->middleware('permission:facilities.delete')->name('facility.destroy');

    /*
    |--------------------------------------------------------------------------
    | Fire department
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:fire_departments.read']], function () {
        Route::get('/fire-department/paginate', 'FireDepartmentController@paginate')->name('fire-department.paginate');
        Route::get('/fire-department/archive', 'FireDepartmentController@archive')->name('fire-department.archive');
        Route::get('/fire-department/history/{id}', 'FireDepartmentController@history')->name('fire-department.history');
        Route::get('/fire-department/search-fire-department-type', 'FireDepartmentController@search_fire_department_type')->name('fire-department.search-fire-department-type');
    });
    Route::group(['middleware' => ['permission:fire_departments.update']], function () {
        Route::post('/fire-department/archive-create', 'FireDepartmentController@archive_create')->name('fire-department.archive-create');
        Route::post('/fire-department/unarchive', 'FireDepartmentController@unarchive')->name('fire-department.unarchive');
    });
    /* Resource Routes */
    Route::get('/fire-department/create','FireDepartmentController@create')->middleware('permission:fire_departments.create')->name('fire-department.create');
    Route::post('/fire-department','FireDepartmentController@store')->middleware('permission:fire_departments.create')->name('fire-department.store');
    Route::get('/fire-department','FireDepartmentController@index')->middleware('permission:fire_departments.read')->name('fire-department.index');
    Route::get('/fire-department/{fire_department}','FireDepartmentController@show')->middleware('permission:fire_departments.read')->name('fire-department.show');
    Route::get('/fire-department/{fire_department}/edit','FireDepartmentController@edit')->middleware('permission:fire_departments.update')->name('fire-department.edit');
    Route::put('/fire-department/{fire_department}','FireDepartmentController@update')->middleware('permission:fire_departments.update')->name('fire-department.update');
    Route::patch('/fire-department/{fire_department}','FireDepartmentController@update')->middleware('permission:fire_departments.update')->name('fire-department.update');
    Route::delete('/fire-department/{fire_department}','FireDepartmentController@destroy')->middleware('permission:fire_departments.delete')->name('fire-department.destroy');

    /*
    |--------------------------------------------------------------------------
    | Fire Dept. types
    |--------------------------------------------------------------------------
    */
    Route::get('/fire-department-type/paginate', 'FireDepartmentTypeController@paginate')->middleware('permission:courses.read')->name('fire-department-type.paginate');
    /* Resource Routes */
    Route::get('/fire-department-type/create','FireDepartmentTypeController@create')->middleware('permission:courses.create')->name('fire-department-type.create');
    Route::post('/fire-department-type','FireDepartmentTypeController@store')->middleware('permission:courses.create')->name('fire-department-type.store');
    Route::get('/fire-department-type','FireDepartmentTypeController@index')->middleware('permission:courses.read')->name('fire-department-type.index');
    Route::get('/fire-department-type/{fire_department_type}','FireDepartmentTypeController@show')->middleware('permission:courses.read')->name('fire-department-type.show');
    Route::get('/fire-department-type/{fire_department_type}/edit','FireDepartmentTypeController@edit')->middleware('permission:courses.update')->name('fire-department-type.edit');
    Route::put('/fire-department-type/{fire_department_type}','FireDepartmentTypeController@update')->middleware('permission:courses.update')->name('fire-department-type.update');
    Route::patch('/fire-department-type/{fire_department_type}','FireDepartmentTypeController@update')->middleware('permission:courses.update')->name('fire-department-type.update');
    Route::delete('/fire-department-type/{fire_department_type}','FireDepartmentTypeController@destroy')->middleware('permission:courses.delete')->name('fire-department-type.destroy');

    /*
    |--------------------------------------------------------------------------
    | Certification
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:certifications.read']], function () {
        Route::get('/certification/paginate', 'CertificationController@paginate')->name('certification.paginate');
        Route::get('/certification/paginate_expire', 'CertificationController@paginate_expire')->name('certification.paginate_expire');
        Route::get('/certification/paginate_awarded_certificate_personnel', 'CertificationController@paginate_awarded_certificate_personnel')->name('certification.paginate_awarded_certificate_personnel');
        Route::get('/certification/search-certification', 'CertificationController@search_certifications')->name('certification.search-certifications');


        Route::get('/certification/history/{id}', 'CertificationController@history')->name('certification.history');
    
      



        Route::get('/certification/search-firefighter', 'CertificationController@search_firefighter')->name('certification.search-firefighter');
        Route::get('/certification/search-organization', 'CertificationController@search_organization')->name('certification.search-organization');
    });
    Route::group(['middleware' => ['permission:certifications.create']], function () {
        Route::get('/certification/award/{id}', 'CertificationController@award')->name('certification.award');
        Route::post('/certification/award/{id}', 'CertificationController@award_firefighters')->name('certification.award-firefighters');
    });
    /* Resource Routes */
    Route::get('/certification/create','CertificationController@create')->middleware('permission:certifications.create')->name('certification.create');
    Route::post('/certification','CertificationController@store')->middleware('permission:certifications.create')->name('certification.store');
    Route::get('/certification','CertificationController@index')->middleware('permission:certifications.read')->name('certification.index');
    Route::get('/certification/expired','CertificationController@expired_certificates')->middleware('permission:certifications.read')->name('certification.expired');
    Route::post('/certification/bulk_renew_certification','CertificationController@bulk_renew_certification')->middleware('permission:certifications.read')->name('certification.bulk_renew_certification');
    Route::get('/certification/{certification}','CertificationController@show')->middleware('permission:certifications.read')->name('certification.show');
    Route::get('/certification/{certification}/edit','CertificationController@edit')->middleware('permission:certifications.update')->name('certification.edit');
    Route::put('/certification/{certification}','CertificationController@update')->middleware('permission:certifications.update')->name('certification.update');
    Route::patch('/certification/{certification}','CertificationController@update')->middleware('permission:certifications.update')->name('certification.update');
    Route::delete('/certification/{certification}','CertificationController@destroy')->middleware('permission:certifications.delete')->name('certification.destroy');
    Route::put('/certification/renew/{id}', 'CertificationController@renew_certification')->middleware('permission:certifications.read');
    Route::get('/certification/{certification}/awarded-certificate-personnels','CertificationController@get_awarded_certificate_personnels')->middleware('permission:certifications.read')->name('certification.awarded-certificate-personnels');

    /* View Firefighter in Certificates */
    Route::get('/certification/{certification}/view-firefighters','CertificationController@view_firefighters')->name('certificate.view-firefighters');
    Route::get('/certification/{certification_id}/view-firefighters-paginate','CertificationController@view_firefighters_paginate')->name('certificate.view-firefighters-paginate');
    Route::post('/certification/approved-firefighters-certification','CertificationController@approved_firefighters_certifications')->name('certificate.approved-firefighters-certificate');
    Route::post('/firefighter-reject-certification','CertificationController@firefighters_certifications_reject')->name('certificate.firefighters.reject.certificate');

    Route::post('/firefighter-accept-certification','CertificationController@firefighters_certifications_accept')->name('certificate.firefighters.accept.certificate');

    Route::get('/certification/{certification}/view-firefighters/status','CertificationController@view_firefighters_status_index')->name('certificate.view-firefighters.status.index');
    Route::get('/certification/{certification}/view-firefighters/status-paginate','CertificationController@view_firefighters_status_paginate')->name('certificate.view-firefighters.status.paginte');
    Route::post('/certification/view-firefighters/status-reshedule','CertificationController@view_firefighters_status_reshedule')->name('certificate.view-firefighters.status.reshedule');
    Route::post('/certification/view-firefighters/status-failed-certificate','CertificationController@view_firefighters_status_failed_certificate')->name('certificate.view-firefighters.status.failed.certificate');

    Route::post('/certification/view-firefighters/award-certificate','CertificationController@firefighters_award_certificate')->name('certificate.view-firefighters.award.certificate');

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['permission:settings.read']], function () {
        Route::get('/settings', 'SettingsController@index')->name('settings.index');
        Route::get('/user/archive', 'UserController@archive')->name('user.archive');
        Route::get('/user/paginate', 'UserController@paginate')->name('user.paginate');
    });

    Route::group(['middleware' => ['permission:settings.update']], function () {
        Route::post('/user/archive-create', 'UserController@archive_create')->name('user.archive-create');
        Route::post('/user/unarchive', 'UserController@unarchive')->name('user.unarchive');
        Route::get('/user/paginate', 'UserController@paginate')->name('user.paginate');
        Route::get('/settings/enrollment-limit', 'SettingsController@enrollment_limit')->name('settings.enrollment-limit');
        Route::put('/settings/enrollment-limit', 'SettingsController@save_enrollment_limit')->name('settings.save-enrollment-limit');
        Route::get('/settings/other-settings', 'SettingsController@other_settings')->name('settings.other-settings');
        Route::put('/settings/other-settings', 'SettingsController@save_other_settings')->name('settings.save-other-settings');
    });

    /* Resource Routes */
    Route::get('/user/create','UserController@create')->middleware('permission:settings.create')->name('user.create');
    Route::post('/user','UserController@store')->middleware('permission:settings.create')->name('user.store');
    Route::get('/user','UserController@index')->middleware('permission:settings.read')->name('user.index');
    Route::get('/user/{user}','UserController@show')->middleware('permission:settings.read')->name('user.show');
    Route::get('/user/{user}/edit','UserController@edit')->middleware('permission:settings.update')->name('user.edit');
    Route::put('/user/{user}','UserController@update')->middleware('permission:settings.update')->name('user.update');
    Route::patch('/user/{user}','UserController@update')->middleware('permission:settings.update')->name('user.update');
    Route::delete('/user/{user}','UserController@destroy')->middleware('permission:settings.delete')->name('user.destroy');
    Route::post('/user/revoke_invitation', 'UserController@revoke_invitation')->middleware('permission:settings.read');
    Route::post('/user/delete_invitation', 'UserController@delete_invitation')->middleware('permission:settings.read');

    Route::group(['middleware' => ['permission:settings.read']], function () {
        Route::get('/role/paginate', 'RoleController@paginate')->name('role.paginate');
    });
    Route::group(['middleware' => ['permission:settings.create','permission:settings.update']], function () {
        Route::put('/role/clone', 'RoleController@clone')->name('role.clone');
    });

    // Invite firefighter setting
    Route::get('/settings/firefighter', 'FirefighterSettingController@index')->name('firefighter.setting.index');
    Route::get('/settings/firefighter-paginate', 'FirefighterSettingController@paginate')->name('firefighter.setting.paginate');
    Route::get('/settings/invite-firefighter', 'FirefighterSettingController@invite_firefighter')->name('firefighter.setting.invite-firefighter');
    Route::post('/settings/store-invite-firefighter', 'FirefighterSettingController@store_invite_firefighter')->name('firefighter.setting.store-invite-firefighter');
    Route::post('/settings/manage-role-firefighter', 'FirefighterSettingController@manage_role_firefighter')->name('firefighter.setting.manage-role-firefighter');


    /* Roles */
    Route::get('/role/create','RoleController@create')->middleware('permission:settings.create')->name('role.create');
    Route::post('/role','RoleController@store')->middleware('permission:settings.create')->name('role.store');
    Route::get('/role','RoleController@index')->middleware('permission:settings.read')->name('role.index');
    Route::get('/role/{role}','RoleController@show')->middleware('permission:settings.read')->name('role.show');
    Route::get('/role/{role}/edit','RoleController@edit')->middleware('permission:settings.update')->name('role.edit');
    Route::put('/role/{role}','RoleController@update')->middleware('permission:settings.update')->name('role.update');
    Route::patch('/role/{role}','RoleController@update')->middleware('permission:settings.update')->name('role.update');
    Route::delete('/role/{role}','RoleController@destroy')->middleware('permission:settings.delete')->name('role.destroy');

    /* Settings permissions */
    //Route::get('/create_permissions', 'UserController@create_permissions');
    //Route::get('/permissions', 'UserController@permissions');
    //Route::get('/revoke_permissions', 'UserController@revoke_permissions');
});
