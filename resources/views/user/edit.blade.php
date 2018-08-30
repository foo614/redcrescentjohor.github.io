@extends('layouts.app')
@section('title', 'Member')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit Member</li>
@endsection
@section('content')
<div class="mdl-grid">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-card__title">
            <h3 class="mdl-card__title-text">Edit Member</h3>
        </div>
        {{ Form::model($user, array('route' => array('users.store', $user->id), 'method' => 'POST', 'enctype'=>'multipart/form-data')) }}
            @include('user.form',['submitText' => 'Edit Member'])
        {{ Form::close() }}
    </div>
</div>
@endsection