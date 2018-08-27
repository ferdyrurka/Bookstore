Projekt LukSta BookStore
--

Założenia
--
**Admin**

- Dodawanie, aktualizowanie, kasowanie produktów.
- Dodawanie, aktualizowanie, kasowanie kategori.
- Produkt może posiadać wiele kategori a kategoria wiele produktów.
- Kasowanie, aktywacja, dezaktywacja użytkowników.
- Dodawanie administratorów.
- Możliwość dodania obrazka wyróżniającego dla produktu.
- Logowanie.

**Sklep**

- Dodawanie produktów do koszyka.
- Aktualizacja ilości sztuk oraz kasowanie produktów z koszyka.
- Zamawianie produktów (One page checkout).
- Logowanie, rejestracja.
- Prosta wyszukiwarka produktów.
- Zapisywanie wejść w produkt tj. użytkownik, urządzenie, produkt.
- Wyświetlanie na stronie głównej popularnych produktów oraz nowych produktów
- Przypomnienie hasła.

**Ogólne**

- Użytkownik oraz administrator powinni mieć możliwość zmiany maila, hasła, imienia oraz nazwiska.
- Możliwość zapamiętania użytkownika oraz administora.
- Zabezpieczenie przed atakiem na sesję.
- Maile powinny być wysyłane za pomocą funkcji mail.
- Wykonaj TDD za pomocą PHPUnit oraz Mockery.
- Framework: Symfony4.
- ORM: Doctrine.
- W repozytorium ma się znajdować raport code coverage.

Co dał mi projekt
--

Projekt dał mi podstawową wiedzę z zakresu frameworka Symfony4, twig oraz ORM Doctrine. 
Dzięki niemu nauczyłem się również podstaw testów jednostkowych z użyciem PHPUnit oraz Mockery.

Jakie błędy popełniłem
--

Na pewno przy następnych projektach nie będę wykonywać TDD kontrollerów tylko i wyłącznie za pomocą WebTestCase.
Zaniedbałem repozytorium od samego początku, co spowodowało złe utrzymanie w końcowym efekcie.

Inne
--

**Dane logowania**

**Panel administora:** /admin1999
* Administrator
* admin1234

**Użytkownik:**
* Ferdyrurka
* admin1234