// Photos are processed locally. Model assets are downloaded only when camera is opened.
const $=id=>document.getElementById(id);
let stream=null, detector=null, opening=false, generation=0;
const status=text=>{$('camera-status').textContent=text;};
function close(){generation++;if(stream)stream.getTracks().forEach(t=>t.stop());stream=null;$('camera').srcObject=null;$('camera').hidden=true;$('capture-camera').disabled=true;}
$('close-camera').onclick=()=>{close();status('Camera closed. You can use manual selections.');};
window.addEventListener('pagehide',close);
$('open-camera').onclick=async()=>{
 document.dispatchEvent(new CustomEvent('shade-camera:opening'));
 const panel=$('inline-shade-check');if(panel){panel.hidden=false;panel.scrollIntoView({behavior:'smooth',block:'nearest'});}
 if(opening)return;opening=true;$('open-camera').disabled=true;
 try{
  close();const attempt=generation;status('Loading face detection. Your camera image stays on this device.');
  if(!navigator.mediaDevices?.getUserMedia)throw Error('Camera needs localhost or HTTPS. Use manual selections instead.');
  if(!detector){const {FaceLandmarker,FilesetResolver}=await import('https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.32/vision_bundle.mjs');const files=await FilesetResolver.forVisionTasks('https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.32/wasm');detector=await FaceLandmarker.createFromOptions(files,{baseOptions:{modelAssetPath:'https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task'},runningMode:'IMAGE',numFaces:2});}
  if(attempt!==generation)return;
  const incoming=await navigator.mediaDevices.getUserMedia({video:{facingMode:'user',width:{ideal:640}},audio:false});if(attempt!==generation){incoming.getTracks().forEach(t=>t.stop());return;}stream=incoming;$('camera').srcObject=stream;$('camera').hidden=false;await $('camera').play();$('capture-camera').disabled=false;status('Face forward in even light, then capture. Keep both cheeks visible.');
 }catch(e){close();status('Camera unavailable: '+e.message+' Continue with manual selections.');}finally{opening=false;$('open-camera').disabled=false;}
};
function median(a){a.sort((x,y)=>x-y);return a[Math.floor(a.length/2)];}
$('capture-camera').onclick=()=>{
 try{
 const video=$('camera');if(!video.videoWidth)throw Error('Camera is not ready.');
 const canvas=$('capture-preview');canvas.width=video.videoWidth;canvas.height=video.videoHeight;const ctx=canvas.getContext('2d',{willReadFrequently:true});ctx.drawImage(video,0,0);
 const faces=detector.detect(canvas).faceLandmarks;if(faces.length!==1)throw Error('Exactly one face must be visible.');const face=faces[0];
 const width=Math.abs(face[454].x-face[234].x)*canvas.width;if(width<canvas.width*.22)throw Error('Move closer to the camera.');
 const rgbSamples=[];const light=[];let clipped=0,total=0;const samples=[];
 for(const index of [50,280]){const x=Math.round(face[index].x*canvas.width),y=Math.round(face[index].y*canvas.height),rad=Math.max(4,Math.round(width*.045));if(x-rad<0||y-rad<0||x+rad>=canvas.width||y+rad>=canvas.height)throw Error('Center your face.');const pixels=ctx.getImageData(x-rad,y-rad,rad*2,rad*2).data;const local=[];for(let i=0;i<pixels.length;i+=4){const rgb=[pixels[i],pixels[i+1],pixels[i+2]];total++;if(Math.max(...rgb)>250||Math.max(...rgb)<12){clipped++;continue;}rgbSamples.push(rgb);const lin=rgb.map(v=>{v/=255;return v<=.04045?v/12.92:Math.pow((v+.055)/1.055,2.4);});const Y=.2126*lin[0]+.7152*lin[1]+.0722*lin[2];const L=116*(Y>.008856?Math.cbrt(Y):7.787*Y+16/116)-16;local.push(L);}if(!local.length)throw Error('Skin sample is clipped. Adjust the light.');light.push(median(local));samples.push({x,y,rad});}
 if(clipped/total>.2)throw Error('Too many overexposed or underexposed pixels. Adjust lighting.');if(Math.abs(light[0]-light[1])>12)throw Error('Lighting is uneven across your cheeks. Face the light source.');
 const L=(light[0]+light[1])/2;
 // Illustrative thresholds only; these are not validated skin tone diagnoses.
 const depth=L>78?1:L>68?2:L>58?3:L>48?4:L>36?5:6;$('depth').value=String(depth);
 ctx.strokeStyle='#fff';ctx.lineWidth=3;for(const {x,y,rad}of samples)ctx.strokeRect(x-rad,y-rad,2*rad,2*rad);canvas.hidden=false;
 const rgb=[0,1,2].map(i=>Math.round(median(rgbSamples.map(c=>c[i]))));
 document.dispatchEvent(new CustomEvent('shade-camera:captured',{detail:{depth,rgb}}));
 close();status('Captured both cheek samples. Suggested depth group '+depth+'. Confirm or adjust it. Undertone has not been inferred.');
 }catch(e){status(e.message+' You can also continue manually.');}
};
