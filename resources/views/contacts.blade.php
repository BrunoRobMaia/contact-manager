<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meus Contatos</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
</head>

<body onload="loadContacts()">
    <div class="container-fluid">
        <div class="sidebar">
            <h3>Menu</h3>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadContacts()">📞 Contatos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="logout()">🚪 Sair</a>
                </li>
            </ul>
        </div>



        <div class="main-content">
            <h3 class="text-center mb-4">📇 Meus Contatos</h3>
            <div class="buttons-top">
                <button class="btn btn-primary mb-3" onclick="openModal()">
                    ➕ Adicionar Contato
                </button>
                <div class="mb-3">

                    <input
                        type="text"
                        id="search-contact"
                        class="form-control"
                        placeholder="Digite o nome do contato"
                        oninput="loadContacts()" />
                </div>
                <div class="mb-3">
                    <label for="contact-filter" class="form-label">Filtrar Contatos</label>
                    <select id="contact-filter" class="form-select" onchange="loadContacts()">
                        <option value="active">Ativos</option>
                        <option value="deleted">Excluídos</option>
                        <option value="all">Todos</option>
                    </select>
                </div>
                <div class="mb-3">
                    <button id="exportCsvBtn" class="btn btn-primary">📤 Exportar CSV</button>
                </div>
            </div>
            <ul id="contact-list" class="contact-list"></ul>
        </div>
    </div>


    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        Adicionar Contato
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input
                        type="text"
                        id="contact-name"
                        class="form-control mb-2"
                        placeholder="Nome" />
                    <input
                        type="text"
                        id="contact-phone"
                        class="form-control mb-2"
                        placeholder="Telefone" />
                    <input
                        type="email"
                        id="contact-email"
                        class="form-control mb-2"
                        placeholder="Email" />
                    <input
                        type='text'
                        id="contact-notes"
                        class="form-control"
                        placeholder="Observações"></input>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="saveContact()">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/contact.js') }}"></script>
</body>

</html>