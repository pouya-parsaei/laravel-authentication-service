@extends('layouts.layout')

@section('title' , __('public.home'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            @include('partials.alerts')
           @lang('public.home page message')
        </div>
    </div>
@endsection
