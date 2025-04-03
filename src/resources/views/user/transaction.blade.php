@extends('user.layouts.app')
@section('panel')
<section>

		<div class="card">
			<div class="card-header">
				<h4 class="card-title">{{ translate('Transaction Log')}}</h4>
			</div>
			<div class="card-filter">
				<form action="{{route('user.transaction.search')}}" method="GET">
					<div class="filter-form">
						<div class="filter-item">
							
							<input type="text" autocomplete="off" name="search" placeholder=" {{ translate('Search by trxid')}}" class="form-control" id="search" value="{{@$search}}">
						</div>
						<div class="filter-item">
							
							<select class="form-select" name="paymentMethod" id="paymentMethod">
								<option value="" selected="" disabled=""> {{ translate('Select One')}}</option>
								
								@foreach($paymentMethods as $item)
									<option {{ @$paymentMethod == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>
								@endforeach
							</select>
						</div>
						<div class="filter-item">
							
							<input type="text" class="form-control datepicker-here" name="date" value="{{@$searchDate}}" data-range="true" data-multiple-dates-separator=" - " data-language="en" data-position="bottom right" autocomplete="off" placeholder=" {{ translate('From Date-To Date')}}" id="date">
						</div>
						
						
						<div class="filter-action">
							<button class="i-btn primary--btn btn--md" type="submit">
								<i class="fas fa-search"></i>  {{translate('Search')}}
							</button>
							<button class="i-btn danger--btn btn--md">
								<a class="text-white" href="{{ route('user.transaction.history') }}">
									<i class="las la-sync"></i>  {{translate('reset')}}
								</a>
							</button>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body px-0">
				<div class="responsive-table">
					<table >
						<thead>
							<tr>
								<th> {{ translate('Date')}}</th>
								<th> {{ translate('Trx Number')}}</th>
								<th> {{ translate('Amount')}}</th>
								<th> {{ translate('Detail')}}</th>
							</tr>
						</thead>
						@forelse($transactions as $transaction)
							<tr class="@if($loop->even)@endif">
								<td data-label=" {{ translate('Date')}}">
									<span>{{diffForHumans($transaction->created_at)}}</span><br>
									{{getDateTime($transaction->created_at)}}
								</td>
	
								<td data-label=" {{ translate('Trx Number')}}">
									{{$transaction->transaction_number}}
								</td>
	
								<td data-label=" {{ translate('Amount')}}">
									<span class="@if($transaction->transaction_type == '+')text--success @else text--danger @endif">{{$transaction->transaction_type}} {{shortAmount($transaction->amount)}} {{$transaction->paymentGateway->currency_code}}
									</span>
								</td>
	
								<td data-label=" {{ translate('Details')}}">
									{{$transaction->details}}
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
					{{$transactions->links()}}
				</div>
			</div>
		</div>
	
</section>
@endsection
