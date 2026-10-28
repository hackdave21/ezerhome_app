/**
 * Ezer Home - Thème Tailwind partagé
 * Utilisation : <script>tailwind.config = ezerTheme();</script>
 */
window.ezerTheme = function () {
  return {
    theme: {
      extend: {
        colors: {
          ezer: {
            green: { DEFAULT: '#187d50', dark: '#12603d', light: '#e9f7f0', pale: '#f6faf8', border: '#c8dccf', borderLight: '#d3e6db' },
            dark: '#0e1c03',
            muted: '#5b6656',
            borderMuted: '#6f8577',
            red: { DEFAULT: '#c1272d', light: '#fdf2f2', border: '#f8b4b4' },
            brown: { DEFAULT: '#a67c52', light: '#faf6f2' }
          }
        },
        fontFamily: {
          sans: ['Poppins', 'system-ui, sans-serif'],
          cooper: ['"Cooper Black"', 'Georgia', 'serif']
        },
        borderRadius: { ezer: '12px' },
        boxShadow: { 'ezer-nav': 'inset 0 -3px 0 #187d50' }
      }
    }
  };
};