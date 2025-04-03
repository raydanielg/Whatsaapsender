


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
                <form action="{{route(Route::currentRouteName(), ['type' => $type])}}" class="filter-form">
                    
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="filter-search">
                                <input type="search" value="{{request()->specific_search}}" name="specific_search" class="form-control" id="filter-search" placeholder="{{ translate("Filter by Campaign Or Contact") }}" />
                                <span><i class="ri-search-line"></i></span>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-lg-8 offset-xxl-2">
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
                                    <a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName() , ['type' => $type])}}">
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
                        <h4 class="card-title">{{ $title }}</h4>
                    </div>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">{{ translate("User") }}</th>
                                    <th scope="col">{{ translate("Camapaign") }}</th>
                                    <th scope="col">{{ translate("Contact Information") }}</th>
                                    <th scope="col">{{ translate("Unsubscribed At") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unsubscriptions as $unsubscription)
                                    <tr class="@if($loop->even)@endif">
                                        <td data-label="{{ translate('User')}}">
                                            @if($unsubscription->user_id)
                                                <a href="{{route('admin.user.details', $unsubscription->user_id)}}" class="fw-bold text-dark">{{$unsubscription->user?->email}}</a>
                                            @else
                                                <p class="fw-bold text-dark">{{translate("Admin")}}</p>
                                            @endif
                                        </td>
                                        <td data-label="{{ translate('Camapaign')}}">
                                            {{$unsubscription->campaign?->name ?? translate("N\A")}}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 ">
                                                <div class="lh-1">
                                                    <p class="text-dark fs-10 fw-medium mb-1">{{ translate('First Name: ') }} {{ $unsubscription->contact?->first_name ?? translate('N/A') }}</p>
                                                    @if($type == (string)\App\Enums\ServiceType::EMAIL->value) 
                                                        <p class="text-dark fs-10 fw-medium mb-1">{{ translate('Email Address: ') }} {{ $unsubscription->contact?->email_contact ?? translate('N/A') }}</p>
                                                    @endif
                                                    @if($type == (string)\App\Enums\ServiceType::WHATSAPP->value) 
                                                        <p class="text-dark fs-10 fw-medium mb-1">{{ translate('WhatsApp Contact: ') }} {{ $unsubscription->contact?->whatsapp_contact ?? translate('N/A') }}</p>
                                                    @endif
                                                    @if($type == (string)\App\Enums\ServiceType::SMS->value) 
                                                        <p class="text-dark fs-10 fw-medium mb-1">{{ translate('SMS Contact: ') }} {{ $unsubscription->contact?->sms_contact ?? translate('N/A') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="{{ translate('Unsubscribed At')}}">
                                            <span class="fw-bold">{{diffForHumans($unsubscription->created_at)}}</span><br>
                                            {{getDateTime($unsubscription->created_at)}}
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
                    @include('admin.partials.pagination', ['paginator' => $unsubscriptions])
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