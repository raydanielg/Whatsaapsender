@extends('user.layouts.app')
@section('panel')
    <section>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ translate('Whatsapp logs')}}</h4>
            </div>

            <div class="card-filter">
                <form action="{{route('user.whatsapp.search')}}" method="GET">
                    <div class="filter-form">
                        <div class="filter-item">
                            <select name="status" class="form-select">
                                <option value="all" @if(@$status == "all") selected @endif>{{translate('All')}}</option>
                                <option value="pending" @if(@$status == "pending") selected @endif>{{translate('Pending')}}</option>
                                <option value="schedule" @if(@$status == "schedule") selected @endif>{{translate('Schedule')}}</option>
                                <option value="fail" @if(@$status == "fail") selected @endif>{{translate('Fail')}}</option>
                                <option value="delivered" @if(@$status == "delivered") selected @endif>{{translate('Delivered')}}</option>
                                <option value="processing" @if(@$status == "processing") selected @endif>{{translate('Processing')}}</option>
                            </select>
                        </div>

                        <div class="filter-item">
                            <input type="text" autocomplete="off" name="search" placeholder="{{ translate('Search with User or Recipient number')}}" class="form-control" id="search" value="{{@$search}}">
                        </div>

                        <div class="filter-item">
                            <input type="text" class="form-control datepicker-here" name="date" value="{{@$searchDate}}" data-range="true" data-multiple-dates-separator=" - " data-language="en" data-position="bottom right" autocomplete="off" placeholder="{{ translate('From Date-To Date')}}" id="date">
                        </div>

                        <div class="filter-action">
                            <button class="i-btn primary--btn btn--md" type="submit">
                                <i class="fas fa-search"></i> {{ translate('Search')}}
                            </button>
                            <div class="statusUpdateBtn d-none">
                                <a class="i-btn success--btn btn--md statusupdate"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Status Update"
                                        data-bs-toggle="modal"
                                        data-bs-target="#smsstatusupdate"
                                        type="submit">
                                    <i class="fas fa-gear"></i> {{translate('Action')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body px-0">
                <div class="responsive-table">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ translate('#')}}</th>
                                <th>{{ translate('Gateway')}}</th>
                                <th>{{ translate('To')}}</th>
                                <th>{{ translate('Date & Time')}}</th>
                                <th>{{ translate('Status')}}</th>
                                <th class="text-center">{{ translate('Action')}}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($whatsAppLogs as $smsLog)
                                <tr class="@if($loop->even)@endif">
                                    @if($smsLog->status == 1)
                                        <td class="lh-1" data-label="{{ translate('Id')}}">
                                            <input class="form-check-input mt-0 me-2" type="checkbox" name="whatsappid" value="{{$smsLog->id}}" aria-label="Checkbox for following text input">
                                            {{$loop->iteration}}
                                        </td>
                                    @else
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                    @endif

                                    <td data-label="{{ translate('Gateway') }}">

                                        @if($smsLog->whatsappGateway)
                                            <p class="mb-1">
                                                {{ translate('WhatsApp')}}
                                            </p>
                                            <span class="bg--lite--info text--info rounded px-2 py-1 d-inline-block fs--12 text-break">{{$smsLog->whatsappGateway->name}}
                                            </span>
                                        @else
                                            <span>{{translate('N/A')}}</span>
                                        @endif
                                    </td>

                                    <td data-label="{{ translate('To')}}">
                                        {{$smsLog->to}}

                                        <p>
                                            @php
                                                $getMessageCountWord = $general->whatsapp_word_count;
                                                $messages = str_split($smsLog->message,$getMessageCountWord);
                                                $totalMessage = count($messages);
                                            @endphp

                                            <span class="badge badge--success">
                                                <i class="las la-coins"></i>
                                                {{$totalMessage}} {{ translate('Credit')}}
                                            </span>
                                        </p>
                                    </td>

                                    <td data-label="{{ translate('Date & Time')}}">
                                        <p class="mb-1">
                                            {{translate("Initiated:")}}
                                            <span class="text-muted">{{getDateTime($smsLog->created_at)}}
                                            </span>
                                        </p>

                                        @if(!is_null($smsLog->initiated_time))
                                            <p>
                                                {{translate("Schedule: ")}} <span class="text-muted">{{getDateTime($smsLog->initiated_time)}}</span>
                                            </p>
                                        @else
                                            <p>{{translate("Schedule: ")}}{{translate('N/A')}}</p>
                                        @endif
                                        @if(!is_null($smsLog->delivered_at))
                                        <p class="mb-1">
                                            {{translate("Delivered At: ")}}<span class="text-muted">{{getDateTime($smsLog->delivered_at)}}</span>
                                        </p>
                                        @else
                                            <p>{{translate("Delivered At: ")}}{{translate('N/A')}}</p>
                                        @endif

                                    </td>

                                    <td data-label="{{ translate('Status')}}">
                                        @if($smsLog->status == 1)
                                            <span class="badge badge--primary">{{ translate('Pending ')}}</span>
                                        @elseif($smsLog->status == 2)
                                            <span class="badge badge--info">{{ translate('Schedule')}}</span>
                                        @elseif($smsLog->status == 3)
                                            <span class="badge badge--danger">{{ translate('Fail')}}</span>
                                        @elseif($smsLog->status == 4)
                                            <span class="badge badge--success">{{ translate('Delivered')}}</span>
                                        @elseif($smsLog->status == 5)
                                            <span class="badge badge--primary">{{ translate('Processing')}}</span>
                                        @endif
                                    </td>

                                    <td data-label={{ translate('Action')}}>
                                        <div class="d-flex align-items-center justify-content-md-center justify-content-end gap-3">
                                            <a class="i-btn primary--btn btn--sm details"
                                                data-message="{{$smsLog->message}}"
                                                data-response_gateway="{{$smsLog->response_gateway}}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Details"
                                                data-bs-target="#smsdetails"
                                                ><i class="las la-desktop"></i>
                                            </a>
                                        </div>
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

                <div class="m-3">
                    {{$whatsAppLogs->appends(request()->all())->onEachSide(1)->links()}}
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="smsdetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header bg--lite--violet">
                            <div class="card-title text-center text--light">{{ translate('Message')}}</div>
                        </div>
                        <div class="card-body mb-3">
                            <p id="message--text"></p>
                        </div>
                    </div>
                </div>

                <div class="modal_button2 modal-footer">
                    <button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel')}}</button>
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
			    $("#message--text").html(`${message} :: <span class="text-danger"> ${response_gateway} </span>`);
                modal.modal('show');
            });

            $('.checkAll').click(function(){
                $('input:checkbox').not(this).prop('checked', this.checked);
            });

            $('.statusupdate').on('click', function(){
                var modal = $('#smsstatusupdate');
                var newArray = [];
                $("input:checkbox[name=whatsappid]:checked").each(function(){
                    newArray.push($(this).val());
                });
                modal.find('input[name=smslogid]').val(newArray.join(','));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
