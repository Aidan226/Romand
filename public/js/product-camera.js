import {assess} from './shade-assessment.mjs';
import './shade-camera.js';
const $=id=>document.getElementById(id);
const form=$('product-cart-form'), panel=$('inline-shade-check');
let sample=null;
const selected=()=>form.querySelector('input[name="shade_name"]:checked');
function clearResult(){sample=null;$('inline-result').hidden=true;$('capture-preview').hidden=true;$('profile-adjustments').hidden=true;}
function render(){
 const input=selected();if(!input||!sample)return;
 const shade={reviewed:input.dataset.reviewed==='1',undertone:input.dataset.undertone,depth:input.dataset.depth?Number(input.dataset.depth):null};
 const result=assess(shade,{...sample,depth:Number($('depth').value)},$('undertone').value,panel.dataset.type,panel.dataset.demo==='1');
 $('result-title').textContent=result.title;$('result-message').textContent=result.message;
 $('result-detail').textContent='Sampled skin / '+input.value+' · Estimated depth '+$('depth').value;
 $('skin-swatch').style.setProperty('--c',`rgb(${sample.rgb.join(',')})`);
 $('shade-swatch').style.setProperty('--c',/^#[a-f0-9]{6}$/i.test(input.dataset.shadeHex)?input.dataset.shadeHex:'#ddd');
 $('inline-result').hidden=false;$('profile-adjustments').hidden=false;
}
function update(){
 const input=selected();const stock=input?Number(input.dataset.stock):0;
 $('add-to-cart').disabled=stock<1;$('shade-quantity').disabled=stock<1;
 $('open-camera').disabled=!input;
 if(!input)return;
 $('selected-shade-id').value=input.dataset.shadeId;
 $('shade-quantity').max=Math.min(stock,50)||1;
 $('shade-quantity').value=Math.max(1,Math.min(Number($('shade-quantity').value)||1,stock,50));
 $('checking-name').textContent=input.value;
 $('shade-selection-status').textContent=stock>0?'Selected shade: '+input.value:'Out of stock — camera check is still available.';
 const old=$('product-photo');let replacement;
 if(input.dataset.image){replacement=document.createElement('img');replacement.src=input.dataset.image;replacement.alt=input.value;replacement.className='product-image';replacement.style.height='420px';}
 else{replacement=document.createElement('div');replacement.style.cssText='height:330px;border-radius:8px';replacement.style.backgroundColor=/^#[a-f0-9]{6}$/i.test(input.dataset.shadeHex)?input.dataset.shadeHex:'#ddd';}
 replacement.id='product-photo';old.replaceWith(replacement);
}
form.querySelectorAll('input[name="shade_name"]').forEach(r=>r.addEventListener('change',()=>{clearResult();$('close-camera').click();panel.hidden=true;update();}));
document.addEventListener('shade-camera:opening',clearResult);
document.addEventListener('shade-camera:captured',event=>{sample=event.detail;render();});
$('depth').addEventListener('change',render);$('undertone').addEventListener('change',render);
update();
