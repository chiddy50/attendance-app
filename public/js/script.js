
// $("#menu-toggle").click(function(e) {
//   e.preventDefault();
//   $("#wrapper").toggleClass("toggled");
// }); 

document.querySelector("#menu-toggle").addEventListener('click', function(e){
    e.preventDefault();
    document.querySelector("#wrapper").classList.toggle("toggled");
});

var acc = document.getElementsByClassName("dropdown");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
        
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
