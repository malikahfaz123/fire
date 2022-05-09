@extends('layouts.firefighters-app')

@section('content')
<div class="pl-3">
    <div class="page-title">
        <h3>Dashboard</h3>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12 p-3">
            @include('partials.dashboard-card',['bg_class'=>"bg-info", 'title'=>"Today's Classes",'icon'=>'menu_book','count'=>$today_classes->count,'link'=>route('firefighters.today-classes'),'link_text'=>'View All'])
        </div>
    
        <div class="col-md-3 col-sm-6 col-12 p-3">
            @include('partials.dashboard-card',['bg_class'=>"bg-purple",'title'=>"Tomorrow's Classes",'icon'=>'menu_book','count'=>$tomorrow_classes->count,'link'=>route('firefighters.tomorrow-classes'),'link_text'=>'View All'])
        </div>

        <div class="col-md-3 col-sm-6 col-12 p-3">
            @include('partials.dashboard-card',['bg_class'=>"bg-success",'title'=>"Yesterday's Classes",'icon'=>'menu_book','count'=>$yesterday_classes->count,'link'=>route('firefighters.yesterday-classes'),'link_text'=>'View All'])
        </div>

        <div class="col-md-3 col-sm-6 col-12 p-3">
            @include('partials.dashboard-card',['bg_class'=>"bg-warning",'title'=>"My Courses",'icon'=>'school','count'=>$firefighter_courses->count,'link'=>route('firefighters.my-courses.index'),'link_text'=>'View All'])
        </div>

        <div class="col-md-3 col-sm-6 col-12 p-3">
            @include('partials.dashboard-card',['bg_class'=>"bg-primary",'title'=>"Approved Credentials",'icon'=>'folder_special','count'=>$approved_certificates->count,'link'=>route('firefighters.approved-certificates.index'),'link_text'=>'View All'])
        </div>
    
        <div class="col-md-3 col-sm-6 col-12 p-3">
            @include('partials.dashboard-card',['bg_class'=>"bg-secondary",'title'=>"Awarded Credentials",'icon'=>'folder_special','count'=>$awarded_certificates->count,'link'=>route('firefighters.awarded-certificates.index'),'link_text'=>'View All'])
        </div>
    </div>
</div>
@endsection
