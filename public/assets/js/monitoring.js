// Membuka/menutup putaran
let hrefModal = (event) => {
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
