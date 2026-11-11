@extends('admin.layouts.app')
@section('title','Customer Management')
@section('content')
<div class="table-wrap"><table><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Orders</th><th>Registered</th></tr></thead><tbody>
@forelse($customers as $customer)<tr><td>{{ $customer->id }}</td><td>{{ $customer->name }}</td><td>{{ $customer->email }}</td><td>{{ $customer->orders_count }}</td><td>{{ $customer->created_at?->format('d M Y') }}</td></tr>@empty<tr><td colspan="5">No customers found.</td></tr>@endforelse
</tbody></table></div>
@endsection
