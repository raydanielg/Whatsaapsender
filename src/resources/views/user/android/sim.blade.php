@extends('user.layouts.app')
@section('panel')
    <section class="mt-3">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="responsive-table">
                            <table class="m-0 text-center table--light">
                                <thead>
                                <tr>
                                    <th>{{translate('Android Gateway Name')}}</th>
                                    <th>{{translate('SIM Number')}}</th>
                                    <th>{{translate('Time Interval')}}</th>
                                    <th>{{translate('Send SMS')}}</th>
                                    <th>{{translate('Status')}}</th>
                                    <th>{{translate('Action')}}</th>
                                </tr>
                                </thead>
                                @forelse($simLists as $simList)
                                    <tr class="@if($loop->even)@endif">
                                        <td data-label=">{{translate('Name')}}">
                                            {{$simList->androidGateway->name}}
                                        </td>

                                        <td data-label=">{{translate('Number')}}">
                                            {{$simList->sim_number}}
                                        </td>

                                        <td data-label=">{{translate('Time Interval')}}">
                                            {{$simList->time_interval}}
                                        </td>

                                        <td data-label=">{{translate('Send SMS')}}">
                                            {{$simList->send_sms}}
                                        </td>

                                        <td data-label=">{{translate('Status')}}">
                                            @if($simList->status == 1)
                                                <span class="badge badge--success">{{translate('Active')}}</span>
                                            @else
                                                <span class="badge badge--danger">{{translate('Inactive')}}</span>
                                            @endif
                                        </td>

                                        <td data-label="{{translate('Action')}}">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a class="i-btn primary--btn btn--sm delete" data-bs-toggle="modal" data-bs-target="#deleteandroidsim" href="javascript:void(0)"data-id="{{$simList->id}}"><i class="las la-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{translate('No Data Found')}}</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                        <div class="m-3">
                            {{$simLists->appends(request()->all())->onEachSide(1)->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="deleteandroidsim" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('user.gateway.sms.android.sim.delete')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal_body2">
                        <div class="modal_icon2">
                            <i class="las la-trash"></i>
                        </div>
                        <div class="modal_text2 mt-3">
                            <h6>{{translate('Are you sure to want delete this sim?')}}</h6>
                        </div>
                    </div>
                    <div class="modal_button2 modal-footer">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{translate('Cancel')}}</button>
                            <button type="submit" class="i-btn danger--btn btn--md">{{translate('Delete')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script-push')
    <script>
        (function($){
            "use strict";
            $('.delete').on('click', function(){
                var modal = $('#deleteandroidsim');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush



