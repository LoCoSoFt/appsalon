function swalConfirmar(e, formReferencia, config) {
    const formulario = document.querySelector(formReferencia);

    e.preventDefault();
    const { mensaje, texto, botonConfirmacion, mensajeConfirmacion } = config;
    
    Swal.fire({
      title: mensaje,
      text: texto,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: botonConfirmacion,
      backdrop: false
    }).then((result) => {
      if (result.isConfirmed) {
          let timerInterval;
          
          Swal.fire({
            title: mensajeConfirmacion,
            backdrop: false,
            // html: "Esta ventana se cerrará en <b></b> millisegundos.",
            timer: 1000,
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              // const timer = Swal.getPopup().querySelector("b");
              // timerInterval = setInterval(() => {
                // timer.textContent = `${Swal.getTimerLeft()}`;
              // }, 80);
            },
            willClose: () => {
              clearInterval(timerInterval);
            }
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                formulario.submit();
                // console.log("I was closed by the timer");
            }
          });
      }
    });

}