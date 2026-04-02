@extends('emails.layouts.master')
@section('content')
<p>The following products are running low on stock and may need to be restocked:</p>
<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin: 16px 0;">
    <thead>
        <tr style="background-color: #f8f8f8;">
            <th align="left" style="border-bottom: 2px solid #ddd; font-size: 13px;">Product</th>
            <th align="left" style="border-bottom: 2px solid #ddd; font-size: 13px;">SKU</th>
            <th align="center" style="border-bottom: 2px solid #ddd; font-size: 13px;">Current Stock</th>
            <th align="center" style="border-bottom: 2px solid #ddd; font-size: 13px;">Threshold</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td style="border-bottom: 1px solid #eee; font-size: 14px;">{{ $product->name }}</td>
            <td style="border-bottom: 1px solid #eee; font-size: 14px;">{{ $product->sku }}</td>
            <td align="center" style="border-bottom: 1px solid #eee; font-size: 14px; color: {{ $product->stock_quantity <= 0 ? '#dc3545' : '#ffc107' }}; font-weight: bold;">{{ $product->stock_quantity }}</td>
            <td align="center" style="border-bottom: 1px solid #eee; font-size: 14px;">{{ $product->low_stock_threshold }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
