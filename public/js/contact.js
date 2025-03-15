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
        const res = await fetch("http://localhost:8000/api/v1/contacts", {
            headers: {
                Authorization: "Bearer " + token,
            },
        });

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
    document.getElementById("modalTitle").textContent = contactId
        ? "Editar Contato"
        : "Adicionar Contato";
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
    window.location.href = "login.html";
}
