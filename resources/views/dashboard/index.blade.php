@extends('layouts.app',['title'=>$title])
@section('content')
    <div class="pl-3">
        <div class="page-title">
            <h3>Dashboard</h3>
        </div>
        <div class="row">
            @if($user->can('courses.read'))
                <div class="col-md-3 col-sm-6 col-12 p-3">
                    @include('partials.dashboard-card',['bg_class'=>"bg-info", 'title'=>"Today's Classes",'icon'=>'menu_book','count'=>$today_classes->count,'link'=>route('dashboard.today-classes'),'link_text'=>'View All'])
                </div>
                <div class="col-md-3 col-sm-6 col-12 p-3">
                    @include('partials.dashboard-card',['bg_class'=>"bg-purple",'title'=>"Tomorrow's Classes",'icon'=>'menu_book','count'=>$tomorrow_classes->count,'link'=>route('dashboard.tomorrow-classes'),'link_text'=>'View All'])
                </div>
                <div class="col-md-3 col-sm-6 col-12 p-3">
                    @include('partials.dashboard-card',['bg_class'=>"bg-success",'title'=>"Yesterday's Classes",'icon'=>'menu_book','count'=>$yesterday_classes->count,'link'=>route('dashboard.yesterday-classes'),'link_text'=>'View All'])
                </div>
            @endif
            @if($user->can('certifications.read'))
                <div class="col-md-3 col-sm-6 col-12 p-3">
                    @include('partials.dashboard-card',['bg_class'=>"bg-warning",'title'=>"Renewal of Credentials",'icon'=>'folder_special','count'=>$expired_certifications->count(),'link'=>route('dashboard.renewal-certifications'),'link_text'=>'View All'])
                </div>
            @endif
            @if($user->can('firefighters.read'))
                <div class="col-md-3 col-sm-6 col-12 p-3">
                    @include('partials.dashboard-card',['bg_class'=>"bg-danger",'title'=>"Personnel",'icon'=>'person','count'=>$firefighters->count,'link'=>route('firefighter.index'),'link_text'=>'View All'])
                </div>
            @endif
            @if($user->can('courses.read'))
                <div class="col-md-3 col-sm-6 col-12 p-3">
                    @include('partials.dashboard-card',['bg_class'=>"bg-primary",'title'=>"Courses",'icon'=>'school','count'=>$courses->count,'link'=>route('course.create'),'link_text'=>'Add Courses'])
                </div>
            @endif
            @if($user->can('certifications.read'))
                <div class="col-md-3 col-sm-6 col-12 p-3">
                    @include('partials.dashboard-card',['bg_class'=>"bg-secondary",'title'=>"Credentials",'icon'=>'folder_special','count'=>$certifications->count,'link'=>route('certification.create'),'link_text'=>'Add Credentials'])
                </div>
            @endif
        </div>
    </div>
@endsection