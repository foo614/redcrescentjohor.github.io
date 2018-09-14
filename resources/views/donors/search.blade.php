@extends('layouts.dashboard')

@section('content')
<div class="mdl-grid mt-0 pt-0">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-grid p-0">
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--4-col-desktop">
                <div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label is-dirty">
                    <i class="mdl-textfield__icon material-icons">local_hospital</i>
                    <select id="hospital" name="hospital" class="mdl-selectfield__select">
                        @foreach ($hospitals as $hospital)
                            <option value="{{$hospital->id-1}}">{{$hospital->name}}</option>
                        @endforeach
                    </select>
                    <label for="blood_type" class="mdl-selectfield__label">Hospital</label>
                    <span class="mdl-selectfield__error">Input is not a empty!</span>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--4-col-desktop">
                <div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label is-dirty">
                    <i class="mdl-textfield__icon material-icons" style="line-height: 1;">opacity</i>
                    <select id="blood_type" name="blood_type" class="mdl-selectfield__select">
                        {{-- <option value="">I'm not sure</option> --}}
                        @foreach ($blood_types as $blood_type)
                            <option>{{$blood_type->name}}</option>
                        @endforeach
                    </select>
                    <label for="blood_type" class="mdl-selectfield__label">Blood Type</label>
                    <span class="mdl-selectfield__error">Input is not a empty!</span>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--4-col-desktop">
                <div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label is-dirty">
                    <i class="mdl-textfield__icon material-icons" style="line-height: 1;">360</i>
                    <select id="search_radius" name="search_radius" class="mdl-selectfield__select">
                        @for ($i = 5; $i <= 200; $i += 5)
                            <option value="{{ $i }}">{{ $i }} km</option>
                        @endfor
                    </select>
                    <label for="search_radius" class="mdl-selectfield__label">Search radius</label>
                    <span class="mdl-selectfield__error">Input is not a empty!</span>
                </div>
            </div>
        </div>
        <div class="mdl-grid p-0" style="width:100%">
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--10-col-desktop">
                <div id="find-map" style="height:550px;"></div>
                <div id="legend" class="mdl-card__supporting-text mdl-shadow--2dp" style="background:white;padding: 10px; margin: 10px;"></div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--2-col-desktop">
                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                    <thead><tr><th>Donators</th></tr></thead>
                    <tbody id="results" style="display:block; height:400px; overflow:auto;"></tbody>
                </table>
                <div class=""><a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary mt-3" onclick="sendNotificationSelected(this)">Send Invitation</a></div>
            </div>
        </div>
    </div>
</div>
@endsection
