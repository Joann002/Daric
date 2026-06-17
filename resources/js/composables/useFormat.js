const numberFormatter = new Intl.NumberFormat('fr-FR', {
    maximumFractionDigits: 0,
});

const dateFormatter = new Intl.DateTimeFormat('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
});

export function formatCurrency(value) {
    const number = Number(value ?? 0);
    return `${numberFormatter.format(Number.isFinite(number) ? number : 0)} Ar`;
}

export function formatDate(value) {
    if (!value) return '';
    const date = value instanceof Date ? value : new Date(value);
    return Number.isNaN(date.getTime()) ? '' : dateFormatter.format(date);
}

export function useFormat() {
    return { formatCurrency, formatDate };
}
