@extends('layouts.app')
@section('content')
{{-- <div class="mdl-grid">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-card__title">
            <h3 class="mdl-card__title-text">Add Member</h3>
        </div>
        {{ Form::open(array('url' => 'users', 'files' => true, 'enctype'=>'multipart/form-data')) }}
            @include('users.form',['submitText' => 'Add Member'])
        {{ Form::close() }}
    </div>
</div> --}}
<router-view name="createUser"></router-view>
<router-view></router-view>
@endsection