@extends('layouts.master')

@section('contenido')
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs" role="tablist">
            <li>{{HTML::LinkRoute('user.emails.index',trans('frontend.link.emails_index'),array($user->id,$domain->id))}}</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-sm-6 col-sm-push-3">
            <h2>{{ trans('frontend.title.email.create') }}</h2>

            <div class="instrucciones">
                <p>{{trans('frontend.instructions.email.create')}}</p>
            </div>

            {{ Form::open(array('route' => array('user.emails.store',$user->id,$domain->id))) }}

            @include('layouts.show_form_errors')

            <div class="form-group">                        
                {{ Form::label('email', trans('frontend.label.correo')) }}
                <div class="input-group">
                    {{ Form::text('email', Input::old('email'), array('placeholder' => trans('frontend.placeholder.email'), 'class'=>'form-control')) }}
                    <span class="input-group-addon">{{ '@'.$domain->domain }}</span>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('password', trans('frontend.label.password')) }}
                <div class="input-group">
                    {{ Form::password('password',array('placeholder' => trans('frontend.placeholder.password'), 'class'=>'form-control', 'id'=>'password')) }}
                    <span class="input-group-btn">
                        {{ Form::button(trans('frontend.button.password.generate'),array('class'=>"btn btn-primary",'data-target'=>"#ModalPassword",'on_click'=>'get_password()','data-toggle'=>"modal")) }}                        
                    </span>                  
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('password_confirmation', trans('frontend.label.password_confirmation')) }}
                {{ Form::password('password_confirmation',array('placeholder' => trans('frontend.placeholder.password_confirmation'), 'class'=>'form-control', 'id' => 'confirm_password')) }}
            </div>            
            <div class="form-group">
                {{ Form::label('forward', trans('frontend.label.forward')) }}
                {{ Form::text('forward',Input::old('forward'),array('placeholder' => trans('frontend.placeholder.forward'), 'class'=>'form-control'))}}
            </div>
            <div class="form-group">
                {{Form::submit(trans('frontend.button.emails.create'),array('class'=>"btn btn-primary"))}}                        
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@include('layouts.modal_password')
@stop
