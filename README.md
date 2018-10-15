# MyAlpineRocks
Learning project - web shop backend

ZADATAK:
Potrebno je napraviti aplikaciju za upravljanje kategorijama i proizvodima. 

Jedan proizvod može biti u više kategorija, a jedna kategorija može imati više proizvoda. 
Za kategoriju je potrebno čuvati id kategorije, naziv kategorije i opis. Kategorije su hijerarhijski uređene. Svaka kategorija može imati neograničen broj potkategorija i istovremeno pripadati najviše jednoj kategoriji (parent – child veza).
Za proizvod je potrebno čuvati id proizvoda, naziv, opis, cenu i slike proizvoda (neograničen broj slika).

Potrebno je kreirati bazu podataka (MySQL), napraviti backend, frontend i servis deo aplikacije.

Napomena: Kompletna implementacija celog zadatka mora da bude objektno orjentisana, uz opcionu MVC organizaciju bez korišćenja bilo kog framework-a.

Backend:

Backend delu aplikacije mogu da pristupe samo registrovani korisnici. Kada je korisnik ulogovan, u meniju pored opcija stoji i njegovo ime i mala slika.
Aplikacija ima 3 tipa korisnika: reader, writer i administrator. Reader može samo vršiti pregled podataka, writer može dodavati nove i ažurirati podatke postojećih kategorija i proizvoda, dok administrator može kreirati, brisati i ažurirati podatke o proizvodima i kategorijama, kao i o korisnicima. 

Potrebno je napraviti ekran za administraciju korisnika. Ovde administrator može dodavati, menjati i brisati korisnike. Svaki korisnik treba da ima ime i prezime, email adresu, sliku, šifru i auto-generisan API ključ.

Potrebno je napraviti ekran na kome se vidi spisak kategorija. Odabirom kategorije, otvara se forma za ažuriranje podataka. Odabirom opcije za novu kategoriju, otvara se forma za unos nove kategorije. Odabirom opcije za brisanje, briše se određena kategorija.
Forma za kategoriju je potrebno da ima samo polja navedena gore i opciju izbora roditeljske kategorije. 

Potrebno je napraviti ekran na kome se vidi spisak proizvoda. Odabirom proizvoda, otvara se forma za ažuriranje podataka. Odabirom opcije za nov proizvod, otvara se forma za unos novog proizvoda. Odabirom opcije za brisanje, briše se određeni proizvod.

Forma za proizvod je potrebno da ima polja navedena gore. Ispod polja je potrebno omogućiti dodavanje kategorija proizvodu. Odjednom je moguće dodati više kategorija za određeni proizvod.

Frontend:

Izgled svih stranica je proizvoljan. U nastavku su navedene osnovne stvari koje treba da se prikazuju na stranicama.

Potrebno je kreirati stranicu za prikaz kategorija. Odabirom određene kategorije, prikazuju se proizvodi te kategorije. Sa leve strane u sidebar-u se prikazuju potkategorije. Klikom na određeni proizvod, prikazuju se stranica sa svim detaljima tog proizvoda. 

Omogućiti i pretraživanje proizvoda. Stranica sa rezultatima pretrage treba da izgleda slično kao stranica za prikaz proizvoda jedne kategorije.

Servis REST API:

Potrebno je omogućiti da aplikacija poseduje REST servise. Slanjem zahteva na odgovarajući url, aplikacija je potrebno da vrati rezultat u JSON ili XML formatu. Potrebno je kreirati dva servisa. Jedan će biti zadužen za kategorije (url: domainname/api/category/*), a jedan za proizvode (url: domainname/api/product/*). Svaki servis treba da omogući kreiranje, pregled, ažuriranje i brisanje podataka. Autentikacija se radi preko header-a zahteva gde se šalje email i API ključ korisnika. Prava pristupa treba da budu ista kao i za backend deo. Ako korisnik nema prava pristupa određenoj funkciji (na primer writer pošalje zahtev za menjanjem podataka) server treba da vrati HTTP status 403. Za čitanje podataka koristiti GET, za kreiranje koristiti POST, za ažuriranje PATCH i za brisanje DELETE request.
