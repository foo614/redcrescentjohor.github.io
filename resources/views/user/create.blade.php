@extends('layouts.app')
@section('title', 'Member')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
  <li class="breadcrumb-item active" aria-current="page">Add Member</li>
@endsection
@section('content')
<div class="mdl-grid">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-card__title">
            <h3 class="mdl-card__title-text">Add Member</h3>
        </div>
        {{ Form::open(array('url' => 'users', 'files' => true, 'enctype'=>'multipart/form-data')) }}
            @include('user.form',['submitText' => 'Add Member'])
        {{ Form::close() }}
    </div>
</div>
@endsection