@extends('layouts.layout')

@section('title' , __('auth.two factor insert code'))


@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            @include('partials.alerts')
            <div class="card">
                <div class="card-header">
                    @lang('auth.two factor insert code')
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('auth.two.factor.code') }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="code">@lang('auth.two factor code')</label>
                            <div class="col-sm-9">
                                <input type="code" name="code" class="form-control" id="code"
                                       placeholder="@lang('auth.enter your code')">
                            </div>
                        </div>

                        <div class="offset-sm-3">
                            @include('partials.validation-errors')
                        </div>
                        <button type="submit" class="btn btn-primary">@lang('buttons.submit')</button>
                        <a class="small ml-2" href="{{route('auth.two.factor.resend')}}">@lang('auth.did not get code')</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
