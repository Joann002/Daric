import { ref } from 'vue';

const STORAGE_KEY = 'daric-theme';
const isDark = ref(false);

function apply(value) {
    isDark.value = value;
    document.documentElement.classList.toggle('dark', value);
}

/**
 * Initialise the theme from localStorage or the OS preference.
 * Call once, as early as possible, to avoid a flash of the wrong theme.
 */
export function initDarkMode() {
    const stored = localStorage.getItem(STORAGE_KEY);
    const prefersDark =
        window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches;
    apply(stored ? stored === 'dark' : prefersDark);
}

export function useDarkMode() {
    function toggle() {
        apply(!isDark.value);
        localStorage.setItem(STORAGE_KEY, isDark.value ? 'dark' : 'light');
    }

    return { isDark, toggle };
}
