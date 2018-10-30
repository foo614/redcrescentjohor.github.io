@extends('layouts.home') 
@section('content')
  <router-view name="socialLogin"></router-view> 
@endsection
{{-- <v-icon>fab fa-facebook-square</v-icon>
<v-icon>fab fa-google-plus-square</v-icon> --}}
<!-- <a href="/social/login">Login</a> -->
    <!-- <a href="{{ url('/redirect/google') }}" class="social-button--google">
        <i class="social-icon--google fa fa-google"></i>
        <span class="social-text">
            Sign in with google
        </span>
    </a>
    <a href="{{ url('/redirect/facebook') }}" class="social-button--facebook">
    <i class="social-icon--facebook fa fa-facebook"></i>
    <span class="social-text">
        Sign in with facebook
    </span>
    </a> -->