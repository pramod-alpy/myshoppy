<h2>Daily Sales Report ({{ now()->toDateString() }})</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Buyer</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Item Total</th>
            <th>Purchased At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($report as $row)
            <tr>
                <td>{{ $row->buyer_name }}</td>
                <td>{{ $row->product_name }}</td>
                <td>{{ $row->quantity }}</td>
                <td>₹{{ $row->price }}</td>
                <td>₹{{ $row->item_total }}</td>
                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h3>Total Sales: ₹{{ $totalSales }}</h3>

