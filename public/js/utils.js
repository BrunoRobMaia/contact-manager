export function formatPhoneNumber(phone) {
    const cleaned = ("" + phone).replace(/\D/g, "");

    if (cleaned.length === 11) {
        return cleaned.replace(/(\d{2})(\d{1})(\d{4})(\d{4})/, "($1)$2.$3-$4");
    } else if (cleaned.length === 10) {
        return cleaned.replace(/(\d{2})(\d{4})(\d{4})/, "($1)$2-$3");
    } else {
        return phone;
    }
}
export function formatDateTime(dateTimeString) {
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
