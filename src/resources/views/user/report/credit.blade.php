@extends('user.layouts.app')
@section('panel')

    <main class="main-body">
        <div class="container-fluid px-0 main-content">
            <div class="page-header">
                <div class="page-header-left">
                    <h2>{{ $title }}</h2>
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route("user.dashboard") }}">{{ translate("Dashboard") }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="table-filter mb-4">
                <form action="{{route(Route::currentRouteName())}}" class="filter-form">
                    
                    <div class="row g-3">
                        
                        <div class="col-xxl-5 col-lg-7">
                            <div class="filter-action">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="datePicker" name="date" value="{{request()->input('date')}}"  placeholder="{{translate('Filter by date')}}"  aria-describedby="filterByDate">
                                    <span class="input-group-text" id="filterByDate">
                                        <i class="ri-calendar-2-line"></i>
                                    </span>
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <button type="submit" class="filter-action-btn ">
                                        <i class="ri-menu-search-line"></i> {{ translate("Filters") }}
                                    </button>
                                    <a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName())}}">
                                        <i class="ri-refresh-line"></i> {{ translate("Reset") }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-header-left">
                        <h4 class="card-title">{{ translate("SMS Credit Log") }}</h4>
                    </div>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">{{ translate("Date") }}</th>
                                    <th scope="col">{{ translate("Trx ID") }}</th>
                                    <th scope="col">{{ translate("Credit") }}</th>
                                    <th scope="col">{{ translate("Previous Credit") }}</th>
                                    <th scope="col">{{ translate("Details") }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($creditLogs as $creditLog)
                                <tr class="@if($loop->even)@endif">
                                    <td data-label="{{ translate('Date')}}">
                                        <span class="fw-bold">{{diffForHumans($creditLog->created_at)}}</span><br>
                                        {{getDateTime($creditLog->created_at)}}
                                    </td>

                                    <td data-label="{{ translate('Trx ID')}}">
                                        {{$creditLog->trx_number}}
                                    </td>

                                    <td data-label="{{ translate('Credit')}}">
                                        <span class="@if($creditLog->credit_type == \App\Enums\StatusEnum::TRUE->status()) text-success @else text-danger @endif">
                                            {{$creditLog->credit_type == \App\Enums\StatusEnum::TRUE->status() ? '+' : '-'}} {{$creditLog->credit}}</span> {{ translate('credit')}}
                                    </td>

                                    <td data-label="{{ translate('Previous Credit')}}">
                                        {{$creditLog->post_credit}} {{ translate('credit')}}
                                    </td>

                                    <td data-label="{{ translate('Details')}}">
                                        {{$creditLog->details}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ translate('No Data Found')}}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('user.partials.pagination', ['paginator' => $creditLogs])
                </div>
            </div>
        </div>
    </main>
@endsection
@push('script-push')
    <script>
        "use strict";
            flatpickr("#datePicker", {
                dateFormat: "Y-m-d",
                mode: "range",
            });
    </script>
@endpush
