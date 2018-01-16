@extends('frontend.layouts.app')

@section('content')
    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">TODAY SALES</div>

                <div class="panel-body">

                    <h3>
                        <small>TOTAL SALE:</small> 
                        <?php
                            $total = 0;
                            foreach($orders as $order)
                            {
                                if($order->status == 'Paid')
                                {
                                    $total += ($order->payable - $order->discount);
                                }
                            }
                            echo number_format($total, 2);
                        ?>
                    </h3>

                    <table class="table table-bordered">
                        <thead>
                            <th>TRANSACTION NO.</th>
                            <th>DATE</th>
                            <th>TIME</th>
                            <th>PRICE</th>
                            <th>DISCOUNT</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                        </thead>
                        <tbody>
                            @if(count($orders))
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->transaction_no }}</td>
                                    <td>{{ $order->created_at->format('F d, Y') }}</td>
                                    <td>{{ $order->created_at->format('h:i A') }}</td>
                                    <td>{{ $order->payable }}</td>
                                    <td>{{ number_format($order->discount, 2) }}</td>
                                    <td>{{ number_format($order->payable - $order->discount, 2) }}</td>
                                    <td style='color:{{ $order->status =="Cancelled" ? "red":"green" }}'>
                                        {{ $order->status }}
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan=3>No record to display.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div><!--panel body-->

            </div><!-- panel -->

        </div><!-- col-xs-12 -->

    </div><!-- row -->
@endsection