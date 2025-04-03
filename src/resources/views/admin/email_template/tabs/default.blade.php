@extends('admin.layouts.app')
@section('panel')
    <section>
        <div class="container-fluid p-0">
            <div class="row gy-4">
                @include('admin.email_template.templates')
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h6>{{translate('Default Templates')}}</h6>
                        </div>
                        <div class="card-body px-0">
                            <div class="responsive-table">
                            <table>
                                <thead>
                                <tr>
                                    <th> {{ translate('Name')}}</th>
                                    <th> {{ translate('Subject')}}</th>
                                    <th> {{ translate('Status')}}</th>
                                    <th> {{ translate('Action')}}</th>
                                </tr>
                                </thead>
                                @forelse($defaultTemplates as $emailTemplate)
                                    <tr class="@if($loop->even)@endif">
                                        <td data-label=" {{ translate('Name')}}">
                                            {{$emailTemplate->name}}
                                        </td>

                                        <td data-label=" {{ translate('Subject')}}">
                                            {{$emailTemplate->subject}}
                                        </td>

                                        <td data-label=" {{ translate('Status')}}">
                                            @if($emailTemplate->status == 1)
                                                <span class="badge badge--success"> {{ translate('Active')}}</span>
                                            @else
                                                <span class="badge badge--danger"> {{ translate('Inactive')}}</span>
                                            @endif
                                        </td>

                                        <td data-label={{ translate('Action') }}>
                                            <div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">
                                                <a class="i-btn primary--btn btn--sm" href="{{route('admin.mail.templates.edit', $emailTemplate->id)}}"><i class="las la-pen"></i></a>
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
                                {{$defaultTemplates->appends(request()->all())->onEachSide(1)->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

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

