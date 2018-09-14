@extends('layouts.app') 
@section('content')
{{-- <div class="mdl-card mdl-shadow--2dp" style="margin:0 auto; margin-top:30px;">
    <div class="mdl-card__title mdl-card--expand">
        <h2 class="mdl-card__title-text">Login</h2>
    </div>
    <div class="mdl-card__supporting-text">
        {!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => '', 'id' => 'login', 'role' => 'form']) !!} 
        {{ csrf_field() }}
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('email') ? 'is-invalid' :'' }}">
            {!! Form::email('email', null, array('id' => 'email', 'class' => 'mdl-textfield__input')) !!} 
            {!! Form::label('email',trans('auth.email') , array('class' => 'mdl-textfield__label')); !!} 
            @if ($errors->has('email'))
            <span class="mdl-textfield__error">{{$errors->first('email')}}</span>
            @endif
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('password') ? 'is-invalid' :'' }}">
            {!! Form::password('password', array('id' => 'password', 'class' => 'mdl-textfield__input')) !!} 
            {!! Form::label('password',trans('auth.password') , array('class' => 'mdl-textfield__label')); !!} 
            @if ($errors->has('password'))
                <span class="mdl-textfield__error">{{$errors->first('password')}}</span>
            @endif
        </div>
        <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="remember">
            {!! Form::checkbox('remember', 'remember', null, ['id' => 'remember', 'class' => 'mdl-checkbox__input', old('remember') ?
            'checked' : '']); !!}
            <span class="mdl-checkbox__label">{{ trans('auth.rememberMe') }}</span>
        </label>
        {!! Form::button('<span class="mdl-spinner-text">'.trans('auth.login').'</span><div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner mdl-color-text--white mdl-color-white "></div>', array('class' => 'mdl-button mdl-js-button mdl-js-ripple-effect center mdl-color--primary mdl-color-text--white mdl-button--raised full-span margin-bottom-1 margin-top-2','type' => 'submit','id' => 'submit')) !!} 
        {!! Form::close() !!}
    </div>
    <div class="mdl-card__actions mdl-card--border">
        {!! Html::link(route('password.request'), trans('auth.forgot'), array('id' => 'forgot', 'class' => 'mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect float-left')) !!}
    </div>
</div> --}}
<v-layout align-center justify-center>
    <v-flex xs12 sm8 md6>
    {!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => '', 'id' => 'login', 'role' => 'form']) !!} 
    {{ csrf_field() }}
      <v-card class="elevation-12">
        <v-toolbar dark color="red">
          <v-toolbar-title>Login</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
            <v-text-field prepend-icon="email" name="email" label="Email" type="email"></v-text-field>
            @if ($errors->has('email'))
            <div class="v-text-field__details">
                <div class="v-messages theme--light error--text">
                    <div class="v-messages__wrapper">
                        <div class="v-messages__message">{{$errors->first('email')}}</div>
                    </div>
                </div>
            </div>
            @endif
            <v-text-field prepend-icon="lock" :type="show1 ? 'text' : 'password'" @click:append="show1 = !show1" :append-icon="show1 ? 'visibility_off' : 'visibility'" name="password" label="Password" id="password" type="password"></v-text-field>
            @if ($errors->has('password'))
            <div class="v-text-field__details">
                <div class="v-messages theme--light error--text">
                    <div class="v-messages__wrapper">
                        <div class="v-messages__message">{{$errors->first('password')}}</div>
                    </div>
                </div>
            </div>
            @endif
            {{-- <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="remember">
                {!! Form::checkbox('remember', 'remember', null, ['id' => 'remember', 'class' => 'mdl-checkbox__input', old('remember') ?
                'checked' : '']); !!}
                <span class="mdl-checkbox__label">{{ trans('auth.rememberMe') }}</span>
            </label> --}}
            <v-checkbox
              label= {{trans('auth.rememberMe')}}
              color="red"
            >
            {!! Form::checkbox('remember', 'remember', null, ['id' => 'remember', 'class' => 'mdl-checkbox__input', old('remember') ?
                'checked' : '']); !!}
            </v-checkbox>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" dark type="submit">Login</v-btn>
        </v-card-actions>
      </v-card>
    {!! Form::close() !!}
    </v-flex>
  </v-layout>
@endsection