const currencyFormatter = new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XAF',
    maximumFractionDigits: 0,
});

const dateFormatter = new Intl.DateTimeFormat('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
});

export function formatCurrency(value) {
    const number = Number(value ?? 0);
    return currencyFormatter.format(Number.isFinite(number) ? number : 0);
}

export function formatDate(value) {
    if (!value) return '';
    const date = value instanceof Date ? value : new Date(value);
    return Number.isNaN(date.getTime()) ? '' : dateFormatter.format(date);
}

export function useFormat() {
    return { formatCurrency, formatDate };
}
