
//document.querySelector(#psearch).addEventListener('enter',)
var search = document.querySelector("#psearch").value;
var search = search.replace(/\D/g, "");
//console.log(search.length);

document.querySelector('#nw').addEventListener('mouseover',overing);
document.querySelector('#nw').addEventListener('mouseout',outing);
document.querySelector('#f1').addEventListener('mouseover',overing);
document.querySelector('#f1').addEventListener('mouseout',outing);
document.querySelector('#f2').addEventListener('mouseover',overing);
document.querySelector('#f2').addEventListener('mouseout',outing);
document.querySelector('#f3').addEventListener('mouseover',overing);
document.querySelector('#f3').addEventListener('mouseout',outing);

function overing(ev){
  ev.currentTarget.style.border = '5px solid red';
}
function outing(ev){
  ev.currentTarget.style.border = '5px solid rgba(0,0,0,.5)';
}
