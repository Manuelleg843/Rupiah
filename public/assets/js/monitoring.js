// Menutup putaran
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

  $.ajax({
    type: "POST",
    url: "/monitoring/updateStatus",
    dataType: "json",
    error: function (error) {
      console.error("Terjadi kesalahan:", error);
    },
  });
};

// Membuka putaran
let membuka = (event) => {
  event.preventDefault();
  window.open(event.target.href, "_self");
};

$(function () {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
  });
  $(".swalDefaultBuka").click(function () {
    Toast.fire({
      icon: "success",
      title: "Putaran telah berhasil dibuka, mengarahkan kembali...",
    });
    clearTimeout(toastTimeout);
  });
  $(".swalDefaultTutup").click(function () {
    Toast.fire({
      icon: "success",
      title: "Putaran telah berhasil ditutup",
    });
    toastTimeout = setTimeout(function () {
      Toast.close();
    }, 3000);
  });
});
