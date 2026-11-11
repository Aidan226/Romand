
@extends('v2.layout')
@section('content')<div class="eyebrow">Your shade shortlist</div><h1>Find the one<br>that feels like you.</h1><div class="compare"><strong>Your confirmed profile</strong><p>{{ ucfirst($p['type']) }} · Depth group {{ $p['depth'] }} · {{ $p['undertone']==='unknown'?'Undertone not specified':ucfirst($p['undertone']).' undertone' }} · {{ $p['finish'] }} finish</p>
@if(!empty($p['reference_note']))<p class="small">Your reference note: {{ $p['reference_note'] }}</p>
@endif<p class="small muted">Recommendations use catalog tags and your confirmed selections. They are not measured accuracy percentages. Test a physical cushion swatch before buying.</p></div>
@if(empty($results))<section class="panel"><h2>No suitable match in this catalog</h2><p>We could not find an in-stock, reviewed shade within the matching rules. Your skin tone does not need to change. This collection may need a broader shade range.</p><a class="button" href="{{ route('shade.finder') }}">Review my profile</a></section>
@else<div class="grid">
@foreach($results as $result)
@php($shade=$result['shade'])<article class="card"><div style="height:155px;background:{{ $shade->shade_hex }}"></div><div class="card-body"><span class="eyebrow">Option {{ $loop->iteration }} · {{ $shade->product->product_type }}</span><h3>{{ $shade->product->name }}</h3><p><strong>{{ $shade->shade_name }}</strong> {{ $shade->shade_code }}</p>
@if($shade->product->is_demo)<span class="tag">Illustrative demo shade</span>
@endif<ul>
@foreach($result['reasons'] as $reason)<li class="small">{{ $reason }}</li>
@endforeach</ul><p class="small muted">{{ $shade->undertone }} · {{ $shade->product->finish }} 
@if($shade->depth)· Depth {{ $shade->depth }}
@endif</p><p>{{ number_format($shade->price) }} MMK · {{ $shade->stock }} in stock</p><a class="button secondary" href="{{ route('product.show',$shade->id) }}">View shade</a><form method="post" action="{{ route('cart.add') }}" style="margin-top:10px">
@csrf<input type="hidden" name="product_id" value="{{ $shade->id }}"><input type="hidden" name="shade_name" value="{{ $shade->shade_name }}"><input type="hidden" name="quantity" value="1"><button>Add to cart</button></form><details><summary class="small">Shade data source</summary><p class="small">{{ $shade->source }}</p></details>
@auth<form method="post" action="{{ route('shade.feedback') }}">
@csrf<input type="hidden" name="shade_id" value="{{ $shade->id }}"><label class="field">Your feedback<select name="feedback">
@foreach(($p['type']==='lipstick'?['like'=>'I like it','dislike'=>'Not my preference']:['suitable'=>'Suitable','too_light'=>'Too light','too_dark'=>'Too dark','too_pink'=>'Too pink','too_yellow'=>'Too yellow']) as $v=>$label)<option value="{{ $v }}">{{ $label }}</option>
@endforeach</select></label><button class="secondary">Save feedback</button></form>
@endauth</div></article>
@endforeach</div>
@endif<p><a href="{{ route('shade.finder') }}">← Adjust my preferences</a></p>
@endsection