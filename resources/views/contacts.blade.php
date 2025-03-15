<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meus Contatos</title>
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
            color: var(--text-light);
            font-family: "Arial", sans-serif;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--bg-light-dark);
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
            border-right: 1px solid var(--border-color);
        }

        .sidebar h3 {
            margin-bottom: 20px;
            color: var(--primary);
            font-weight: bold;
        }

        .sidebar .nav-link {
            color: var(--text-light);
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            transition: 0.3s;
            display: block;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background-color: var(--primary);
            color: #fff;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: var(--bg-dark);
        }

        .btn-primary,
        .btn-success,
        .btn-danger,
        .btn-warning {
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            transition: 0.3s ease-in-out;
        }

        .btn-primary {
            background-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #008ecc;
        }

        .list-group-item {
            background-color: var(--bg-light-dark);
            color: var(--text-light);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
        }

        .list-group-item:hover {
            background-color: #242424;
        }

        .btn-sm {
            border-radius: 6px;
            padding: 6px 10px;
        }

        /* Modal estilizado */
        .modal-content {
            background-color: var(--bg-light-dark);
            border-radius: 12px;
            color: var(--text-light);
        }

        .modal-header {
            background-color: var(--primary);
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .modal-footer {
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        input,
        textarea {
            background-color: #222;
            border: 1px solid var(--border-color);
            color: var(--text-light);
            border-radius: 6px;
            padding: 10px;
        }

        input::placeholder,
        textarea::placeholder {
            color: var(--text-muted);
        }

        input:focus,
        textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 5px rgba(0, 170, 255, 0.5);
        }
    </style>
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

        <!-- Main Content -->
        <div class="main-content">
            <h3 class="text-center mb-4">📇 Meus Contatos</h3>
            <button class="btn btn-primary mb-3" onclick="openModal()">
                ➕ Adicionar Contato
            </button>

            <!-- Lista de Contatos -->
            <ul id="contact-list" class="list-group"></ul>
        </div>
    </div>

    <!-- Modal de Adição/Alteração de Contato -->
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
                    <textarea
                        id="contact-notes"
                        class="form-control"
                        placeholder="Observações"></textarea>
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
    <script>
        let editingContactId = null;
        const contactModal = new bootstrap.Modal(
            document.getElementById("contactModal")
        );

        async function loadContacts() {
            const token = localStorage.getItem("token");
            if (!token) {
                window.location.href = "login.html";
                return;
            }

            try {
                const res = await fetch(
                    "http://localhost:8000/api/v1/contacts", {
                        headers: {
                            Authorization: "Bearer " + token
                        },
                    }
                );

                if (!res.ok) throw new Error("Erro ao carregar contatos");

                const data = await res.json();
                renderContacts(data);
            } catch (error) {
                console.error("Erro ao carregar contatos:", error);
                alert("Erro ao carregar contatos. Tente novamente.");
            }
        }

        function renderContacts(contacts) {
            const contactList = document.getElementById("contact-list");
            contactList.innerHTML = "";

            contacts.forEach((contact) => {
                const li = document.createElement("li");
                li.className = "list-group-item";
                li.setAttribute("data-id", contact.id);
                li.innerHTML = `
                    <div>
                        <strong>${contact.name}</strong> - ${contact.phone}
                        <br><small>${contact.email}</small>
                        <br><small>${contact.observations}</small>
                    </div>
                    <div>
                        <button class="btn btn-warning btn-sm" onclick="editContact(${contact.id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteContact(${contact.id})">Excluir</button>
                    </div>
                `;
                contactList.appendChild(li);
            });
        }

        function openModal(contactId = null) {
            editingContactId = contactId;
            document.getElementById("modalTitle").textContent = contactId ?
                "Editar Contato" :
                "Adicionar Contato";
            if (contactId) {
                const contact = document.querySelector(
                    `#contact-list li[data-id="${contactId}"]`
                );
                document.getElementById("contact-name").value =
                    contact.querySelector("strong").textContent;
                document.getElementById("contact-phone").value = contact
                    .querySelector("div")
                    .textContent.split(" - ")[1]
                    .trim();
                document.getElementById("contact-email").value = contact
                    .querySelector("small")
                    .textContent.trim();
                document.getElementById("contact-notes").value = contact
                    .querySelectorAll("small")[1]
                    .textContent.trim();
            } else {
                document.getElementById("contact-name").value = "";
                document.getElementById("contact-phone").value = "";
                document.getElementById("contact-email").value = "";
                document.getElementById("contact-notes").value = "";
            }
            contactModal.show();
        }

        function editContact(contactId) {
            openModal(contactId);
        }

        async function saveContact() {
            const token = localStorage.getItem("token");
            const data = {
                name: document.getElementById("contact-name").value,
                phone: document.getElementById("contact-phone").value,
                email: document.getElementById("contact-email").value,
                observations: document.getElementById("contact-notes").value,
            };

            try {
                const url = editingContactId ?
                    `http://localhost:8000/api/v1/contacts/${editingContactId}` :
                    "http://localhost:8000/api/v1/contacts";
                const method = editingContactId ? "PUT" : "POST";

                const res = await fetch(url, {
                    method,
                    headers: {
                        Authorization: `Bearer ${token}`,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(data),
                });

                if (res.ok) {
                    loadContacts();
                    contactModal.hide();
                } else {
                    alert("Erro ao salvar contato!");
                }
            } catch (error) {
                console.error("Erro de conexão:", error);
                alert("Erro de conexão!");
            }
        }

        async function deleteContact(contactId) {
            const token = localStorage.getItem("token");
            const res = await fetch(
                `http://localhost:8000/api/v1/contacts/${contactId}`, {
                    method: "DELETE",
                    headers: {
                        Authorization: "Bearer " + token
                    },
                }
            );

            if (res.ok) {
                loadContacts();
            } else {
                alert("Erro ao excluir contato!");
            }
        }

        function logout() {
            localStorage.removeItem("token");
            window.location.href = "login.html";
        }
    </script>
</body>

</html>