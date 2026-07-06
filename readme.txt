=== PMI Events ===
Contributors: pmi
Tags: events, calendar, shortcode
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.4.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Calendario eventi con shortcode, CPT dedicato e widget interattivo.

== Description ==

Plugin WordPress autonomo per gestire eventi e mostrare un calendario interattivo in homepage.

**Funzionalità incluse:**

* Custom Post Type `pmi_event`
* Meta campi: data, ora, luogo, organizzatore
* Shortcode calendario: `[pmi_calendar]`
* Navigazione mensile AJAX
* Selezione giorno con elenco eventi

**Shortcode**

`[pmi_calendar]`

Attributi opzionali:

* `title` – titolo header (default: Calendario Eventi)
* `calendar_url` – URL pagina calendario completa
* `calendar_link` – testo link footer (default: Vedi calendario)
* `year` – anno iniziale
* `month` – mese iniziale (1-12)
* `date` – data selezionata (Y-m-d)

Esempio:

`[pmi_calendar title="PMI Calendario Eventi" calendar_url="/eventi/"]`

**Shortcode calendario mensile a griglia estesa**

`[pmi_calendar_full]`

Mostra un mese intero con tutti gli eventi visibili direttamente nelle celle (ora, titolo, luogo). Cliccando su un evento o sul giorno si apre un popup con l'elenco completo.

Attributi opzionali:

* `title` – titolo interno usato per le richieste AJAX (default: Eventi)
* `year` – anno iniziale
* `month` – mese iniziale (1-12)
* `event_limit` – numero massimo di eventi mostrati per giorno prima del link "+N altri" (default: 2)

Esempio:

`[pmi_calendar_full event_limit="3"]`

== Installation ==

1. Copia la cartella `pmi-events` in `wp-content/plugins/`
2. Attiva il plugin da **Plugin** in wp-admin
3. Crea eventi da **Eventi PMI**
4. Inserisci `[pmi_calendar]` o `[pmi_calendar_full]` in una pagina o in Elementor (widget Shortcode)

== Changelog ==

= 1.4.0 =
* Nuovi tag dinamici Elementor per costruire il template della pagina evento: Titolo, Descrizione breve, Categoria, Luogo, Organizzatore, Lingua, N. PDU, Prezzo soci, Prezzo non soci, Immagine copertina, URL iscrizione.

= 1.3.0 =
* Nuovo shortcode `[pmi_calendar_full]`: calendario mensile a griglia estesa con eventi inline e popup di dettaglio.
* Design system: colori (viola #4f17a8, arancione #ff6110, celeste #05bfdf) e font Azeret Mono self-hosted.

= 1.2.0 =
* Tag dinamico Elementor per data/ora evento.

= 1.1.0 =
* Backend eventi: tassonomia categorie, prezzi, lingua, PDU, URL registrazione.

= 1.0.0 =
* Prima release: CPT eventi e shortcode calendario.
