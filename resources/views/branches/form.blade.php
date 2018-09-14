<div class="mdl-card__supporting-text" style="padding:0;">
    <fieldset style="padding: 0px 16px 0px;">
        <legend>
            <h5 class="mdl-sub-title">Branch Information</h5>
        </legend>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div id="name-is-found" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('name') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">person</i>
                    {{ Form::text('name', old('name'), array('id' => 'name', 'class' => 'mdl-textfield__input')) }}
                    {{ Form::label('name', 'Name', array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('name'))
                        <span class="mdl-textfield__error">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div id="email-is-not-found" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('email') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">contact_mail</i>
                    {{ Form::text('email', old('email'), array('id' => 'email', 'class' => 'mdl-textfield__input')) }}
                    {{ Form::label('email', trans('auth.email'), array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('email'))
                        <span class="mdl-textfield__error">{{ $errors->first('email') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div id="contact-is-found" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('contact') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">contact_phone</i>
                    {{ Form::text('contact', old('contact'), array('id' => 'contact', 'class' => 'mdl-textfield__input')) }}
                    {{ Form::label('contact', 'Contact', array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('contact'))
                        <span class="mdl-textfield__error">{{ $errors->first('contact') }}</span>
                    @endif
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div id="address-is-found" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('address') ? 'is-invalid' : '' }}">
                    <i class="mdl-textfield__icon material-icons">maps</i>
                    {{ Form::textarea('address', old('address'), array('id' => 'address', 'rows'=>3, 'class' => 'mdl-textfield__input')) }}
                    {{ Form::label('address', 'Address', array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('address'))
                        <span class="mdl-textfield__error">{{ $errors->first('address') }}</span>
                    @endif
                </div>
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
        {!! Form::button('SUBMIT', 
                array('class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary' ,'style' => 'float: right; margin-bottom: 8px;',
                'type' => 'submit','id' => 'submit')) !!}
</div>