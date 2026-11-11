@extends('admin.layouts.app')
@section('title','Discounts & Promotions')
@section('content')
<div class="card">
    <h3>Create Promotion</h3>
    <form method="POST" action="{{ route('admin.promotions.store') }}" class="form-grid" style="grid-template-columns:1.5fr .7fr 1fr 1fr 1fr auto;">
        @csrf
        <div><label>Name</label><input name="name" placeholder="September Sale" required></div>
        <div><label>Discount %</label><input type="number" name="discount_percent" min="0.01" max="100" step="0.01" required></div>
        <div><label>Minimum Order</label><input type="number" name="minimum_order" min="0" step="1" value="0" required></div>
        <div><label>Start</label><input type="datetime-local" name="starts_at"></div>
        <div><label>End</label><input type="datetime-local" name="ends_at"></div>
        <div><label><input type="checkbox" name="is_active" value="1" checked style="width:auto;"> Active</label><br><button class="btn primary">Add</button></div>
    </form>
</div>
<div class="section table-wrap"><table><thead><tr><th>Name</th><th>Discount</th><th>Min Order</th><th>Period</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse($promotions as $promotion)
<tr><form method="POST" action="{{ route('admin.promotions.update',$promotion) }}">@csrf @method('PUT')
<td><input name="name" value="{{ $promotion->name }}" required></td>
<td><input type="number" name="discount_percent" value="{{ $promotion->discount_percent }}" min="0.01" max="100" step="0.01" required></td>
<td><input type="number" name="minimum_order" value="{{ $promotion->minimum_order }}" min="0" step="1" required></td>
<td><input type="datetime-local" name="starts_at" value="{{ optional($promotion->starts_at)->format('Y-m-d\TH:i') }}"><br><input type="datetime-local" name="ends_at" value="{{ optional($promotion->ends_at)->format('Y-m-d\TH:i') }}" style="margin-top:6px;"></td>
<td><label><input type="checkbox" name="is_active" value="1" {{ $promotion->is_active ? 'checked' : '' }} style="width:auto;"> Active</label></td>
<td class="actions"><button class="btn primary">Save</button></form><form method="POST" action="{{ route('admin.promotions.destroy',$promotion) }}" onsubmit="return confirm('Delete this promotion?')">@csrf @method('DELETE')<button class="btn danger">Delete</button></form></td></tr>
@empty<tr><td colspan="6">No promotions yet.</td></tr>@endforelse
</tbody></table></div>
@endsection
