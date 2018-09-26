@extends('layouts.app')

@section('content')
<div class="mdl-grid mt-0 pt-0">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-card__title">
            <h2 class="mdl-card__title-text">Edit Donor</h2>
        </div>
        {{ Form::model($donor, array('route' => array('donors.update', $donor->id), 'method' => 'PUT', 'enctype'=>"multipart/form-data"))}}
        <div class="mdl-card__supporting-text" style="padding:0;">
            <fieldset style="padding: 0px 16px 0px;">
                {{--
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                        <img src="{{Auth::user()->avatar != null ? Storage::url('img/'.Auth::user()->avatar) :  Storage::url('/user.jpg')}}" alt="avatar">
                        <input id="avatar" type="file" class="form-control" name="avatar" accept="image/*">
                    </div>
                </div> --}}
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('name') ? 'is-invalid' :'' }}">
                            <i class="mdl-textfield__icon material-icons">person</i>
                            {{ Form::text('name', $donor->name, array('id' => 'name', 'class' => 'mdl-textfield__input')) }} {{ Form::label('name',
                            'Name', array('class' => 'mdl-textfield__label')) }} @if ($errors->has('name'))
                            <span class="mdl-textfield__error">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('email') ? 'is-invalid' :'' }}">
                            <i class="mdl-textfield__icon material-icons">contact_mail</i>
                            {{ Form::email('email', $donor->email, array('id' => 'email', 'class' => 'mdl-textfield__input')) }} {{ Form::label('email',
                            trans('auth.email'), array('class' => 'mdl-textfield__label')) }} @if ($errors->has('email'))
                            <span class="mdl-textfield__error">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('contact') ? 'is-invalid' :'' }}">
                            <i class="mdl-textfield__icon material-icons">contact_phone</i>
                            {{ Form::text('contact', $donor->contact, array('id' => 'contact', 'class' => 'mdl-textfield__input')) }} {{ Form::label('contact',
                            'Contact', array('class' => 'mdl-textfield__label')) }} @if ($errors->has('contact'))
                            <span class="mdl-textfield__error">{{ $errors->first('contact') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height">
                            <i class="mdl-textfield__icon material-icons">opacity</i>
                            <input type="text" value="" class="mdl-textfield__input" id="blood_type" readonly>
                            <input type="hidden" value="" name="blood_type">
                            <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
                            <label for="blood_type" class="mdl-textfield__label">Blood Type</label>
                            <ul for="blood_type" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                                @foreach ($blood_types as $blood_type)
                                <li class="mdl-menu__item" data-val="{{$blood_type->id}}" {{$donor !=null && ($donor->blood_type_id === $blood_type->id) ? 'data-selected="true"' : ''}}>{{$blood_type->name}}</li>
                                @endforeach {{-- data-selected="true" --}}
                            </ul>
                        </div>
                        @if ($errors->has('blood_type'))
                        <span class="mdl-textfield__error">{{ $errors->first('blood_type') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('health_issues') ? 'is-invalid' :'' }}">
                            <i class="mdl-textfield__icon material-icons">description</i>
                            {{ Form::textarea('health_issues', $donor->health_issues, array('id' => 'health_issues', 'rows'=>1, 'class' => 'mdl-textfield__input'))
                            }} {{ Form::label('health_issues', 'Health Issues', array('class' => 'mdl-textfield__label'))
                            }} @if ($errors->has('health_issues'))
                            <span class="mdl-textfield__error">{{ $errors->first('health_issues') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                        <div id="address-is-found" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('address') ? 'is-invalid' : '' }}">
                            <i class="mdl-textfield__icon material-icons">map</i>
                            {{ Form::textarea('address', $donor->address, array('id' => 'address', 'rows'=>3, 'class' => 'mdl-textfield__input'))
                            }} {{ Form::label('address', 'Address', array('class' => 'mdl-textfield__label')) }} @if ($errors->has('address'))
                            <span class="mdl-textfield__error">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                        <small style="color:red;margin-left: -260px;">*Use google map to add residential places</small>
                    </div>
                </div>
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
                        <div id="address-input-map"></div>
                        <input id="pac-input" type="text" class="map-control" placeholder="Search...">
                        <input id="map_lat" type="hidden" name="map_lat">
                        <input id="map_lng" type="hidden" name="map_lng">
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="mdl-card__actions mdl-card--border">
            {!! Form::button('SUBMIT', array('class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary'
            ,'style' => 'float: right; margin-bottom: 8px;','type' => 'submit','id' => 'submit')) !!}
        </div>
        {{ Form::close() }}
    </div>
</div>
@endsection