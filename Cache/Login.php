<?php class_exists('Kernel\Template') or exit; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <link rel="stylesheet" href="/Themes/<?php echo \Kernel\Template::getTheme() ?>/base.css">
    <title>Login</title>
    
<style>
  @keyframes shake {
    10%,
    30%,
    50%,
    70%,
    90% {
      transform: translate3d(-10px, 0, 0);
    }

    20%,
    40%,
    60%,
    80% {
      transform: translate3d(10px, 0, 0);
    }
  }

  .content {
    display: flex;
    justify-content: center;
  }

  .token {
    width: 30px;
    padding: 0.5rem 0;
    text-align: center;
    margin-right: 0.5rem;
  }
</style>


</head>
<body>
    

<nav class="navbar">
    <h3 class="navbar__logo">Inforum</h3>
    <ul class="navbar__list" id="menuList">

    </ul>
</nav>
    
<div
  style="
    animation-duration: 0.8s;
    animation-fill-mode: both;
    animation-name: '';
  "
  id="content"
>
  <form id="form" class="form">
    <h1>Login</h1>

    <div id="form__error" class="form__error"></div>

    <div class="form__group">
      <label class="form__label">Email</label>
      <input id="email" class="form__input" />
      <span class="form__error"></span>
    </div>

    <div class="form__group">
      <label class="form__label">Password</label>
      <input id="password" type="password" class="form__input" />
      <span class="form__error"></span>
    </div>

    <button class="form__button">Login</button>
  </form>

  <div id="token_modal">
    <div class="modal hidden">
      <button class="close-modal">&times;</button>
      <div class="content">
        <div>
          <h3>Enter Code To Continue</h3>
          <div
            class="form__error"
            style="text-align: center"
            id="token_error"
          ></div>
          <div class="content tokens">
            <input type="text" class="token" maxlength="1" />
            <input type="text" class="token" maxlength="1" />
            <input type="text" class="token" maxlength="1" />
            <input type="text" class="token" maxlength="1" />
          </div>
        </div>
      </div>
    </div>
    <div class="overlay hidden"></div>
  </div>

  
    
<script></script>
<script>
  (() => {
    const modal = document.querySelector("#token_modal .modal");
    const overlay = document.querySelector("#token_modal .overlay");
    const closeBtn = document.querySelector("#token_modal .close-modal");

    const openModal = function () {
      modal.classList.remove("hidden");
      overlay.classList.remove("hidden");
    };

    const closeModal = function () {
      modal.classList.add("hidden");
      overlay.classList.add("hidden");
    };

    closeBtn.addEventListener("click", closeModal);
    overlay.addEventListener("click", closeModal);

    let twoFactorEmail = "";
    const content = document.getElementById("content");
    const form = document.getElementById("form");
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const resource = {
        email: document.getElementById("email"),
        password: document.getElementById("password"),
      };

      const formData = new FormData();
      for (let [key, element] of Object.entries(resource)) {
        formData.append(key, element.value);
      }

      const response = await fetch("/auth/login", {
        method: "post",
        body: formData,
      });
      const data = await response.json();
      if (response.status === 400) {
        if (data.message) {
          const error = document.getElementById("form__error");
          error.innerText = data.message;
          doShake(content);
        } else {
          validateForm(resource, data, form);
          doShake(content);
        }
      } else if (data.two_factor_enabled) {
        openModal();
        twoFactorEmail = data.email;
      } else if (data.success) {
        window.location = "/";
      }
    });

    const doShake = (elm) => {
      elm.style.animationName = "shake";
      setTimeout(() => {
        elm.style.animationName = "";
      }, 800);
    };

    const tokens = document.querySelectorAll(".token");
    tokens.forEach((token, index) => {
      token.addEventListener("keyup", async (e) => {
        if (index < tokens.length - 1) {
          tokens[index + 1].focus();
        } else {
          let code = "";

          tokens.forEach((token) => {
            code += token.value;
          });

          if (code.length === 4) {
            document.querySelector("#token_error").innerText = "";
            const data = await checkToken(code);
            if (!data.success && data.isCorrect === false) {
              document.querySelector("#token_error").innerText = "Invalid Code";
            } else if (data.success) {
              window.location = "/";
            }
          }
        }
      });
    });

    const checkToken = async (token) => {
      const formData = new FormData();

      const email = twoFactorEmail;

      formData.append("token", token);
      formData.append("email", document.getElementById("email").value);
      console.log(email.value);

      const response = await fetch("/auth/check-token", {
        method: "post",
        body: formData,
      });

      const data = await response.json();
      return data;
    };
  })();
</script>


    <script src="/Public/js/Form.js"></script>
</body>
</html> 
  
</div>
