@extends('layouts.app')
@section('content')
<div class="mdl-grid">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
        <div class="mTdl-card__title">
            <h3 class="mdl-card__title-text">Edit Member</h3>
        </div>
        {{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT', 'enctype'=>'multipart/form-data')) }}
             @include('users.form',['submitText' => 'Edit Member'])
        {{ Form::close() }}
    </div>
</div>
@endsection