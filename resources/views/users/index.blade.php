@extends('layouts.app')

@section('title', 'Member')
@section('breadcrumbs')
  <li>
    <a href="{{url('/dashboard')}}">
      <span>
        Home
      </span>
    </a>
    <i class="material-icons">chevron_right</i>
  </li>
  <li>
    <a href="/users">
      <span>
        Members List
      </span>
    </a>
  </li>
@endsection
@section('content')

<div class="mdl-grid mt-0 pt-0">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 p-0">
            <div class="table-responsive material-table">
                <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="mdl-data-table__cell--non-numeric">Name</th>
                            <th class="mdl-data-table__cell--non-numeric">Email</th>
                            <th class="mdl-data-table__cell--non-numeric">Date/Time Added</th>
                            <th class="mdl-data-table__cell--non-numeric">User Roles</th>
                            <th class="mdl-data-table__cell--non-numeric">Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="mdl-data-table__cell--non-numeric">{{ $user->name }}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ $user->email }}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ $user->created_at->format('F d, Y h:ia') }}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ $user->roles()->pluck('name')->implode(', ') }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}
                            <td class="mdl-data-table__cell--non-numeric">
                                <a href="{{ route('users.edit', $user->id) }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" style="margin-right: 3px;">
                                    <i class="material-icons mdl-color-text--orange">edit</i>
                                </a>
                                {{-- {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id] ]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!} --}}

                                {{-- DELETE ICON BUTTON AND FORM CALL --}}
                                {!! Form::open(array('url' => 'users/' . $user->id, 'class' => 'inline-block', 'id' => 'delete_'.$user->id)) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                <a href="#" class="dialog-button dialiog-trigger-delete dialiog-trigger{{$user->id}} mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-userid="{{$user->id}}">
                                    <i class="material-icons mdl-color-text--red">delete</i>
                                </a>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mdl-card__menu" style="top: -4px;">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable search-white">
                <label class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-button--icon" for="search_table" title="Search terms(s)">
                    <i class="material-icons">search</i>
                    <span class="sr-only">Search terms(s)</span>
                </label>
                <div class="mdl-textfield__expandable-holder">
                    <input class="mdl-textfield__input" type="search" id="search_table" placeholder="Search term...">
                    <label class="mdl-textfield__label" for="search_table">
                        Search term...
                    </label>
                </div>
            </div>
            <a href="{{ route('users.create') }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-color-text--white" title="Add New User">
                <i class="material-icons">person_add</i>
                <span class="sr-only">Add New User</span>
            </a>
        </div>
    </div>
</div>
    @include('dialogs.delete')
@endsection

@section('footer_scripts')
    @include('scripts.datatables')
    <script type="text/javascript">
        @foreach ($users as $a_user)
            mdl_dialog('.dialiog-trigger{{$a_user->id}}','','#dialog_delete');
        @endforeach
        var userid;
        $('.dialiog-trigger-delete').click(function(event) {
            event.preventDefault();
            userid = $(this).attr('data-userid');
        });
        $('#confirm').click(function(event) {
            $('form#delete_'+userid).submit();
        });
    </script>
@endsection