<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Agenda Digital</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
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
  <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>