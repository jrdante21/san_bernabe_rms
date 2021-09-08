@extends('layouts.print')
@section('content')
    <div class="font-bold mb-4 text-lg text-center">Transaction Report</div>
        
    @if (!empty($resident))
        <div class="font-bold text-center mb-2">{{ $resident['name'] }}</div>
    @endif

    @if (count($documents) <= 0)
        <div class="italic text-center">No transactions found.</div>   
    @endif

    @foreach ($documents as $k => $docs)
        @php
            $trans = collect($transactions)->firstWhere('id', $k); 
            $total = collect($docs)->sum('amount');
            $num = 0;
        @endphp
        <div class="font-bold mb-2 text-center">{{ $trans['title'] }}</div>

        @if ($k <= 9) {{-- All Documents --}}
            <table class="celled w-full table-auto text-left border-collapse mb-4">
                <thead>
                    <tr class="border-b border-gray-300 border-solid bg-gray-200">
                        <th>#</th>
                        <th>Name</th>
                        <th>CTC No.</th>
                        <th>OR No.</th>
                        <th class="text-right">Amount</th>
                        <th>Date Issued</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($docs as $d)
                        <tr>
                            <td>{{ $num+=1 }}</td>
                            <td>{{ $d['resident']['name'] }}</td>
                            <td>{{ $d['doc_num'] }}</td>
                            <td>{{ $d['or_num'] }}</td>
                            <td class="text-right">{{ $d['amount_format'] }}</td>
                            <td>{{ date('M d, Y', strtotime($d['issued_at'])) }}</td>
                        </tr>
                    @endforeach
                    <tr class="text-right font-bold border-t border-gray-300 border-solid  bg-gray-100">
                        <td colspan="4">Total</td>
                        <td>{{ number_format($total) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

        @else {{-- Borrowed Materials --}}
            <table class="celled w-full table-auto text-left border-collapse mb-4">
                <thead>
                    <tr class="border-b border-gray-300 border-solid bg-gray-200">
                        <th>#</th>
                        <th>Name</th>
                        <th>Materials</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>OR No.</th>
                        <th class="text-right">Amount</th>
                        <th>Date Issued</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($docs as $d)
                        <tr>
                            <td>{{ $num+=1 }}</td>
                            <td>{{ $d['resident']['name'] }}</td>
                            <td>({{ $d['quantity'] }}) {{ ucwords($d['material']) }}</td>
                            <td>{{ ucwords($d['status']) }}</td>
                            <td>{{ $d['remarks']['text'] }}</td>
                            <td>{{ $d['or_num'] }}</td>
                            <td class="text-right">{{ $d['amount_format'] }}</td>
                            <td>{{ date('M d, Y', strtotime($d['issued_at'])) }}</td>
                        </tr>
                    @endforeach
                    <tr class="text-right font-bold border-t border-gray-300 border-solid  bg-gray-100">
                        <td colspan="6">Total</td>
                        <td>{{ number_format($total) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @endif

    @endforeach
@endsection