let menutup = () => {
  var buka = document.getElementById("buttonbuka");
  var tutup = document.getElementById("buttontutup");
  var detailbuka = document.getElementById("detail_buka");
  var detailtutup = document.getElementById("detail_tutup");
  detailbuka.removeAttribute("hidden");
  detailtutup.setAttribute("hidden", "hidden");
  buka.removeAttribute("hidden");
  tutup.setAttribute("hidden", "hidden");

  document.getElementById("kepala").setAttribute("class", "text-center");
  document
    .getElementById("tabel")
    .setAttribute("class", "table table-bordered table-secondary");
};

let membuka = () => {
  var buka = document.getElementById("buttonbuka");
  var tutup = document.getElementById("buttontutup");
  var detailbuka = document.getElementById("detail_buka");
  var detailtutup = document.getElementById("detail_tutup");
  // var putaran = document.getElementById("putaran");
  // var turn = document.getElementById("putaran").innerHTML;
  buka.setAttribute("hidden", "hidden");
  tutup.removeAttribute("hidden");
  detailbuka.setAttribute("hidden", "hidden");
  detailtutup.removeAttribute("hidden");
  // putaran.removeAttribute("hidden");
  // var i = i + 1;
  // document.getElementById("putaran").innerHTML = "Putaran " + i;

  document
    .getElementById("kepala")
    .setAttribute("class", "text-center table-primary");
  document
    .getElementById("tabel")
    .setAttribute("class", "table table-bordered table-striped");
};
