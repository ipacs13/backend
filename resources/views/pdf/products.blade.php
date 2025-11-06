<div>
    <h1 style="text-align: center; font-size: 24px; font-weight: bold;">Products</h1>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid #000; padding: 8px;">ID</th>
                <th style="border: 1px solid #000; padding: 8px;">Name</th>
                <th style="border: 1px solid #000; padding: 8px;">Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td style="border: 1px solid #000; padding: 8px;">{{ $product->id }}</td>
                    <td style="border: 1px solid #000; padding: 8px;">{{ $product->name }}</td>
                    <td style="border: 1px solid #000; padding: 8px;">{{ $product->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
