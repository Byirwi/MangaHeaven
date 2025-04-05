/* ==========================================================================
   SYSTÈME DE THÈME & PRÉFÉRENCES - MangaHeaven
   ========================================================================== */

/** 
 * Fonctionnalité à implémenter:
 * - Mode clair/sombre
 * - Sauvegarde des préférences dans localStorage
 * - Détection auto des préférences système
 */

class ThemeManager {
  constructor() {
    this.themeKey = 'mangaheaven-theme-preference';
    this.darkThemeClass = 'dark-theme';
    this.lightThemeClass = 'light-theme';
    this.initTheme();
  }

  // Get saved theme from localStorage or detect system preference
  getPreferredTheme() {
    const savedTheme = localStorage.getItem(this.themeKey);
    if (savedTheme) {
      return savedTheme;
    }
    
    // Detect system preference
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  // Set theme to either light or dark
  setTheme(theme) {
    if (theme === 'dark') {
      document.documentElement.classList.add(this.darkThemeClass);
      document.documentElement.classList.remove(this.lightThemeClass);
    } else {
      document.documentElement.classList.add(this.lightThemeClass);
      document.documentElement.classList.remove(this.darkThemeClass);
    }
    
    // Save preference to localStorage
    localStorage.setItem(this.themeKey, theme);
    
    // Force update of moon/sun icons - add this
    this.updateThemeIcons(theme);
    
    // Dispatch event for theme change
    document.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
  }
  
  // Add a new method to explicitly update theme icons
  updateThemeIcons(theme) {
    // Directly update all theme icons based on current theme
    if (theme === 'dark') {
      document.querySelectorAll('.icon-moon').forEach(icon => {
        icon.style.opacity = '0';
        icon.style.transform = 'translateY(-20px)';
      });
      document.querySelectorAll('.icon-sun').forEach(icon => {
        icon.style.opacity = '1';
        icon.style.transform = 'translateY(0)';
      });
    } else {
      document.querySelectorAll('.icon-moon').forEach(icon => {
        icon.style.opacity = '1';
        icon.style.transform = 'translateY(0)';
      });
      document.querySelectorAll('.icon-sun').forEach(icon => {
        icon.style.opacity = '0';
        icon.style.transform = 'translateY(20px)';
      });
    }
  }

  // Toggle between light and dark themes
  toggleTheme() {
    const currentTheme = this.getPreferredTheme();
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    this.setTheme(newTheme);
    return newTheme;
  }

  // Initialize theme based on saved preference or system default
  initTheme() {
    // Set initial theme
    const initialTheme = this.getPreferredTheme();
    this.setTheme(initialTheme);
    
    // Also explicitly update icons on init
    this.updateThemeIcons(initialTheme);
    
    // Listen for system preference changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
      if (!localStorage.getItem(this.themeKey)) {
        this.setTheme(e.matches ? 'dark' : 'light');
      }
    });
  }
}

// Create global instance
const themeManager = new ThemeManager();

// Export for use in other modules
window.themeManager = themeManager;
