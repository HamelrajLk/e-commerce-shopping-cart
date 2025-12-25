<h2>Daily Sales Report</h2>

<ul>
@foreach ($sales as $items)
    @php $item = $items->first(); @endphp
    <li>
        {{ $item->product->name }} —
        Total Sold: {{ $items->sum('quantity') }}
    </li>
@endforeach
</ul>
