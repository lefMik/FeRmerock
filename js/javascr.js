window.onload = function () {
  window.scroll(0, sessionStorage["scroll"]);

  errText();
  let inpTex = Array.from(
    document.querySelectorAll(
      '.aurhreg form div input[type="text"], input[type="password"]'
    )
  );
  inpTex.forEach((element) => {
    element.addEventListener("change", function () {
      errText();
    });
  });

  function errText() {
    let inpTex = document.querySelectorAll(
      '.aurhreg form div input[type="text"], input[type="password"]'
    );
    for (i = 0; i < inpTex.length; i++) {
      if (inpTex[i].value == "") {
        document.querySelector("#butt").disabled = true;
        break;
      } else {
        document.querySelector("#butt").disabled = false;
      }
    }
  }

  window.onscroll = function () {
    sessionStorage["scroll"] =
      window.pageYOffset !== undefined
        ? window.pageYOffset
        : (
            document.documentElement ||
            document.body.parentNode ||
            document.body
          ).scrollTop;
  };

  //message taken
  function messagebox() {
    let message = document.querySelector(".messageokno.actione");
    if (message != null) {
      message.classList.toggle("actione");
    }
  }
  setTimeout(messagebox, 3000);

  //animation
  let slid = document.querySelector(".poz-3 .conteyner .soderjimoe");
  if (slid != null) {
    slid.classList.toggle("actione");
  }
  function slider() {
    let slids = Array.from(document.getElementsByClassName("soderjimoe"));
    for (let i = 0; i < slids.length; i++) {
      if (slids[i].classList.contains("actione")) {
        slids[i].classList.toggle("actione");
        slids[(i + 1) % slids.length].classList.toggle("actione");
        break;
      }
    }
  }
  setInterval(slider, 10000);
};

function accordionOnClick(element) {
  element.classList.toggle("action");
}

function accordionOn(element) {
  element.parentNode.classList.toggle("action");
}
