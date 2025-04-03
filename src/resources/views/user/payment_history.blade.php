@extends('user.layouts.app')
@section('panel')
<section>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ translate('Payment History')}}</h4>
        </div>
        <div class="card-filter">
            <form action="{{route('user.payment.search')}}" method="GET">
                <div class="filter-form">
                    <div class="filter-item">
                        <input type="text" autocomplete="off" name="search"  placeholder=" {{ translate('Search by trxid')}}" class="form-control" id="search" value="{{@$search}}">
                    </div>

                    <div class="filter-item">

                        <input type="text" class="form-control datepicker-here" name="date" value="{{@$searchDate}}" data-range="true" data-multiple-dates-separator=" - " data-language="en" data-position="bottom right" autocomplete="off" placeholder=" {{ translate('From Date-To Date')}}" id="date">
                    </div>

                    <div class="filter-action">
                        <button class="i-btn primary--btn btn--md" type="submit">
                            <i class="fas fa-search"></i>  {{translate('Search')}}
                        </button>
                        <button class="i-btn danger--btn btn--md">
                            <a class="text-white" href="{{ route('user.payment.history') }}">
                                <i class="las la-sync"></i>  {{translate('reset')}}
                            </a>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body px-0">
            <div class="responsive-table">
                <table>
                    <thead>
                        <tr>
                            <th> {{ translate('Date')}}</th>
                            <th> {{ translate('Trx Number')}}</th>
                            <th> {{ translate('Amount')}}</th>
                            <th> {{ translate('Status')}}</th>
                            <th> {{ translate('Action')}}</th>
                        </tr>
                    </thead>
                    @forelse($paymentLogs as $payment)
                        <tr class="@if($loop->even)@endif">
                            <td data-label=" {{ translate('Date')}}">
                                <span>{{diffForHumans($payment->created_at)}}</span><br>
                                {{getDateTime($payment->created_at)}}
                            </td>

                            <td data-label=" {{ translate('Trx Number')}}">
                                {{$payment->trx_number}}
                            </td>

                            <td data-label=" {{ translate('Amount')}}">
                                {{shortAmount($payment->amount)}} {{$general->currency_name}}

                            </td>

                            <td data-label="{{ translate('Status')}}">
                                @if($payment->status == 1)
                                    <span class="badge badge--primary">{{ translate('Pending')}}</span>
                                @elseif($payment->status == 2)
                                    <span class="badge badge--success">{{ translate('Received')}}</span>
                                @elseif($payment->status == 3)
                                    <span class="badge badge--danger">{{ translate('Rejected')}}</span>
                                @else
                                    <span class="badge badge--info">{{ translate('Initiated')}}</span>
                                @endif
                            </td>
                            <td data-label={{ translate('Action')}}>
                                <div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">

                                    @if( $payment->feedback != null)

                                        <a class="i-btn primary--btn btn--sm details"
                                            data-message="{{$payment->feedback}}"
                                            data-bs-placement="top" title="feedback"
                                            data-bs-toggle="modal"
                                            data-bs-target="#paymentFeedback"><i class='las la-desktop'></i>
                                        </a>
                                    @else
                                        <p>{{ translate("N\A") }}</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center" colspan="100%"> {{ translate('No Data Found')}}</td>
                        </tr>
                    @endforelse
                </table>
            </div>
            <div class="m-3">
                {{$paymentLogs->links()}}
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="paymentFeedback" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ translate('Feedback')}}</h5>
				 <button type="button" class="i-btn bg--lite--danger text--danger btn--sm" data-bs-dismiss="modal"> <i class="las la-times"></i></button>
			</div>
            <div class="modal-body">
            	<div class="card">
        			<div class="card-body mb-3">
        				<p id="message--text"></p>
        			</div>
        		</div>
        	</div>

            <div class="modal-footer">
                <button type="button" class="i-btn danger--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel')}}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-push')
    <script>
        (function($){
            "use strict";
            $('.details').on('click', function(){
                var modal = $('#smsdetails');
                var message = $(this).data('message');
                var response_gateway = $(this).data('response_gateway');
                $("#message--text").html(`${message}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
