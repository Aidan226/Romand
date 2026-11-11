
@extends('v2.layout')
@section('content')<div class="eyebrow">Your personal shade guide</div><h1>A starting point,<br>made for you.</h1><div class="steps"><span>01 · Capture or select</span><span>02 · Confirm your profile</span><span>03 · Compare your shades</span></div>
<div class="two-col"><section class="panel"><h2>Start with your skin</h2><p class="muted">Camera capture can suggest a skin-depth group. Confirm it yourself. Undertone remains your selection; a webcam cannot reliably diagnose it.</p><video id="camera" autoplay playsinline muted hidden></video><canvas class="preview" id="capture-preview" hidden></canvas><p id="camera-status" role="status" aria-live="polite">Use even daylight, turn off beauty filters, and keep your face centered. For cushion, use bare skin.</p><div class="actions"><button type="button" id="open-camera">Open camera</button><button type="button" class="secondary" id="capture-camera" disabled>Capture cheeks</button><button type="button" class="secondary" id="close-camera">Close</button></div><details><summary>No camera? You can still continue.</summary><p>Select your skin depth and undertone on the right. Photos stay in this browser and are not uploaded. Face detection requires an internet connection to load its model.</p></details><p class="small muted">Skin depth: 1 fair · 2 light · 3 light-medium · 4 medium · 5 tan · 6 deep. Groups are illustrative and need validation across devices and skin tones.</p></section>
<section class="panel"><h2>Confirm your preferences</h2><form method="post" action="{{ route('shade.analyze') }}" id="finder-form">
@csrf
@if($scope)<input type="hidden" name="product_id" value="{{ $scope }}"><p class="tag">Matching within your selected product</p>
@endif
<div class="form-grid">
@php($fields=['type'=>['Product type',['lipstick'=>'Lipstick','cushion'=>'Cushion']],'depth'=>['Skin depth',[1=>'1 · Fair',2=>'2 · Light',3=>'3 · Light-medium',4=>'4 · Medium',5=>'5 · Tan',6=>'6 · Deep']],'undertone'=>['Undertone / preference',['unknown'=>'Not sure','warm'=>'Warm','cool'=>'Cool','neutral'=>'Neutral']],'family'=>['Lip color family',['any'=>'Any','nude'=>'Nude','pink'=>'Pink','red'=>'Red','coral'=>'Coral','berry'=>'Berry','brown'=>'Brown','other'=>'Other']],'finish'=>['Preferred finish',['any'=>'Any','matte'=>'Matte','glossy'=>'Glossy','natural'=>'Natural','dewy'=>'Dewy']],'coverage'=>['Cushion coverage',['any'=>'Any','light'=>'Light','medium'=>'Medium','full'=>'Full']]])
@foreach($fields as $name=>[$label,$options])<label class="field">{{ $label }}<select name="{{ $name }}" id="{{ $name }}" required>
@foreach($options as $value=>$text)<option value="{{ $value }}" 
@selected((string)old($name,$saved[$name]??($name==='depth'?3:array_key_first($options)))===(string)$value)>{{ $text }}</option>
@endforeach</select></label>
@endforeach
</div><label class="field">Current cushion and how it fits (optional)<textarea name="reference_note" maxlength="300" placeholder="e.g. My current shade is too light. This note is for your comparison, not automatic shade conversion.">{{ old('reference_note',$saved['reference_note']??'') }}</textarea></label><p class="small muted">Color family is used for lips; coverage is used for cushion. Unknown undertone broadens the results.</p>
@auth<label><input type="checkbox" name="save_profile" value="1"> Save this profile to my account</label>
@else<p class="small muted">Sign in to save your profile and give feedback.</p>
@endauth<p><button>Show my recommendations →</button></p></form>
@auth<form action="{{ route('shade.profile.delete') }}" method="post">
@csrf 
@method('DELETE')<button class="text-button small">Delete my saved profile</button></form>
@endauth</section></div>
@endsection
@push('scripts')<script type="module" src="{{ asset('js/shade-camera.js') }}"></script>
@endpush