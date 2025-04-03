@extends('admin.layouts.app')
@section('panel')
    <section>
        <div class="container-fluid p-0">
            <div class="row gy-4">
                @include('admin.email_template.templates')
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h6>{{translate('User Templates')}}</h6>
                        </div>

                        <div class="card-body px-0">
                            <div class="responsive-table">
                                <table>
                                    <thead>
                                    <tr>
                                        <th> {{ translate('User')}}</th>
                                        <th> {{ translate('Name')}}</th>
                                        <th> {{ translate('Provider')}}</th>
                                        <th> {{ translate('Status')}}</th>
                                        <th> {{ translate('Action')}}</th>
                                    </tr>
                                    </thead>
                                    @forelse($userEmailTemplates as $emailTemplate)
                                        <tr class="@if($loop->even)@endif">
                                            <td data-label=" {{ translate('User Name')}}">
                                                {{$emailTemplate->user ? $emailTemplate->user->name :"N/A"}}
                                            </td>

                                            <td data-label=" {{ translate('Name')}}">
                                                {{$emailTemplate->name}}
                                            </td>

                                            <td data-label=" {{ translate('provider')}}">
                                                @if($emailTemplate->provider == 1)
                                                    <span class="badge badge--success"> {{ translate('Beepro')}}</span>
                                                @else
                                                    <span class="badge badge--info"> {{ translate('Texteditor')}}</span>
                                                @endif
                                            </td>

                                            <td data-label=" {{ translate('Status')}}">
                                                @if(request()->routeIs('admin.template.email.user.list'))
                                                    @if($emailTemplate->status ==1)
                                                        <div class="badge bg-primary">{{translate('Approved')}}</div>
                                                    @elseif($emailTemplate->status == 2)
                                                        <div class="badge bg-warning">{{translate('Pending')}}</div>
                                                    @elseif($emailTemplate->status == 3)
                                                        <div class="badge bg-danger">{{translate('Rejected')}}</div>
                                                    @endif
                                                @else
                                                    @if($emailTemplate->status == 1)
                                                        <span class="badge badge--success"> {{ translate('Active')}}</span>
                                                    @else
                                                        <span class="badge badge--danger"> {{ translate('Inactive')}}</span>
                                                    @endif
                                                @endif
                                            </td>

                                            <td data-label={{ translate('Action') }}>
                                                <div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">
                                                    @if(!request()->routeIs('admin.template.email.user.list'))
                                                        <a class="i-btn primary--btn btn--sm" href="{{route('admin.template.email.edit', $emailTemplate->id)}}"><i class="las la-pen"></i></a>
                                                    @else
                                                        <a class="i-btn primary--btn btn--sm statusUpdate" data-bs-toggle="modal" data-bs-target="#updateStatus" href="javascript:void(0)"
                                                        data-id="{{$emailTemplate->id}}" data-status="{{$emailTemplate->status}}">
                                                            <i class="las la-pen"></i>
                                                        </a>
                                                    @endif
                                                    <a href="javascript:void(0)" class="i-btn danger--btn btn--sm delete"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#emailTemplateDelete"
                                                    data-delete_id="{{$emailTemplate->id}}"><i class="las la-trash"></i>
                                                    </a>
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
                                {{$userEmailTemplates->appends(request()->all())->onEachSide(1)->links()}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

<div class="modal fade" id="emailTemplateDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('admin.template.email.delete')}}" method="POST">
                @csrf
                <input type="hidden" name="id" value="">
                <div class="modal_body2">
                    <div class="modal_icon2">
                        <i class="las la-trash"></i>
                    </div>
                    <div class="modal_text2 mt-3">
                        <h6>{{ translate('Are you sure to delete this Template')}}</h6>
                    </div>
                </div>
                <div class="modal_button2 modal-footer">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel')}}</button>
                        <button type="submit" class="i-btn danger--btn btn--md">{{ translate('Delete')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('admin.template.email.status.update')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header bg--lite--violet">
                            <div class="card-title text-center text--light">{{translate('Status Update')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3" id="statusAppend">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal_button2 modal-footer">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        <button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="i-btn success--btn btn--md">{{translate('Update')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('script-push')
    <script>
        (function($){
            "use strict";
            $('.delete').on('click',function(e){
                var modal = $('#emailTemplateDelete');
                modal.find('input[name=id]').val($(this).data('delete_id'));
                modal.modal('show');
            })

            $('.statusUpdate').on('click', function(){
                var modal = $('#updateStatus');
                modal.find('input[name=id]').val($(this).data('id'));
                var value = $(this).data('status');
                $('#statusAppend').html('')
                $('#statusAppend').html(`
                    <label for="status" class="form-label">{{translate('Status')}} <sup class="text--danger">*</sup></label>
                    <select name="status" id="status" class="form-control" >
                        <option  ${value == 1 ? 'selected' : ''} value="1">{{translate('Approved')}}</option>
                        <option  ${value == 2 ? 'selected' : ''} value="2">{{translate('Pending')}}</option>
                        <option  ${value == 3 ? 'selected' : ''} value="3">{{translate('Reject')}}</option>
                    </select>
                `)
                modal.modal('show');
            });
            $(document).ready(function() {
                 $('#body').summernote({
                    placeholder: '{{ translate('Write Here Email Content &  For Mention Name Use ')}}'+'{'+'{name}'+"}",
                    tabsize: 2,
                    width:'100%',
                    height: 200,
                    toolbar: [
                        ['fontname', ['fontname']],
                        ['style', ['style']],
                        ['fontsize', ['fontsizeunit']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['height', ['height']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['codeview']],
                    ],
                    codeviewFilterRegex: 'custom-regex'
                });
            });
        })(jQuery);
    </script>
@endpush

