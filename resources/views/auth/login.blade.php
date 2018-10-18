@extends('layouts.app')
@section('content')
<v-container fluid fill-height>
    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
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
                    <v-text-field prepend-icon="lock" name="password" label="Password" id="password" type="password"></v-text-field>
                    @if ($errors->has('password'))
                    <div class="v-text-field__details">
                        <div class="v-messages theme--light error--text">
                            <div class="v-messages__wrapper">
                                <div class="v-messages__message">{{$errors->first('password')}}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </v-card-text>
                <v-card-actions>
                    <v-btn color="red" dark href="{{ url('/password/reset') }}">Forgot password?</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark type="submit">Login</v-btn>
                </v-card-actions>
            </v-card>
            {!! Form::close() !!}
        </v-flex>
    </v-layout>
</v-container>
@endsection