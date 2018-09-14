@extends('layouts.dashboard')

@section('title', 'Donor')
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
    <a href="/donors">
      <span>
        Donators List
      </span>
    </a>
  </li>
@endsection
@section('content')

<div class="mdl-grid mt-0 pt-0">
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
            <h3 class="mdl-card__title-text logo-style" style="font-size:20px !important;">
                @if ($totalUsers === 1)
                    {{ $totalUsers }} Donators total
                @elseif ($totalUsers > 1)
                    {{ $totalUsers }} Total Donators
                @else
                    No User found
                @endif
            </h3>
        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600 p-0">
            <div class="table-responsive material-table">
                <table id="user_table" class="mdl-data-table mdl-js-data-table data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="mdl-data-table__cell--non-numeric">Name</th>
                            <th class="mdl-data-table__cell--non-numeric">Email</th>
                            <th class="mdl-data-table__cell--non-numeric">Contact</th>
                            <th class="mdl-data-table__cell--non-numeric">IC</th>
                            <th class="mdl-data-table__cell--non-numeric">Blood Type</th>
                            <th class="mdl-data-table__cell--non-numeric">Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donors as $donor)
                        <tr>
                            <td class="mdl-data-table__cell--non-numeric">{{ $donor->user->name }}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ $donor->user->email }}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ $donor->user->contact}}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ $donor->user->ic}}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ $donor->bloodType->name}}</td>
                            <td class="mdl-data-table__cell--non-numeric">
                                <a href="{{ route('donors.edit', $donor->id) }}" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" style="margin-right: 3px;">
                                    <i class="material-icons mdl-color-text--orange">edit</i>
                                </a>
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
        </div>
    </div>
</div>
    @include('dialogs.delete')
@endsection
@section('footer_scripts')
    @include('scripts.datatables')
@endsection