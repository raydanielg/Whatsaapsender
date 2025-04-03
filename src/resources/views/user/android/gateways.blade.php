@extends('user.layouts.app')
@section('panel')
<section>
    <div class="container-fluid p-0">
        <div class="row gy-4">
            @include('user.gateway.method')
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{translate("Android Gateway")}}</h4>
                        <a href="{{ $general->app_link }}" class="i-btn info--btn btn--md text-white"  title="{{translate('Download APK file')}}">
                            {{translate('Download APK file')}}
                        </a>
                    </div>
                   
                    <div class="card-body px-0">
                        <div class="responsive-table">
                            <table class="m-0 text-center table--light">
                                <thead>
                                <tr>
                                    <th>{{ translate('Name') }}</th>
                                    <th>{{ translate('Password') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th>{{ translate('SIM List') }}</th>
                                    @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())<th>{{ translate('Action') }}</th>@endif
                                </tr>
                                </thead>
                                @forelse($androids as $android)
                                    <tr class="@if($loop->even)@endif">
                                        <td data-label="{{ translate('Name') }}">
                                            {{$android->name}}
                                        </td>

                                        <td data-label="{{ translate('Password') }}">
                                            {{$android->show_password}}
                                        </td>

                                        <td data-label="{{ translate('Status') }}">
                                            @if($android->status == 1)
                                                <span class="badge badge--success">{{ translate('Active') }}</span>
                                            @else
                                                <span class="badge badge--danger">{{ translate('Inactive') }}</span>
                                            @endif
                                        </td>

                                        <td data-label="{{ translate('list')}}">
                                            @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                                <a href="{{route('user.gateway.sms.android.sim.index', $android->id)}}" class="badge badge--primary p-2">{{ translate('View All')." (".count($android->simInfo).")" }}</a>
                                            @else
                                                <p class="badge badge--primary p-2">{{ translate("Total Sim: "). count($android->siminfo) }}</p>
                                            @endif
                                        </td>
                                        @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
                                            <td data-label={{ translate('Action') }}>
                                                <div class="d-flex align-items-center justify-content-center gap-3">
                                                    <a class="i-btn primary--btn btn--sm android" data-bs-toggle="modal" data-bs-target="#updateandroid" href="javascript:void(0)"
                                                    data-id="{{$android->id}}"
                                                    data-name="{{$android->name}}"
                                                    data-password="{{$android->show_password}}"
                                                    data-status="{{$android->status}}"><i class="las la-pen"></i></a>
                                                    <a class="i-btn danger--btn btn--sm delete" data-bs-toggle="modal" data-bs-target="#deleteandroidApi" href="javascript:void(0)" data-id="{{$android->id}}"><i class="las la-trash"></i></a>
                                                </div>
                                            </td>
                                        @endif  
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ translate('No Data Found') }}</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                        <div class="m-3">
                            {{$androids->appends(request()->all())->onEachSide(1)->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($allowed_access->type == App\Enums\StatusEnum::FALSE->status())
        <a href="javascript:void(0);" class="support-ticket-float-btn" data-bs-toggle="modal" data-bs-target="#createandroid" title="{{ translate('Create New Android GW') }}">
            <i class="fa fa-plus ticket-float"></i>
        </a>
    @endif
</section>
<div class="modal fade" id="createandroid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('user.gateway.sms.android.store')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header bg--lite--violet">
                            <div class="card-title text-center text--light">{{ translate('Add Gateway') }}</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ translate('Name') }} <sup class="text--danger">*</sup></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ translate('Enter Name')}}
                            " required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ translate('Password') }} <sup class="text--danger">*</sup></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{ translate('Enter Password')}}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">{{ translate('Confirm Password') }} <sup class="text--danger">*</sup></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{ translate('Confirm Password') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">{{ translate('Status')}} <sup class="text--danger">*</sup></label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="1">{{ translate('Active') }}</option>
                                    <option value="2">{{ translate('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal_button2 modal-footer">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="i-btn success--btn btn--md">{{ translate('Submit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="updateandroid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('user.gateway.sms.android.update')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header bg--lite--violet">
                            <div class="card-title text-center text--light">{{ translate('Update Android Gateway') }}</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ translate('Name') }}<sup class="text--danger">*</sup></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ translate('Enter Name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ translate('Password') }} <sup class="text--danger">*</sup></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{ translate('Enter Password')}}" required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">{{ translate('Status') }} <sup class="text--danger">*</sup></label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="1">{{ translate('Active') }}</option>
                                    <option value="2">{{ translate('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal_button2 modal-footer">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <button type="button" class="i-btn danger--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="i-btn primary--btn btn--md">{{ translate('Submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteandroidApi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('user.gateway.sms.android.delete')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal_body2">
                    <div class="modal_icon2">
                        <i class="las la-trash"></i>
                    </div>
                    <div class="modal_text2 mt-3">
                        <h6>{{ translate('Are you sure to want delete this android gateway?') }}</h6>
                    </div>
                </div>
                <div class="modal_button2 modal-footer">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="i-btn danger--btn btn--md">{{ translate('Delete') }}</button>
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
            $('.android').on('click', function(){
                var modal = $('#updateandroid');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=name]').val($(this).data('name'));
                modal.find('input[name=password]').val($(this).data('password'));
                modal.find('select[name=status]').val($(this).data('status'));
                modal.modal('show');
            });

            $('.delete').on('click', function(){
                var modal = $('#deleteandroidApi');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
