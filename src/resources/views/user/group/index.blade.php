@extends('user.layouts.app')
@section('panel')
<section>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">{{ translate("Group of Contact")}}</h4>
		</div>
		<div class="card-filter">
			<form action="{{route(request()->route()->getName())}}" method="get">
				@csrf
				<div class="filter-form">
					<div class="filter-item">
						<select name="status" class="form-select">
							<option value="all" selected disabled @if(@$status == "all") selected @endif>{{translate('All')}}</option>
							<option value="1" @if(@$status == "1") selected @endif>{{translate('Active')}}</option>
							<option value="2" @if(@$status == "2") selected @endif>{{translate('Inactive')}}</option>
						</select>
					</div>

					<div class="filter-item">
						<input type="text" autocomplete="off" name="search" placeholder="{{translate('Search with Name')}}" class="form-control" id="search" value="{{@$search}}">
					</div>

					<div class="filter-action">
						<button class="i-btn primary--btn btn--md" type="submit">
							<i class="fas fa-search"></i> {{ translate('Search')}}
						</button>

						<a class="i-btn danger--btn btn--md" href="{{route(request()->route()->getName())}}">
							<i class="las la-sync"></i>  {{translate('reset')}}
						</a>

					</div>
				</div>
			</form>
		</div>
		<div class="card-body px-0">
			<div class="responsive-table">
				<table>
					<thead>
						<tr>
							<th>{{ translate('Name')}}</th>
							<th>{{ translate('Contact')}}</th>
							<th>{{ translate('Status')}}</th>
							<th>{{ translate('Action')}}</th>
						</tr>
					</thead>
					@forelse($groups as $group)
						<tr class="@if($loop->even)@endif">
							<td data-label="{{ translate('Name')}}">
								{{$group->name}}
							</td>

								<td data-label="{{ translate('Contact')}}">
								<a href="{{route('user.phone.book.sms.contact.group', $group->id)}}" class="badge badge--primary p-2">{{ translate('view contact')}} ({{count($group->contact)}})	</a>
							</td>

							<td data-label="{{ translate('Status')}}">
								@if($group->status == 1)
									<span class="badge badge--success">{{ translate('Active')}}</span>
								@else
									<span class="badge badge--danger">{{ translate('Inactive')}}</span>
								@endif
							</td>

							<td data-label={{ translate('Action')}}>
								<div class="d-flex align-items-center justify-content-md-start justify-content-end gap-3">
									<a class="i-btn primary--btn btn--sm group" data-bs-toggle="modal" data-bs-target="#updatebrand" href="javascript:void(0)"
										data-id="{{$group->id}}"
										data-name="{{$group->name}}"
										data-status="{{$group->status}}"><i class="las la-pen"></i></a>
									<a class="i-btn danger--btn btn--sm delete" data-bs-toggle="modal" data-bs-target="#delete" href="javascript:void(0)"data-id="{{$group->id}}"><i class="las la-trash"></i></a>
								</div>
							</td>
						</tr>
					@empty
						<tr>
							<td class="text-muted text-center" colspan="100%">{{ translate('No Data Found')}}</td>
						</tr>
					@endforelse
				</table>
			</div>
			<div class="m-3">
				{{$groups->appends(request()->all())->onEachSide(1)->links()}}
			</div>
		</div>
	</div>

	<a href="javascript:void(0);" class="support-ticket-float-btn" data-bs-toggle="modal" data-bs-target="#creategroup" title="{{ translate('Create New SMS Group')}}">
		<i class="fa fa-plus ticket-float"></i>
	</a>
</section>


<div class="modal fade" id="creategroup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
			<form action="{{route('user.phone.book.group.store')}}" method="POST">
				@csrf
	            <div class="modal-body">
	            	<div class="card">
	            		<div class="card-header bg--lite--violet">
	            			<div class="card-title text-center text--light">{{ translate('Add New Group')}}</div>
	            		</div>
		                <div class="card-body">
							<div class="mb-3">
								<label for="name" class="form-label">{{ translate('Name')}} <sup class="text--danger">*</sup></label>
								<input type="text" class="form-control" id="name" name="name" placeholder="{{ translate('Enter Name')}}" required>
							</div>

							<div class="mb-3">
								<label for="status" class="form-label">{{ translate('Status')}} <sup class="text--danger">*</sup></label>
								<select class="form-control" name="status" id="status" required>
									<option value="1">{{ translate('Active')}}</option>
									<option value="2">{{ translate('Inactive')}}</option>
								</select>
							</div>
						</div>
	            	</div>
	            </div>

	            <div class="modal_button2 modal-footer">
					<div class="d-flex align-items-center justify-content-center gap-3">
						<button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel')}}</button>
						<button type="submit" class="i-btn success--btn btn--md">{{ translate('Submit')}}</button>
					</div>
	            </div>
	        </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updategroup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
			<form action="{{route('user.phone.book.group.update')}}" method="POST">
				@csrf
				<input type="hidden" name="id">
	            <div class="modal-body">
	            	<div class="card">
	            		<div class="card-header bg--lite--violet">
	            			<div class="card-title text-center text--light">{{ translate('Update Group')}}</div>
	            		</div>
		                <div class="card-body">
							<div class="mb-3">
								<label for="name" class="form-label">{{ translate('Name')}} <sup class="text--danger">*</sup></label>
								<input type="text" class="form-control" id="name" name="name" placeholder="{{ translate('Enter Name')}}" required>
							</div>

							<div class="mb-3">
								<label for="status" class="form-label">{{ translate('Status')}} <sup class="text--danger">*</sup></label>
								<select class="form-control" name="status" id="status" required>
									<option value="1">{{ translate('Active')}}</option>
									<option value="2">{{ translate('Inactive')}}</option>
								</select>
							</div>
						</div>
	            	</div>
	            </div>

	            <div class="modal_button2 modal-footer">
					<div class="d-flex align-items-center justify-content-center gap-3">
						<button type="button" class="i-btn primary--btn btn--md" data-bs-dismiss="modal">{{ translate('Cancel')}}</button>
						<button type="submit" class="i-btn success--btn btn--md">{{ translate('Submit')}}</button>
					</div>
	            </div>
	        </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deletegroup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{route('user.phone.book.group.delete')}}" method="POST">
				@csrf
				<input type="hidden" name="id">
				<div class="modal_body2">
					<div class="modal_icon2">
						<i class="las la-trash"></i>
					</div>
					<div class="modal_text2 mt-3">
						<h6>{{ translate('Are you sure to want delete this group?')}}</h6>
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
		$('.group').on('click', function(){
			var modal = $('#updategroup');
			modal.find('input[name=id]').val($(this).data('id'));
			modal.find('input[name=name]').val($(this).data('name'));
			modal.find('select[name=status]').val($(this).data('status'));
			modal.modal('show');
		});

		$('.delete').on('click', function(){
			var modal = $('#deletegroup');
			modal.find('input[name=id]').val($(this).data('id'));
			modal.modal('show');
		});
	})(jQuery);
</script>
@endpush
