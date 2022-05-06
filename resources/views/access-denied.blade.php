@auth
    @extends('layouts.app',['title'=>'Access denied !'])
    @section('content')
        <div class="text-center">
            <h1>Access denied !</h1>
            <h4>Sorry, you don't have the permission to access this page.</h4>
        </div>
    @endsection
@else

@endauth