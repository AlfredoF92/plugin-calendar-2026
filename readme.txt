=== PMI Events ===
Contributors: pmi
Tags: events, calendar, podcast, shortcode
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Calendario eventi e gestione episodi podcast con shortcode, CPT dedicati e widget interattivi.

== Description ==

Plugin WordPress autonomo per gestire eventi e podcast, con calendario e griglie interattive per il front-end.

**Funzionalità incluse:**

* Custom Post Type `pmi_event`
* Meta campi: data, ora, luogo, organizzatore
* Shortcode calendario: `[pmi_calendar]`
* Navigazione mensile AJAX
* Selezione giorno con elenco eventi
* Custom Post Type `pmi_podcast` per gli episodi del podcast (solo back-end al momento)
* Meta campi podcast: numero episodio, ospiti, intervistatori, PDU, link alle piattaforme di ascolto (con icone) e link extra illimitati

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

**Shortcode griglia podcast**

`[pmi_podcast_grid]`

Mostra una griglia di card con gli ultimi episodi (immagine, numero episodio, titolo, ospiti, descrizione, PDU e icone delle piattaforme di ascolto).

Attributi opzionali:

* `title` – titolo sopra la griglia (default: vuoto)
* `posts_per_page` – numero di episodi mostrati (default: 6)
* `category` – slug della categoria podcast da filtrare
* `columns` – colonne della griglia da desktop, 2-4 (default: 3)
* `archive_link` – mostra il link "Vedi tutti gli episodi" (yes/no, default: yes)
* `archive_label` – testo del link archivio

Esempio:

`[pmi_podcast_grid title="Ultimi episodi" posts_per_page="3" columns="3"]`

**Shortcode link ascolto episodio (template singolo)**

`[pmi_podcast_links]`

Elenca tutte le piattaforme dove ascoltare l'episodio corrente, con icona colorata e titolo (Apple Podcast, Spotify, YouTube, ecc. + eventuali link extra).

Attributi opzionali:

* `title` – titolo sopra l'elenco (default: Ascolta su)
* `post_id` – ID episodio (default: post corrente nel template Elementor)

Esempio per template episodio:

`[pmi_podcast_links title="Il podcast è disponibile su:"]`

== Installation ==

1. Copia la cartella `pmi-events` in `wp-content/plugins/`
2. Attiva il plugin da **Plugin** in wp-admin
3. Crea eventi da **Eventi PMI** o episodi da **Podcast**
4. Inserisci `[pmi_calendar]`, `[pmi_calendar_full]` o `[pmi_podcast_grid]` in una pagina o in Elementor (widget Shortcode)

== Changelog ==

= 1.5.0 =
* Nuovo Custom Post Type `pmi_podcast` per gli episodi del podcast, con tassonomia categorie dedicata.
* Meta box episodio: numero, ospiti, intervistatori, PDU, 6 link piattaforma fissi (Apple Podcast, Spotify, YouTube, YouTube Music, Amazon Music, Spreaker) con icone dedicate, più sezione "Altri link" illimitata e gestibile dall'admin.
* Nuovo shortcode `[pmi_podcast_grid]`: griglia di card episodio in stile design system.
* Nuovi tag dinamici Elementor (gruppo "PMI Podcast") per costruire il template della pagina episodio.

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
