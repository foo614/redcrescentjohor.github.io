<div class="mdl-card__supporting-text" style="padding:0;">
    <fieldset style="padding: 0px 16px 0px;">
        <legend>
            <h5 class="mdl-sub-title">Member Information</h5>
        </legend>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('name') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">person</i>
                    {{ Form::text('name', null, array('id' => 'name', 'class' => 'mdl-textfield__input', 'pattern' => '[A-Z,a-z\s]*')) }}
                    {{ Form::label('name', 'Name', array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('name'))
                        <span class="mdl-textfield__error">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('email') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">contact_mail</i>
                    {{ Form::email('email', null, array('id' => 'email', 'class' => 'mdl-textfield__input')) }}
                    {{ Form::label('email', trans('auth.email'), array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('email'))
                        <span class="mdl-textfield__error">{{ $errors->first('email') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('ic') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">credit_card</i>
                    {{ Form::text('ic', null, array('id' => 'ic', 'class' => 'mdl-textfield__input')) }}
                    {{ Form::label('ic', 'Identity Card Number', array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('ic'))
                        <span class="mdl-textfield__error">{{ $errors->first('ic') }}</span>
                    @endif
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('contact') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">contact_phone</i>
                    {{ Form::text('contact', null, array('id' => 'contact', 'class' => 'mdl-textfield__input')) }}
                    {{ Form::label('contact', 'Contact', array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('contact'))
                        <span class="mdl-textfield__error">{{ $errors->first('contact') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="mdl-grid" style="{{Request::route()->getName() ==  'users.edit' ? 'display:none' : ''}}">     
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('password') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">vpn_key</i>
                    {{ Form::password('password', array('id' => 'password', 'class' => 'mdl-textfield__input')) }}
                    {{ Form::label('password', 'Password', array('class' => 'mdl-textfield__label')) }}   
                    @if ($errors->has('password'))
                        <span class="mdl-textfield__error"> {{ $errors->first('password') }}</span>
                    @endif        
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('password_confirmation') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">vpn_key</i>
                    {{ Form::password('password_confirmation', array('id' => 'password_confirmation','class' => 'mdl-textfield__input')) }}
                    {{ Form::label('password', 'Confirm Password', array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('password_confirmation'))
                        <span class="mdl-textfield__error">{{ $errors->first('password_confirmation') }}</span>
                    @endif 
                </div>
            </div>
        </div>  
        <div class="mdl-grid">      
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height">
                    <i class="mdl-textfield__icon material-icons">people</i>
                    <input type="text" value="" class="mdl-textfield__input" id="membership_type" readonly>
                    <input type="hidden" value="" name="membership_type">
                    <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
                    <label for="membership_type" class="mdl-textfield__label">Membership Type</label>
                    <ul for="membership_type" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                        @foreach ($membership_types as $key => $value)
                            <li class="mdl-menu__item" data-val="{{$key}}" data-selected="{{Request::route()->getName() ==  'users.edit' ? ($user->membership_type_id === $key ? true : false) : '' }}">{{$value}}</li>
                        @endforeach
                    </ul>
                </div>
                <span class="mdl-selectfield__error">{{$errors->first('membership_type')}}</span>
            </div> 
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('detachment') ? 'is-invalid' :'' }}">
                    <i class="mdl-textfield__icon material-icons">store_mall_directory</i>
                    {{ Form::text('detachment', null, array('id' => 'detachment', 'class' => 'mdl-textfield__input')) }}
                    {{ Form::label('detachment', 'Detachment', array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('detachment'))
                        <span class="mdl-textfield__error">{{ $errors->first('detachment') }}</span>
                    @endif 
                </div>
            </div>
        </div>  
        <div class="mdl-grid"> 
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height" id="myselect">
                    <i class="mdl-textfield__icon material-icons">home</i>
                    <input type="text" value="" class="mdl-textfield__input" id="branch" readonly>
                    <input type="hidden" value="" name="branch">
                    <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
                    <label for="branch" class="mdl-textfield__label">Branch</label>
                    <ul for="branch" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                        @foreach ($branches as $key => $value)
                        <li class="mdl-menu__item" data-val="{{$key}}" data-selected="{{Request::route()->getName() ==  'users.edit' ? ($user->branch_id === $key ? true : false) : '' }}">{{$value}}</li>
                        @endforeach
                    </ul>
                </div>
                <span class="mdl-selectfield__error">{{$errors->first('branch')}}</span>
            </div>  
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height">
                    <i class="mdl-textfield__icon material-icons">opacity</i>
                    <input type="text" value="" class="mdl-textfield__input" id="blood_type" readonly>
                    <input type="hidden" value="" name="blood_type">
                    <i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
                    <label for="blood_type" class="mdl-textfield__label">Blood Type</label>
                    <ul for="blood_type" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
                        @foreach ($blood_types as $key => $value)
                        <li class="mdl-menu__item" data-val="{{$key}}" data-selected="{{Request::route()->getName() ==  'users.edit' ?  ($user->blood_type_id === $key ? true : false) : '' }}">{{$value}}</li>
                        @endforeach
                    </ul>
                </div>
            </div> 
        </div>
        <div class="mdl-grid">
            <upload-file img-path="{{Request::route()->getName() == 'users.edit' ? '/storage/img/'.$user->avatar : ''}}"></upload-file>
            <div class="mdl-tooltip mdl-tooltip--right mdl-tooltip--large" data-mdl-for="tootip-upload-image">Upload Profile Image</div>
        </div>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
                <div id="address-is-found" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('address') ? 'is-invalid' : '' }}">
                    <i class="mdl-textfield__icon material-icons">map</i>
                    {{ Form::textarea('address', old('address'), array('id' => 'address', 'rows'=>3, 'class' => 'mdl-textfield__input', 'readonly' => 'true')) }}
                    {{ Form::label('address', 'Address', array('class' => 'mdl-textfield__label')) }}
                    @if ($errors->has('address'))
                        <span class="mdl-textfield__error">{{ $errors->first('address') }}</span>
                    @endif
                </div>
                <small style="color:red;margin-left: -260px;">*Use google map to add residential places</small>
                <div id="address-input-map"></div>
                <input id="pac-input" type="text" class="map-control" placeholder="Search...">
                <input id="map_lat" type="hidden" name="map_lat">
                <input id="map_lng" type="hidden" name="map_lng">
            </div>
        </div>
    <legend>
        <h5 class="mdl-sub-title">Roles</h5>
    </legend>
    <div class="mdl-grid">      
        <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
            <ul class="mdl-list mdl-shadow--2dp" style="width: 60%; overflow:hidden auto; height:150px;">
                @foreach ($roles as $key => $value)
                <li class="mdl-list__item">
                    <span class="mdl-list__item-primary-content">
                        <label class='mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect'>
                            {!! Form::checkbox('roles[]',  $key, null, ['multiple' => true, 'class' => 'mdl-checkbox__input']); !!}
                            <span class="mdl-checkbox__label">{{ ucfirst($value) }}</span>
                        </label>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    </fieldset>
</div>
<div class="mdl-card__actions mdl-card--border">
        {!! Form::button($submitText, 
            array('class' => 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary' ,'style' => 'float: right; margin-bottom: 8px;',
            'type' => 'submit','id' => 'submit')) !!}
</div>