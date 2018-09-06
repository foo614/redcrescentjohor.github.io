@extends('layouts.app')
@section('title', 'Member')
@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
  <li class="breadcrumb-item active" aria-current="page">Members List</li>
@endsection
@section('content')
<div class="mdl-grid">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-card__supporting-text mdl-color-text--grey-600" style="padding:0;">
        <blood-type></blood-type>
        </div>
    </div>
</div>
@endsection
@section('footer-scripts')
    @include('scripts.datatables')
@endsection