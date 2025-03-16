let editingContactId = null;
const contactModal = new bootstrap.Modal(
    document.getElementById("contactModal")
);
document.getElementById("exportCsvBtn").addEventListener("click", function () {
    exportContactsToExcel();
});

async function exportContactsToExcel() {
    const token = localStorage.getItem("token");
    if (!token) {
        window.location.href = "login.html";
        return;
    }

    try {
        const response = await fetch(
            "http://localhost:8000/api/v1/export-excel",
            {
                headers: {
                    Authorization: "Bearer " + token,
                },
            }
        );

        if (!response.ok) throw new Error("Erro ao exportar contatos");

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "contatos.xlsx";
        document.body.appendChild(a);
        a.click();
        a.remove();
    } catch (error) {
        console.error("Erro ao exportar contatos:", error);
        alert("Erro ao exportar contatos. Tente novamente.");
    }
}
document.addEventListener("DOMContentLoaded", function () {
    loadContacts();
    document
        .getElementById("contact-phone")
        .addEventListener("input", function (e) {
            e.target.value = formatPhoneNumber(e.target.value);
        });
});

function formatPhoneNumber(phone) {
    const cleaned = ("" + phone).replace(/\D/g, "");
    if (cleaned.length === 11) {
        return cleaned.replace(/(\d{2})(\d{1})(\d{4})(\d{4})/, "($1)$2.$3-$4");
    } else if (cleaned.length === 10) {
        return cleaned.replace(/(\d{2})(\d{4})(\d{4})/, "($1)$2-$3");
    } else {
        return phone;
    }
}

function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    return date.toLocaleString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
}

async function loadContacts() {
    const token = localStorage.getItem("token");
    if (!token) {
        window.location.href = "login.html";
        return;
    }

    const filter = document.getElementById("contact-filter").value;
    const searchQuery = document
        .getElementById("search-contact")
        .value.toLowerCase();

    let url = "http://localhost:8000/api/v1/contacts";
    if (filter !== "all") {
        url += `?filter=${filter}`;
    }

    try {
        const res = await fetch(url, {
            headers: {
                Authorization: "Bearer " + token,
            },
        });

        if (!res.ok) throw new Error("Erro ao carregar contatos");

        const data = await res.json();

        const filteredContacts = data.filter((contact) =>
            contact.name.toLowerCase().includes(searchQuery)
        );

        renderContacts(filteredContacts);
    } catch (error) {
        console.error("Erro ao carregar contatos:", error);
        alert("Erro ao carregar contatos. Tente novamente.");
    }
}

function renderContacts(contacts) {
    const contactList = document.getElementById("contact-list");
    contactList.innerHTML = "";

    if (contacts.length === 0) {
        contactList.innerHTML =
            "<li class='list-group-item text-center'>Nenhum contato encontrado.</li>";
        return;
    }

    contacts.forEach((contact) => {
        const li = document.createElement("li");
        li.className =
            "list-group-item d-flex justify-content-between align-items-center overflow-auto fixed-height-item";
        li.setAttribute("data-id", contact.id);
        li.innerHTML = `
            <div>
                <strong>${contact.name}</strong> - ${formatPhoneNumber(
            contact.phone
        )}
                <br><small>${contact.email}</small>
                <br><small>${
                    contact.observations || "Nenhuma observação"
                }</small>
                ${
                    contact.deleted_at
                        ? `<br><small>${formatDateTime(
                              contact.deleted_at
                          )}</small>`
                        : `<br><small>${formatDateTime(
                              contact.created_at
                          )}</small>`
                }
                
                
            </div>
            <div>
                ${
                    contact.deleted_at
                        ? `<span class='badge bg-danger'>Excluído</span>`
                        : "<span class='badge bg-success'>Ativo</span>"
                }
                <button class="btn btn-warning btn-sm" onclick="editContact(${
                    contact.id
                })">✏️ Editar</button>
                <button class="btn btn-danger btn-sm" onclick="deleteContact(${
                    contact.id
                })">🗑️ Excluir</button>
            </div>
        `;
        contactList.appendChild(li);
    });
}

function openModal(contactId = null) {
    editingContactId = contactId;
    document.getElementById("modalTitle").textContent = contactId
        ? "Editar Contato"
        : "Adicionar Contato";

    if (contactId) {
        const contact = document.querySelector(
            `#contact-list li[data-id="${contactId}"]`
        );

        if (!contact) return;

        const name = contact.querySelector("strong").textContent.trim();
        const email = contact.querySelector("small").textContent.trim();

        const phoneText = contact.innerHTML.match(
            /- (\(\d{2}\)\d{1}\.\d{4}-\d{4}|\(\d{2}\)\d{4}-\d{4})/
        );
        const phone = phoneText ? phoneText[1] : "";

        const smallElements = contact.querySelectorAll("small");
        const observations =
            smallElements.length > 1 ? smallElements[1].textContent.trim() : "";

        document.getElementById("contact-name").value = name;
        document.getElementById("contact-phone").value = phone;
        document.getElementById("contact-email").value = email;
        document.getElementById("contact-notes").value = observations;
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

function isValidEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailPattern.test(email);
}

async function saveContact() {
    const token = localStorage.getItem("token");
    const name = document.getElementById("contact-name").value;
    const phone = document.getElementById("contact-phone").value;
    const email = document.getElementById("contact-email").value;
    const observations = document.getElementById("contact-notes").value;

    if (!isValidEmail(email)) {
        alert("Por favor, insira um email válido.");
        return;
    }

    const data = {
        name,
        phone,
        email,
        observations,
    };

    try {
        const url = editingContactId
            ? `http://localhost:8000/api/v1/contacts/${editingContactId}`
            : "http://localhost:8000/api/v1/contacts";
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
        `http://localhost:8000/api/v1/contacts/${contactId}`,
        {
            method: "DELETE",
            headers: {
                Authorization: "Bearer " + token,
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
    window.location.href = "login";
}
