@extends('user.layouts.app')
@section('panel')
<section class="mt-3">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"> {{translate('Email Templates')}}</h4>
            <div>
                <a href="{{route('user.template.email.create')}}" class="i-btn btn--primary btn--md" ><i class="las la-plus"></i>{{translate('Create')}}</a>
            </div>
        </div>
        <div class="card-body px-0">
            <div class="responsive-table">
                <table>
                    <thead>
                        <tr>
                            <th> {{ translate('Name')}}</th>
                            <th> {{ translate('Provider')}}</th>
                            <th> {{ translate('Status')}}</th>
                            <th> {{ translate('Action')}}</th>
                        </tr>
                    </thead>
                    @forelse($emailTemplates as $emailTemplate)
                        <tr class="@if($loop->even)@endif">
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
                                @if($emailTemplate->status == 1)
                                    <span class="badge badge--success"> {{ translate('Active')}}</span>
                                @elseif($emailTemplate->status == 2)
                                    <span class="badge badge--danger"> {{ translate('Inactive')}}</span>

                                @endif
                            </td>

                            <td data-label={{ translate('Action') }}>
                                <div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">
                                    <a class="i-btn primary--btn btn--sm me-2" href="{{route('user.template.email.edit', $emailTemplate->id)}}"><i class="las la-pen"></i></a>

                                    <a href="javascript:void(0)" class="templateDelete i-btn danger--btn btn--sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#delete"
                                    data-delete_id="{{$emailTemplate->id}}"
                                    ><i class="las la-trash"></i></a>
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
                {{$emailTemplates->links()}}
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        	<form action="{{route('user.template.email.delete')}}" method="POST">
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

@endsection



@push('script-push')
<script>
	(function($){
		"use strict";
        $(document).on('click','.templateDelete',function(e){
            var modal = $('#delete');
			modal.find('input[name=id]').val($(this).attr('data-delete_id'));
			modal.modal('show');
            e.preventDefault()
        })
	})(jQuery);
</script>
@endpush
