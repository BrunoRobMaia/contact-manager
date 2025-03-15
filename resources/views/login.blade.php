<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Agenda Digital</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <style>
    :root {
      --bg-dark: #121212;
      --bg-light-dark: #1e1e1e;
      --primary: #00aaff;
      --text-light: #e0e0e0;
      --text-muted: #9e9e9e;
      --border-color: #333;
    }

    body {
      background-color: var(--bg-dark);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background: var(--bg-light-dark);
      padding: 30px;
      border-radius: 10px;
      color: white;
      width: 350px;
    }

    .user-create {
      display: flex;
      justify-content: center;
      margin-top: 10px;
    }

    .modal-create-user {
      cursor: pointer;
      color: var(--primary);
    }

    .modal-create-user:hover {
      text-decoration: underline;
    }

    .modal-content {
      background-color: var(--bg-light-dark);
      color: var(--text-light);
    }

    .modal-header {
      border-bottom: 1px solid var(--border-color);
    }

    .modal-footer {
      border-top: 1px solid var(--border-color);
    }

    .form-control {
      background-color: var(--bg-dark);
      border: 1px solid var(--border-color);
      color: var(--text-light);
    }

    .form-control:focus {
      background-color: var(--bg-dark);
      border-color: var(--primary);
      color: var(--text-light);
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h3 class="text-center">Agenda Digital</h3>
    <div class="mb-3">
      <label>Email</label>
      <input
        type="email"
        id="email"
        class="form-control"
        placeholder="Digite seu email" />
    </div>
    <div class="mb-3">
      <label>Senha</label>
      <input
        type="password"
        id="password"
        class="form-control"
        placeholder="Digite sua senha" />
    </div>
    <button class="btn btn-primary w-100" onclick="login()">
      Login
    </button>
    <p class="user-create">
      Não tem cadastro?
      <u
        class="modal-create-user"
        data-bs-toggle="modal"
        data-bs-target="#registerModal">cadastre-se</u>
    </p>
    <p id="error-message" class="text-danger text-center mt-2"></p>
  </div>

  <div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cadastrar Usuário</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nome</label>
            <input
              type="text"
              id="register-name"
              class="form-control"
              placeholder="Digite seu nome" />
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input
              type="email"
              id="register-email"
              class="form-control"
              placeholder="Digite seu email" />
          </div>
          <div class="mb-3">
            <label>Senha</label>
            <input
              type="password"
              id="register-password"
              class="form-control"
              placeholder="Digite sua senha" />
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
            Fechar
          </button>
          <button
            type="button"
            class="btn btn-primary"
            onclick="register()">
            Cadastrar
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    async function login() {
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const errorMessage = document.getElementById("error-message");

      try {
        const res = await fetch(
          "http://localhost:8000/api/v1/login", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              email,
              password
            }),
          }
        );

        const data = await res.json();

        if (!res.ok) {
          errorMessage.textContent =
            data.message || "Erro ao fazer login!";
          return;
        }

        if (data.token) {
          localStorage.setItem("token", data.token);
        } else {
          errorMessage.textContent = "Token não recebido!";
        }
      } catch (error) {
        console.error("Erro de conexão:", error);
        errorMessage.textContent = "Erro de conexão!";
      }
    }

    async function register() {
      const name = document.getElementById("register-name").value;
      const email = document.getElementById("register-email").value;
      const password =
        document.getElementById("register-password").value;
      const errorMessage = document.getElementById("error-message");

      try {
        const res = await fetch(
          "http://localhost:8000/api/v1/register", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              name,
              email,
              password
            }),
          }
        );

        const data = await res.json();

        if (!res.ok) {
          errorMessage.textContent =
            data.message || "Erro ao cadastrar usuário!";
          return;
        }
        bootstrap.Modal.getInstance(
          document.getElementById("registerModal")
        ).hide();
        document.getElementById("register-name").value = "";
        document.getElementById("register-email").value = "";
        document.getElementById("register-password").value = "";

        errorMessage.textContent =
          "Usuário cadastrado com sucesso!";
        errorMessage.classList.remove("text-danger");
        errorMessage.classList.add("text-success");
      } catch (error) {
        console.error("Erro de conexão:", error);
        errorMessage.textContent = "Erro de conexão!";
        errorMessage.classList.remove("text-success");
        errorMessage.classList.add("text-danger");
      }
    }
  </script>
</body>

</html>