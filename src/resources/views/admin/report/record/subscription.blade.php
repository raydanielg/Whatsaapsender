@extends('admin.layouts.app')
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
                                <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
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
                    <div class="col-lg-4">
                        <div class="filter-search">
                            <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by user, plan, trx code or amount") }}" />
                            <span><i class="ri-search-line"></i></span>
                        </div>
                    </div>

                    <div class="col-xxl-5 col-lg-7 offset-xxl-3">
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
                    <h4 class="card-title">{{ translate("Subscription History") }}</h4>
                </div>
            </div>
            <div class="card-body px-0 pt-0">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">{{ translate("Date") }}</th>
                                <th scope="col">{{ translate("User") }}</th>
                                <th scope="col">{{ translate("Plan") }}</th>
                                <th scope="col">{{ translate("Amount") }}</th>
                                <th scope="col">{{ translate("Trx Number") }}</th>
                                <th scope="col">{{ translate("Expired Date") }}</th>
                                <th scope="col">{{ translate("Status") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $subscription)
                                <tr class="@if($loop->even)@endif">
                                    <td data-label="{{ translate('Date')}}">
                                        <span class="fw-bold">{{diffForHumans($subscription->created_at)}}</span><br>
                                        {{getDateTime($subscription->created_at)}}
                                    </td>

                                    <td data-label="{{ translate('User')}}">
                                        <a href="{{route('admin.user.details', $subscription->user_id)}}" class="text-dark fw-semibold">{{@$subscription->user->email}}</a>
                                    </td>

                                    <td data-label="{{ translate('Plan')}}">
                                        {{optional($subscription->plan)->name ?? 'N\A'}}
                                    </td>

                                    <td data-label="{{ translate('Amount')}}">
                                        <span>{{shortAmount($subscription->amount)}} {{getDefaultCurrencyCode(json_decode(site_settings('currencies'), true))}}</span>
                                    </td>

                                    <td data-label="{{ translate('Trx Number')}}">
                                        {{$subscription->trx_number}}
                                    </td>

                                     <td data-label="{{ translate('Expired Date')}}">
                                        {{getDateTime($subscription->expired_date)}}
                                    </td>

                                    <td data-label="{{ translate('Status')}}">
                                        @if($subscription->status == 1)
                                            <span class="i-badge dot success-soft pill">{{ translate('Active')}}</span>
                                        @elseif($subscription->status == 2)
                                            <span class="i-badge dot warning-soft pill">{{ translate('Expired')}}</span>
                                        @else
                                            <span class="i-badge dot danger-soft pill">{{ translate('Inactive')}}</span>

                                        @endif

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
                @include('admin.partials.pagination', ['paginator' => $subscriptions])
            </div>
        </div>
    </div>
</main>
@endsection

@push('script-push')
<script>
	(function($){
		"use strict";

        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });
    })(jQuery);
</script>
@endpush
