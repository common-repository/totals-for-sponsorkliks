=== Totals for Sponsorkliks ===
Contributors: DeBoekies
Tags: Sponsorkliks, shortcode, widget
Requires at least: 4.2.3
Tested up to: 5.5
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

Deze plugin toont het totaalbedrag dat verdiend is via SponsorKliks.

== Description ==

Deze plugin toont het totaalbedrag dat verdiend is via SponsorKliks.
Het totaalbedrag kan op twee manieren worden getoond:

* Via een _shortcode_ op elke willekeurige plek op pagina's of berichten;
* Via een _widget_ die in een sidebar of een footer getoond kan worden.

== Gebruik plugin ==

**Een shortcode**
Bij gebruik van de shortcode is de syntax

`[totals-for-sponsorkliks club="12345"]`

waarbij het eigen clubnummer moet worden ingevuld. Standaard wordt het totaalbedrag getoond met Euro-teken er voor. De plugin houdt rekening met de standaard numerieke notatie van de local settings. Deze shortcode kan overal op de website geplaatst worden, in berichten of op pagina's.

**Voorbeeld:**

`Onze club heeft dankzij jullie [totals-for-sponsorkliks club="12345"] verdiend`

**Extra!**
Er kunnen ook andere bedragen worden getoond, door aan de shortcode de parameter 'commission' toe te voegen. De bijbehorende bedragen kunnen interessant zijn om in verslag te tonen. De beschikbare commissie-codes zijn: _transferred_, _qualified_, _pending_ en _total_.

**Voorbeeld:**

`Er is nog [totals-for-sponsorkliks club="12345" commission="pending"] in afwachting van goedkeuring`

**Een widget**
De widget kan na activatie van de plugin in de widgetlijst worden gevonden. Standaard zal rekening worden gehouden met de lay-out die het actieve thema gebruikt.

* Vul het clubnummer in die u gekregen heeft van SponsorKliks
* Vul een titel in voor de widget die op de gekozen plek zal worden getoond
* Kies er wel of niet voor om het SponsorKliks logo te tonen in de widget
* Het totaalbedrag in euro's zal worden getoond. Vul een tekst in die voorafgaat aan het getoonde bedrag


== Installation ==
1. Installeer de plugin door gebruik te maken van het Wordpress plugin scherm en kies 'Nieuwe plugin'
2. Activeer de plugin op de plugin pagina

== Screenshots ==
1. Het gebruik van de shortcode in een bericht of pagina
2. De widget, die kan worden toegevoegd aan sidebar of footer
3. De widget zoals die er (afhankelijk van het gebruikte thema) uit kan zien

== FAQ ==

== Changelog ==

= V0.1.5 =
The widget showed 'transferred' amount only, which actually should be 'total'. Added a dropdown box where you can
select what commission type you want to show.

= V0.1.4 =
A nested function that converted money string to float, was called several times and therefor redeclared.
Moved the nested function to make it standalone

= V0.1.3 =
Changed the result of the default call to SponsorKliks API. Default commission_type was "transferred", now set to "total".
Reduced the commission_types to 4: "transferred", "qualified", "pending" and "total", due to the fact that "qualified" actually contains "accepted", "qualified" and "sponsorkliks".
This solution is more in line with the way SponsorKliks itself propagates its values.

= V0.1.0 =
First version of plugin
