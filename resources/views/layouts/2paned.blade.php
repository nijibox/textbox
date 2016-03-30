@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @yield('content.title')
    </div>
    <div class="row">
        <div class="col-xs-9">
            @yield('content.main')
        </div>
        <div class="col-xs-3">
            @yield('content.sub')
        </div>
    </div>
</div>
@endsection
