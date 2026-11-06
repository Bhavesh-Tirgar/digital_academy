/**
 * DIGITAL ACADEMY - GLOBALIZATION ENGINE
 * Multi-Currency & Multi-Language Country Switcher
 * Real-Time Currency Conversion & Client-Side i18n
 */

(function () {
    // Currency Exchange Rates & Symbols (Base USD = 1.0)
    const CURRENCIES = {
        'USD': { code: 'USD', symbol: '$', rate: 1.0, flag: '🇺🇸', name: 'USD ($)', country: 'United States' },
        'INR': { code: 'INR', symbol: '₹', rate: 83.0, flag: '🇮🇳', name: 'INR (₹)', country: 'India' },
        'EUR': { code: 'EUR', symbol: '€', rate: 0.92, flag: '🇪🇺', name: 'EUR (€)', country: 'European Union' },
        'GBP': { code: 'GBP', symbol: '£', rate: 0.79, flag: '🇬🇧', name: 'GBP (£)', country: 'United Kingdom' },
        'CAD': { code: 'CAD', symbol: 'CA$', rate: 1.36, flag: '🇨🇦', name: 'CAD ($)', country: 'Canada' },
        'AUD': { code: 'AUD', symbol: 'AU$', rate: 1.52, flag: '🇦🇺', name: 'AUD ($)', country: 'Australia' }
    };

    // Lightweight i18n Dictionary
    const TRANSLATIONS = {
        'en': {
            'nav_home': 'Home',
            'nav_courses': 'Courses',
            'nav_live': 'Live Schedule',
            'nav_ebooks': 'E-Book Slot',
            'nav_membership': 'Membership',
            'nav_reviews': 'Reviews',
            'nav_teach': 'Teach & Earn (80%)',
            'nav_login': 'Log In',
            'nav_register': 'Get Started',
            'nav_dashboard': 'Student Dashboard',
            'nav_instructor_portal': 'Instructor Portal',
            'live_now_ticker': 'Live AI Masterclass is in session!',
            'join_live_now': 'Join Live Class',
            'hero_badge': 'VETTED HIGH-PERFORMANCE ED-TECH',
            'hero_cta_courses': 'Explore Verified Cohorts',
            'hero_cta_teach': 'Teach on Platform'
        },
        'hi': {
            'nav_home': 'होम',
            'nav_courses': 'कोर्सेस',
            'nav_live': 'लाइव कक्षाएं',
            'nav_ebooks': 'ई-बुक्स स्लॉट',
            'nav_membership': 'सदस्यता',
            'nav_reviews': 'समीक्षाएं',
            'nav_teach': 'सिखाएं और कमाएं (80%)',
            'nav_login': 'लॉग इन',
            'nav_register': 'शुरू करें',
            'nav_dashboard': 'विद्यार्थी डैशबोर्ड',
            'nav_instructor_portal': 'शिक्षक पोर्टल',
            'live_now_ticker': 'लाइव एआई मास्टरक्लास जारी है!',
            'join_live_now': 'लाइव क्लास से जुड़ें',
            'hero_badge': 'सत्यापित डिजिटल अकादमी',
            'hero_cta_courses': 'कोर्स खोजें',
            'hero_cta_teach': 'अध्यापक बनें'
        },
        'es': {
            'nav_home': 'Inicio',
            'nav_courses': 'Cursos',
            'nav_live': 'Clases en Vivo',
            'nav_ebooks': 'E-Books',
            'nav_membership': 'Membresía',
            'nav_reviews': 'Reseñas',
            'nav_teach': 'Enseña y Gana (80%)',
            'nav_login': 'Iniciar Sesión',
            'nav_register': 'Empezar',
            'nav_dashboard': 'Panel de Estudiante',
            'nav_instructor_portal': 'Portal de Instructor',
            'live_now_ticker': '¡Masterclass de IA en vivo ahora!',
            'join_live_now': 'Unirse a la Clase',
            'hero_badge': 'PLATAFORMA EDUCATIVA VERIFICADA',
            'hero_cta_courses': 'Explorar Cursos',
            'hero_cta_teach': 'Enseñar en la Plataforma'
        },
        'fr': {
            'nav_home': 'Accueil',
            'nav_courses': 'Cours',
            'nav_live': 'Directs & Horaires',
            'nav_ebooks': 'E-Books',
            'nav_membership': 'Abonnement',
            'nav_reviews': 'Avis',
            'nav_teach': 'Enseigner (80%)',
            'nav_login': 'Connexion',
            'nav_register': 'Commencer',
            'nav_dashboard': 'Tableau Étudiant',
            'nav_instructor_portal': 'Portail Formateur',
            'live_now_ticker': 'Masterclass IA en direct actuellement!',
            'join_live_now': 'Rejoindre le Direct',
            'hero_badge': 'ACADÉMIE NUMÉRIQUE CERTIFIÉE',
            'hero_cta_courses': 'Explorer les Cours',
            'hero_cta_teach': 'Devenir Formateur'
        },
        'de': {
            'nav_home': 'Startseite',
            'nav_courses': 'Kurse',
            'nav_live': 'Live-Stunden',
            'nav_ebooks': 'E-Books',
            'nav_membership': 'Mitgliedschaft',
            'nav_reviews': 'Bewertungen',
            'nav_teach': 'Unterrichten (80%)',
            'nav_login': 'Anmelden',
            'nav_register': 'Loslegen',
            'nav_dashboard': 'Studenten-Dashboard',
            'nav_instructor_portal': 'Dozenten-Portal',
            'live_now_ticker': 'Live KI-Masterclass läuft gerade!',
            'join_live_now': 'Live beitreten',
            'hero_badge': 'VERIFIZIERTE DIGITAL AKADEMIE',
            'hero_cta_courses': 'Kurse Durchsuchen',
            'hero_cta_teach': 'Dozent Werden'
        }
    };

    // Active preferences
    let currentCurrency = localStorage.getItem('da_currency') || 'USD';
    let currentLang = localStorage.getItem('da_lang') || 'en';

    if (!CURRENCIES[currentCurrency]) currentCurrency = 'USD';
    if (!TRANSLATIONS[currentLang]) currentLang = 'en';

    // Format currency amount
    window.formatPlatformPrice = function (baseUsdAmount, currencyCode) {
        const curr = CURRENCIES[currencyCode || currentCurrency] || CURRENCIES['USD'];
        const converted = baseUsdAmount * curr.rate;
        
        if (curr.code === 'INR') {
            return `${curr.symbol}${Math.round(converted).toLocaleString('en-IN')}`;
        } else if (curr.code === 'EUR' || curr.code === 'GBP') {
            return `${curr.symbol}${converted.toFixed(2)}`;
        } else {
            return `${curr.symbol}${converted.toFixed(2)}`;
        }
    };

    window.getPlatformCurrency = function () {
        return CURRENCIES[currentCurrency];
    };

    window.setPlatformCurrency = function (code) {
        if (!CURRENCIES[code]) return;
        currentCurrency = code;
        localStorage.setItem('da_currency', code);
        updateCurrencyUI();
        convertAllPagePrices();
        window.dispatchEvent(new CustomEvent('daCurrencyChanged', { detail: CURRENCIES[code] }));
    };

    window.setPlatformLanguage = function (lang) {
        if (!TRANSLATIONS[lang]) return;
        currentLang = lang;
        localStorage.setItem('da_lang', lang);
        updateLanguageUI();
        applyTranslations();
        window.dispatchEvent(new CustomEvent('daLangChanged', { detail: lang }));
    };

    function updateCurrencyUI() {
        const btn = document.getElementById('currencySelectorBtn');
        if (btn) {
            const c = CURRENCIES[currentCurrency];
            btn.innerHTML = `<span>${c.flag} ${c.code} (${c.symbol})</span> <i class="fas fa-chevron-down fa-2xs ms-1"></i>`;
        }
    }

    function updateLanguageUI() {
        const label = document.getElementById('currentLangLabel');
        if (label) {
            label.textContent = currentLang.toUpperCase();
        }
    }

    function convertAllPagePrices() {
        const c = CURRENCIES[currentCurrency];
        const elements = document.querySelectorAll('[data-price-usd]');
        elements.forEach(el => {
            const baseUsd = parseFloat(el.getAttribute('data-price-usd'));
            if (!isNaN(baseUsd)) {
                if (c.code === 'INR') {
                    el.textContent = `${c.symbol}${Math.round(baseUsd * c.rate).toLocaleString('en-IN')}`;
                } else if (baseUsd % 1 === 0 && c.code === 'USD') {
                    el.textContent = `${c.symbol}${baseUsd}`;
                } else {
                    el.textContent = `${c.symbol}${(baseUsd * c.rate).toFixed(2)}`;
                }
            }
        });

        // Also check elements with class .dynamic-usd-price
        const dynamicPrices = document.querySelectorAll('.dynamic-usd-price');
        dynamicPrices.forEach(el => {
            const raw = el.getAttribute('data-raw-usd') || el.textContent.replace(/[^0-9.]/g, '');
            const base = parseFloat(raw);
            if (!isNaN(base)) {
                el.setAttribute('data-raw-usd', base);
                el.textContent = window.formatPlatformPrice(base, currentCurrency);
            }
        });
    }

    function applyTranslations() {
        const dict = TRANSLATIONS[currentLang] || TRANSLATIONS['en'];
        const elements = document.querySelectorAll('[data-i18n]');
        elements.forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (dict[key]) {
                el.textContent = dict[key];
            }
        });
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        updateCurrencyUI();
        updateLanguageUI();
        convertAllPagePrices();
        applyTranslations();
    });

})();
