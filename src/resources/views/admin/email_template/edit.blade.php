@extends('admin.layouts.app')
@section('panel')
<section class="mt-3 rounded_box">
	<div class="container-fluid p-0 pb-2">
		<div class="row d-flex align--center rounded">
			<div class="col-xl">
				<form action="{{route('admin.mail.templates.update', $emailTemplate->id)}}" method="POST" enctype="multipart/form-data" novalidate="">
				@csrf
					<div class="card mb-3">
						<h6 class="card-header"> {{ translate('Update mail template for mail notification')}}</h6>
						<div class="p-3 rounded_box">
							<div class="work_list">
								<h5> {{ translate('Email Template Short Code')}}</h5>
								<div class="work_list_body">
									@if($emailTemplate->codes)
										@foreach($emailTemplate->codes as $key => $value)
											<div class="d--flex align--center justify--between single_work_item complete_item">
												<div>
													<h6>{{$value}}</h6>
												</div>
												<p>@php echo "{{". $key ."}}"  @endphp</p>
											</div>
										@endforeach
									@endif
								</div>
							</div>
						</div>

						<div class="card-body">


							<div class="row">
								<div class="mb-2 col-lg-6">
									<label for="subject" class="form-label"> {{ translate('Subject')}}<sup class="text--danger">*</sup></label>
									<input type="text" name="subject" class="form-control" value="{{$emailTemplate->subject}}" placeholder=" {{ translate('Enter Subject')}}" required>
								</div>

								<div class="mb-2 col-lg-6">
									<label for="subject" class="form-label"> {{ translate('Status')}}<sup class="text--danger">*</sup></label>
									<select class="form-control" name="status" id="status" required>
										<option value="1" @if($emailTemplate->status == 1) selected @endif> {{ translate('Active')}}</option>
										<option value="2" @if($emailTemplate->status == 2) selected @endif> {{ translate('Inactive')}}</option>
									</select>
								</div>

								<div class="mb-2 col-lg-12">
									<label for="body" class="form-label"> {{ translate('Description')}}<sup class="text--danger">*</sup></label>
									<textarea class="form-control" name="body" rows="5" id="body" required>{{ $emailTemplate->body }}</textarea>
								</div>
							</div>

							<button type="submit" class="i-btn primary--btn btn--md"> {{ translate('Submit')}}</button>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
</section>
@endsection

@push('script-push')
<script>
	'use strict';
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
</script>
@endpush
