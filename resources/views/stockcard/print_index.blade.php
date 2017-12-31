@extends('layouts.report')
@section('title',"Stock Card $supply->stocknumber")
@section('content')
  <div id="content" class="col-sm-12">
    <table class="table table-hover table-striped table-bordered table-condensed" id="inventoryTable" cellspacing="0" width="100%">
      <thead>
          <tr rowspan="2">
              <th class="text-left" colspan="5">Stock:  <span style="font-weight:normal">{{ $supply->stocknumber }}</span> </th>
              <th class="text-left" colspan="5">Details:  <span style="font-weight:normal">{{ $supply->details }}</span> </th>
          </tr>
          <tr rowspan="2">
              <th class="text-left" colspan="5">Unit Of Measurement:  <span style="font-weight:normal">{{ $supply->unit }}</span>  </th>
              <th class="text-left" colspan="5">Reorder Point: <span style="font-weight:normal">{{ $supply->reorderpoint }}</span> </th>
          </tr>
          <tr rowspan="2">
              <th class="text-center" colspan="4">Information</th>
              <th class="text-center" colspan="4">Quantity</th>
              <th class="text-center" colspan="2"></th>
          </tr>
        <tr>
          <th>Date</th>
          <th>Receipt</th>
          <th>Name/Supplier</th>
          <th>In</th>
          <th>Out</th>
          <th>Unit Cost</th>
          <th>Amount</th>
          <th>Balance</th>
          <th>Remarks</th>
          
        </tr>
      </thead>
      <tbody>
      @if(count($supply->stockcards) > 0)
        @foreach($supply->stockcards as $stockcard)
        <tr>
          <td>{{ Carbon\Carbon::parse($stockcard->date)->toFormattedDateString() }}</td>
          <td>{{ $stockcard->receipt }}</td>
          <td>{{ $stockcard->organization }}</td>
          <td>{{ $stockcard->received }}</td>
          <td>{{ $stockcard->issued }}</td>
          <td>{{ $stockcard->unitcost }}</td>
          <td>{{ $stockcard->balance * $stockcard->unitcost }}</td>
          <td>{{ $stockcard->balance }}</td>
          <td>{{ $stockcard->remarks }}</td>
        </tr>
        @endforeach
      @else
      <tr>
        <td colspan=7 class="col-sm-12"><p class="text-center">  No record </p></td>
      </tr>
      @endif
      </tbody>
  	</table>
  </div>
  <div id="footer" class="col-sm-12">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th class="col-sm-1">  Prepared By: </th>
          {{-- <th class="col-sm-1">   </th>
          <th class="col-sm-1">   </th> --}}
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-center">
            <br />
            <br />
            <span id="name" style="margin-top: 30px; font-size: 15px;"> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
            <br />
            <span id="office" class="text-center" style="font-size:10px;">{{ Auth::user()->office }}</span>
          </td>
          {{-- <td></td>
          <td></td> --}}
        </tr>
      </tbody>
    </table>
  </div>
@endsection
