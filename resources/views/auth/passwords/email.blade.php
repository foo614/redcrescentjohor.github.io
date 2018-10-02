@extends('layouts.app')

@section('content')
@if (session('status'))
<v-alert
    :value="true"
    type="success"
    >
    {{ session('status') }}
</v-alert>
@endif
<v-layout align-center justify-center>
    <v-flex xs12 sm8 md6>
        <form method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}
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
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red" dark type="submit">Send Password Reset Link</v-btn>
                </v-card-actions>
        </v-card>
    </form>
    </v-flex>
</v-layout>
@endsection
