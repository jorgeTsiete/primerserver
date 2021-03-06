@extends('layouts.master')

@section('contenido')
<div class="container">      
      <div class="row">
            @include('layouts.menu', ['page' => 'databases'])
            <div class="col-md-8 sidebar contenido list-table">
                  <h2>{{ trans('frontend.title.database.create') }}</h2>

                  <div class="instrucciones">
                        <p>{{trans('frontend.instruction.database.create')}}</p>
                  </div>

                  {{ Form::open(array('route' => array('user.databases.store',$user->id,$domain->id))) }}

                  @include('layouts.show_form_errors')

                  <div class="form-group">
                        {{ Form::label('name_db', trans('frontend.label.name_db')) }}
                        <div class="input-group">                    
                              <span class="input-group-addon">{{ $domain->server->nameserver."_" }}</span>
                              {{ Form::text('name_db',Input::old('name_db'),array('placeholder' => trans('frontend.placeholder.name_db'), 'class'=>'form-control'))}}
                        </div>
                  </div>
                  <div class="form-group">
                        {{ Form::label('user', trans('frontend.label.user')) }}
                        <div class="input-group">                    
                              <span class="input-group-addon">{{ $domain->server->nameserver."_" }}</span>
                              {{ Form::text('user',Input::old('user'),array('placeholder' => trans('frontend.placeholder.user'), 'class'=>'form-control'))}}
                        </div>
                  </div>
                  <div class="form-group">
                        {{ Form::label('password', trans('frontend.label.password')) }}
                        <div class="input-group">
                              {{ Form::password('password',array('placeholder' => trans('frontend.placeholder.password'), 'class'=>'form-control', 'id'=>'password')) }}
                              <span class="input-group-btn">
                                    {{ Form::button(trans('frontend.button.modal_password.generate_new'),array('class'=>"btn btn-primary",'data-target'=>"#ModalPassword",'onclick'=>'get_password()','data-toggle'=>"modal")) }}                        
                              </span>                  
                        </div>
                  </div>
                  <div class="form-group">
                        {{ Form::label('password_confirmation', trans('frontend.label.password_confirmation')) }}
                        {{ Form::password('password_confirmation',array('placeholder' => trans('frontend.placeholder.password_confirmation'), 'class'=>'form-control', 'id' => 'password_confirmation')) }}
                  </div>                        
                  <div class="form-group">
                        {{Form::submit(trans('frontend.button.database.store.submit'),array('class'=>"btn btn-primary"))}}                        
                  </div>
                  {{ Form::close() }}
            </div>
      </div>
</div>
@include('layouts.modal_password')
@stop
