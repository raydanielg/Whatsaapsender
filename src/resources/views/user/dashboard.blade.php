@extends('user.layouts.app')
@section('panel')
    @php
        $plan_access = planAccess(auth()->user());
    @endphp
    <main class="main-body">
        <div class="container-fluid px-0 main-content">
            <div class="page-header">
                <div class="page-header-left">
                    <h2>{{ $title }} </h2>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-xl-4 col-md-6">
                    <div class="card credit-card">
                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                            <div class="credit-card-left">
                                <div class="credit-count">
                                    <span class="fs-3 text-info">
                                        <i class="ri-message-2-line"></i>
                                    </span>
                                    @if(@$plan_access['sms']['is_allowed'] || @$plan_access['android']['is_allowed'])

                                        <h6>{{ auth()->user()->sms_credit == -1 ? translate('Unlimited') : formatNumber(auth()->user()->sms_credit) ?? translate('N\A') }}</h6>
                                    @else
                                        <h6>{{ translate("Disabled") }}</h6>
                                    @endif
                                </div>
                                <p>{{ translate('SMS Credit') }}</p>
                            </div>
                            <div>
                                <a href="{{ route('user.plan.create') }}" class="i-btn btn--primary btn--md">
                                    {{ translate('Buy Credit') }} </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card credit-card">
                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                            <div class="credit-card-left">
                                <div class="credit-count">
                                    <span class="fs-3 text-danger">
                                        <i class="ri-mail-line"></i>
                                    </span>
                                    @if(@$plan_access['email']['is_allowed'])
                                        <h6>{{ auth()->user()->email_credit == -1 ? translate('Unlimited') : formatNumber(auth()->user()->email_credit) ?? translate('N\A') }}</h6>
                                    @else
                                        <h6>{{ translate("Disabled") }}</h6>
                                    @endif
                                </div>
                                <p>{{ translate('Email Credit') }}</p>
                            </div>
                            <div>
                                <a href="{{ route('user.plan.create') }}" class="i-btn btn--primary btn--md">
                                    {{ translate('Buy Credit') }} </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card credit-card">
                        <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-4">
                            <div class="credit-card-left">
                                <div class="credit-count">
                                    <span class="fs-3 text-success">
                                        <i class="ri-whatsapp-line"></i>
                                    </span>
                                    @if(@$plan_access['whatsapp']['is_allowed'])
                                        <h6>{{ auth()->user()->whatsapp_credit == -1 ? translate('Unlimited') : formatNumber(auth()->user()->whatsapp_credit) ?? translate('N\A') }}</h6>
                                    @else
                                        <h6>{{ translate("Disabled") }}</h6>
                                    @endif
                                </div>
                                <p>{{ translate('Whatsapp Credit') }}</p>
                            </div>
                            <div>
                                <a href="{{ route('user.plan.create') }}" class="i-btn btn--primary btn--md">
                                    {{ translate('Buy Credit') }} </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="row g-4">
                        <div class="col-xxl-10 order-lg-1 order-xxl-0">
                            <div class="row g-4">
                                <div class="col-xxl-4 col-xl-4">
                                    <div class="card feature-card">
                                        <div class="card-header pb-0">
                                            <div class="card-header-left">
                                                <h4 class="card-title">{{ translate('SMS Statistics') }}</h4>
                                            </div>
                                            <div class="card-header-right">
                                                <span class="fs-3 text-info">
                                                    <i class="ri-message-2-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-primary">
                                                                <i class="ri-message-2-line"></i>
                                                            </span>
                                                            <small>{{ translate('Total') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['sms']['all']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-success">
                                                                <i class="ri-mail-check-line"></i>
                                                            </span>
                                                            <small>{{ translate('Success') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['sms']['success']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-warning">
                                                                <i class="ri-hourglass-fill"></i>
                                                            </span>
                                                            <small>{{ translate('Pending') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['sms']['pending']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-danger">
                                                                <i class="ri-mail-close-line"></i>
                                                            </span>
                                                            <small>{{ translate('Failed') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['sms']['failed']) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-xl-4">
                                    <div class="card feature-card">
                                        <div class="card-header pb-0">
                                            <div class="card-header-left">
                                                <h4 class="card-title">{{ translate('Email Statistics') }}</h4>
                                            </div>
                                            <div class="card-header-right">
                                                <span class="fs-3 text-danger">
                                                    <i class="ri-mail-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-primary">
                                                                <i class="ri-mail-line"></i>
                                                            </span>
                                                            <small>{{ translate('Total') }}</small>
                                                        </div>

                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['email']['all']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-success">
                                                                <i class="ri-mail-check-line"></i>
                                                            </span>
                                                            <small>{{ translate('Success') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['email']['success']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-warning">
                                                                <i class="ri-hourglass-fill"></i>
                                                            </span>
                                                            <small>{{ translate('Pending') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['email']['pending']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-danger">
                                                                <i class="ri-mail-close-line"></i>
                                                            </span>
                                                            <small>{{ translate('Failed') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['email']['failed']) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-xl-4">
                                    <div class="card feature-card">
                                        <div class="card-header pb-0">
                                            <div class="card-header-left">
                                                <h4 class="card-title">{{ translate('Whatsapp Statistics') }}</h4>
                                            </div>
                                            <div class="card-header-right">
                                                <span class="fs-3 text-success">
                                                    <i class="ri-whatsapp-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-primary">
                                                                <i class="ri-whatsapp-line"></i>
                                                            </span>
                                                            <small>{{ translate('Total') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['whats_app']['all']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-success">
                                                                <i class="ri-mail-check-line"></i>
                                                            </span>
                                                            <small>{{ translate('Success') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['whats_app']['success']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-warning">
                                                                <i class="ri-hourglass-fill"></i>
                                                            </span>
                                                            <small>{{ translate('Pending') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['whats_app']['pending']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="feature-status">
                                                        <div class="feature-status-left">
                                                            <span class="feature-icon text-danger">
                                                                <i class="ri-mail-close-line"></i>
                                                            </span>
                                                            <small>{{ translate('Failed') }}</small>
                                                        </div>
                                                        <p class="feature-status-count">
                                                            {{ formatNumber($logs['whats_app']['failed']) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-2">
                            <div class="membership-card card-height-100">
                                <div class="membership-content">
                                    <h5>{{ translate('WhatsApp Cloud API') }}</h5>
                                    <p> {{ translate('As an alternative solution to the WhatsApp Node Modules, you can try out our cloud api system') }}
                                    </p>
                                </div>
                                <a href="#" class="membership-btn">{{ translate('Try now') }}</a>
                                <span class="membership-shape">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="101" height="110"
                                        viewBox="0 0 101 110" fill="none">
                                        <circle cx="99.6525" cy="127.091" r="42.5448"
                                            transform="rotate(-64.4926 99.6525 127.091)"
                                            fill="{{ site_settings('trinary_color') }}" fill-opacity="0.4" />
                                        <circle cx="92.0618" cy="89.32" r="67"
                                            transform="rotate(-64.4926 92.0618 89.32)"
                                            fill="{{ site_settings('trinary_color') }}" fill-opacity="0.4" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xxl-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h4 class="card-title">{{ translate('Latest Credit Log') }}</h4>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0">
                            <div class="table-container">
                                <div class="default_table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>{{ translate('Date') }}</th>
                                                <th>{{ translate('Trx Number') }}</th>
                                                <th>{{ translate('Channel') }}</th>
                                                <th>{{ translate('Previous Credit') }}</th>
                                                <th>{{ translate('Credit') }}</th>
                                            </tr>
                                        </thead>
                                        @forelse($credits as $credit_data)
                                            <tr class="@if ($loop->even) @endif">
                                                <td data-label="{{ translate('Date') }}">
                                                    <span>{{ diffForHumans($credit_data->created_at) }}</span><br>
                                                    {{ getDateTime($credit_data->created_at) }}
                                                </td>

                                                <td data-label="{{ translate('Trx Number') }}">
                                                    {{ $credit_data->trx_number }}
                                                </td>
                                                
                                                <td data-label="{{ translate('Channel') }}">
                                                    <span
                                                        class="i-badge {{ $credit_data->type == \App\Enums\ServiceType::SMS->value ? 'info-soft' : ($credit_data->type == \App\Enums\ServiceType::WHATSAPP->value ? 'success-soft' : 'warning-soft') }}">{{ ucfirst(strtolower(\App\Enums\ServiceType::keyVal((int)$credit_data->type))) }}
                                                    </span>
                                                </td>
                                                <td data-label="{{ translate('Previous Credit') }}">
                                                    {{ $credit_data->post_credit }} {{ translate('Credit') }}
                                                </td>
                                                <td data-label="{{ translate('Credit') }}">
                                                    <span
                                                        class="i-badge {{ $credit_data->credit_type == \App\Enums\StatusEnum::TRUE->status() ? 'success-soft' : 'danger-soft' }}">{{ $credit_data->credit_type == \App\Enums\StatusEnum::TRUE->status() ? '+' : '-' }}
                                                        {{ $credit_data->credit }}
                                                    </span>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-muted text-center" colspan="100%">
                                                    {{ translate('No Data Found') }}</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <h4 class="card-title">{{ translate('Latest Transactions Log') }}</h4>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0">
                            <div class="table-container">
                                <div class="default_table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>{{ translate('Date') }}</th>
                                                <th>{{ translate('Trx Number') }}</th>
                                                <th>{{ translate('Amount') }}</th>
                                                <th>{{ translate('Detail') }}</th>
                                            </tr>
                                        </thead>
                                        @forelse($transactions as $transaction)
                                            <tr class="@if ($loop->even) @endif">
                                                <td data-label="{{ translate('Date') }}">
                                                    <span>{{ diffForHumans($transaction->created_at) }}</span><br>
                                                    {{ getDateTime($transaction->created_at) }}
                                                </td>

                                                <td data-label="{{ translate('Trx Number') }}">
                                                    {{ $transaction->transaction_number }}
                                                </td>

                                                <td data-label="{{ translate('Amount') }}">
                                                    <span
                                                        class="i-badge @if ($transaction->transaction_type == '+') success-soft @else danger-soft @endif">{{ $transaction->transaction_type }}
                                                        {{ shortAmount($transaction->amount) }}
                                                    </span>
                                                </td>

                                                <td data-label="{{ translate('Details') }}">
                                                    {{ $transaction->details }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-muted text-center" colspan="100%">
                                                    {{ translate('No Data Found') }}</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
