// Catalog rules, not accuracy percentages or an undertone diagnosis.
export function assess(shade, sample, preference='unknown', type='lipstick', demo=false) {
 if (!sample) return null;
 if (demo || !shade.reviewed) return {title:'Shade data needs review',message:'Your skin was captured. This selected shade has not been reviewed against a reliable source, so suitability cannot be confirmed. Ask the store to review its shade tags.'};
 const known=['warm','cool','neutral'];
 if (!known.includes(shade.undertone)) return {title:'Shade undertone is missing',message:'The selected shade needs a reviewed undertone tag before comparison.'};
 const compatible=preference===shade.undertone || preference==='neutral' || shade.undertone==='neutral';
 if(type==='lipstick') {
  if(preference==='unknown') return {title:'Captured — compare your selected lip color',message:'A camera cannot reliably decide lipstick suitability or your undertone. The two swatches show your sampled skin and selected lip color. If you know your undertone preference, add it below for a simple tag comparison.'};
  return {title:compatible?'Compatible with your undertone preference':'A contrasting undertone choice',message:compatible?'The reviewed shade tag is compatible with your selected preference. This is a style suggestion, not a guarantee.':'The reviewed tag differs from your preference. You can still wear it; lipstick is a personal choice.'};
 }
 if(!Number.isInteger(shade.depth)||shade.depth<1||shade.depth>6)return {title:'Shade depth is missing',message:'The store needs to review this cushion depth before comparison.'};
 const gap=Math.abs(shade.depth-sample.depth);
 const depthText=gap===0?'Same estimated depth group.':shade.depth<sample.depth?'This shade is lighter than the estimated skin-depth group.':'This shade is darker than the estimated skin-depth group.';
 if(preference==='unknown')return {title:gap<=1?'Depth comparison — undertone not confirmed':'Different depth group',message:depthText+' A camera depth comparison alone does not establish a cushion match.'};
 return {title:gap<=1&&compatible?'A possible shade to swatch':'Not a close match under these rules',message:depthText+' '+(compatible?'Undertone tags are compatible.':'Undertone tags differ.')+' Test a physical jawline swatch before buying.'};
}
