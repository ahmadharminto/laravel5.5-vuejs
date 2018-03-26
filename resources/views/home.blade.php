@extends('layouts.app')

@section('content')
    <keep-alive :exclude="['task', 'profile']">
        <router-view></router-view>
    </keep-alive>
@endsection
