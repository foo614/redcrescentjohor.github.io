@extends('layouts.app')

@section('content')
<v-layout align-center justify-center>
    <v-flex xs12 sm8 md6>
        <form method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <v-card class="elevation-12">
            <v-toolbar dark color="red">
                <v-toolbar-title>Reset Password</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-text-field prepend-icon="email" name="email" label="Email" type="email" value="{{ old('email') }}" required></v-text-field>
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

                <v-text-field prepend-icon="lock" name="password_confirmation" label="Confirm Password" id="password-confirm" type="password"></v-text-field>
                @if ($errors->has('password_confirmation'))
                <div class="v-text-field__details">
                    <div class="v-messages theme--light error--text">
                        <div class="v-messages__wrapper">
                            <div class="v-messages__message">{{$errors->first('password_confirmation')}}</div>
                        </div>
                    </div>
                </div>
                @endif
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="red" dark type="submit">Reset Password</v-btn>
            </v-card-actions>
        </v-card>
    </form>
    </v-flex>
</v-layout>
@endsection