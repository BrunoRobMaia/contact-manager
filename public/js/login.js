async function login() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const errorMessage = document.getElementById("error-message");

    try {
        const res = await fetch("http://localhost:8000/api/v1/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ email, password }),
        });

        const data = await res.json();

        if (!res.ok) {
            errorMessage.textContent = data.message || "Erro ao fazer login!";
            return;
        }

        if (data.token) {
            localStorage.setItem("token", data.token);
            window.location.href = "contacts";
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
    const password = document.getElementById("register-password").value;
    const errorMessage = document.getElementById("error-message");

    try {
        const res = await fetch("http://localhost:8000/api/v1/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ name, email, password }),
        });

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

        errorMessage.textContent = "Usuário cadastrado com sucesso!";
        errorMessage.classList.remove("text-danger");
        errorMessage.classList.add("text-success");
    } catch (error) {
        console.error("Erro de conexão:", error);
        errorMessage.textContent = "Erro de conexão!";
        errorMessage.classList.remove("text-success");
        errorMessage.classList.add("text-danger");
    }
}
