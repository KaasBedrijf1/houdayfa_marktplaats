-- Houdayfa Marktplaats — demo-advertenties (marktplaats-achtige voorbeelden)
-- Vervangt ALLE bestaande advertenties. Maak eerst backup als je echte data hebt.
--
-- FOUT #1054 "Onbekende kolom 'user_id'"?
-- Je `listings`-tabel is nog het oude schema. Eerst uitvoeren (op dezelfde database):
--   sql/migration_auth_moderation.sql
-- Of alles opnieuw: lege DB + sql/schema.sql, daarna dit seed-bestand.
--
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE listings;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO listings (category_id, title, description, price, city, seller_name, seller_email, image, user_id, status) VALUES
(1, 'Peugeot 208 2018', 'Benzine, 95.000 km, NAP, airco, cruise control. APK tot volgend jaar. Kleine parkeerdeuk achter.', 8900.00, 'Casablanca', 'Youssef A.', 'youssef.demo@example.com', NULL, NULL, 'approved'),
(1, 'Volkswagen Golf 7 1.4 TSI', 'Nette hatchback, dealeronderhouden tot 2023. Winterbanden op velgen meegeleverd.', 12450.00, 'Rabat', 'Mehdi K.', 'mehdi.demo@example.com', NULL, NULL, 'approved'),
(1, 'Winterbanden 205/55 R16 set van 4', 'Continental, 6 mm profiel, een seizoen gebruikt. Ophalen in overleg.', 220.00, 'Amsterdam', 'Jan V.', 'jan.demo@example.com', NULL, NULL, 'approved'),
(1, 'Dashcam 4K + park mode', 'Inclusief SD-kaart 128GB en hardwire kit. Weinig gebruikt, werkt perfect.', 89.00, 'Rotterdam', 'Sara L.', 'sara.demo@example.com', NULL, NULL, 'approved'),
(1, 'Kinderautostoel groep 1-2-3', 'Isofix, wasbaar hoes, geen ongelopen. Voldoet aan norm.', 45.00, 'Utrecht', 'Lisa M.', 'lisa.demo@example.com', NULL, NULL, 'approved'),
(1, 'Oldtimer onderdelen DAF variomatic', 'Diverse onderdelen uit nalatenschap. Mail voor lijst met foto''s.', 350.00, 'Eindhoven', 'Tom B.', 'tom.demo@example.com', NULL, NULL, 'approved'),

(2, 'MacBook Air M1 8GB 256GB', 'Accu 92% health, geen deuken. Originele lader. Factuur niet meer aanwezig.', 675.00, 'Marrakech', 'Omar H.', 'omar.demo@example.com', NULL, NULL, 'approved'),
(2, 'Samsung Galaxy S21 128GB', 'Simlockvrij, scherm licht gebruikt maar geen barsten. Met hoes.', 285.00, 'Tanger', 'Fatima Z.', 'fatima.demo@example.com', NULL, NULL, 'approved'),
(2, 'Sony WH-1000XM4 koptelefoon', 'Noise cancelling, oorkussens vervangen vorig jaar. Doos aanwezig.', 189.00, 'Den Haag', 'Kevin P.', 'kevin.demo@example.com', NULL, NULL, 'approved'),
(2, 'Dell UltraSharp 27 inch QHD', 'USB-C hub, kleurkalibratie fabriek. Geen dode pixels.', 275.00, 'Utrecht', 'Nina R.', 'nina.demo@example.com', NULL, NULL, 'approved'),
(2, 'PlayStation 5 + 2 controllers', 'Disc versie, 825GB. Spelletjes apart te koop. Ophalen alleen.', 449.00, 'Rotterdam', 'Bas G.', 'bas.demo@example.com', NULL, NULL, 'approved'),
(2, 'iPad 9e gen 64GB WiFi', 'Kind gebruikt voor school, lichte krasjes op achterkant. Hoes + screenprotector.', 265.00, 'Amsterdam', 'Emma D.', 'emma.demo@example.com', NULL, NULL, 'approved'),
(2, 'Mechanisch toetsenbord Keychron K2', 'Hot-swap, brown switches, backlight. Weinig getypt.', 95.00, 'Fès', 'Hicham T.', 'hicham.demo@example.com', NULL, NULL, 'approved'),
(2, 'Epson EcoTank printer ET-2850', 'Nog veel inkt, geschikt voor thuiswerk. Gebruiksaanwijzing digitaal.', 155.00, 'Groningen', 'Roos V.', 'roos.demo@example.com', NULL, NULL, 'approved'),
(2, 'Canon EOS 2000D + 18-55mm kit', 'Beginners DSLR, sensor schoon, 8k sluiter. Tas en extra accu.', 320.00, 'Casablanca', 'Amina B.', 'amina.demo@example.com', NULL, NULL, 'approved'),
(2, 'Roomba stofzuigerrobot 675', 'Nieuwe borstels geplaatst. Werkt op tegels en laminaat.', 125.00, 'Tilburg', 'Mark S.', 'mark.demo@example.com', NULL, NULL, 'approved'),
(2, 'Sonos One SL speaker', 'Stereo paar mogelijk; dit is één stuk. Nette staat.', 135.00, 'Rabat', 'Ilse K.', 'ilse.demo@example.com', NULL, NULL, 'approved'),
(2, 'Garmin Forerunner 245', 'GPS hardloophorloge, bandje origineel. Oplader mee.', 165.00, 'Agadir', 'Yassine M.', 'yassine.demo@example.com', NULL, NULL, 'approved'),

(3, 'Vintage leren 3-zits bank', 'Patina, solide frame. Breedte 210 cm. Verplaatsing voor rekening koper.', 420.00, 'Marrakech', 'Karim L.', 'karim.demo@example.com', NULL, NULL, 'approved'),
(3, 'IKEA KALLAX 4x4 wit', 'In elkaar gezet, kleine gebruikssporen. Alleen demontage ophalen.', 65.00, 'Amsterdam', 'Sophie W.', 'sophie.demo@example.com', NULL, NULL, 'approved'),
(3, 'Eettafel eiken 180x90', 'Massief, poten demontabel. Geschikt voor 6-8 personen.', 380.00, 'Utrecht', 'Daan F.', 'daan.demo@example.com', NULL, NULL, 'approved'),
(3, 'Hanglamp messing industrieel', '3 lichtpunten, dimbaar met externe dimmer. Snoer 1,5m.', 75.00, 'Rotterdam', 'Julia H.', 'julia.demo@example.com', NULL, NULL, 'approved'),
(3, 'Vloerkleed Berber 200x300', 'Wol blend, professioneel gereinigd. Geen gaten.', 195.00, 'Casablanca', 'Salma N.', 'salma.demo@example.com', NULL, NULL, 'approved'),
(3, 'Spiegel rond 80cm', 'Zwart metalen frame, ophangsysteem aanwezig.', 55.00, 'Den Haag', 'Piet J.', 'piet.demo@example.com', NULL, NULL, 'approved'),
(3, 'Plantenrek bamboe 5 etages', 'Voor binnen, max 15 kg per plank. Demontabel.', 38.00, 'Eindhoven', 'Lotte C.', 'lotte.demo@example.com', NULL, NULL, 'approved'),
(3, 'Bureau wit 120x60 verstelbaar', 'Handmatig in hoogte, kabelgoot aanwezig. Bureaustoel niet inbegrepen.', 110.00, 'Rabat', 'Nadia E.', 'nadia.demo@example.com', NULL, NULL, 'approved'),
(3, 'Gordijnen op maat lichtdicht', 'Breedte 280 cm hoog 240, grijs. Rails niet inbegrepen.', 85.00, 'Tanger', 'Hugo A.', 'hugo.demo@example.com', NULL, NULL, 'approved'),
(3, 'Servies set 12 personen', 'Porselein wit met goudrand, kist met schuim.', 120.00, 'Fès', 'Imane R.', 'imane.demo@example.com', NULL, NULL, 'approved'),

(4, 'Winterjas The North Face maat M', 'Gore-Tex, geen scheuren. Professioneel gewassen.', 165.00, 'Amsterdam', 'Noor K.', 'noor.demo@example.com', NULL, NULL, 'approved'),
(4, 'Nike Air Max maat 42', 'Witte sneakers, lichte geleuring zool. Originele doos.', 55.00, 'Rotterdam', 'Timo V.', 'timo.demo@example.com', NULL, NULL, 'approved'),
(4, 'Zara blazer dames maat S', 'Zwart, getailleerd, één keer gedragen naar bruiloft.', 35.00, 'Utrecht', 'Fleur B.', 'fleur.demo@example.com', NULL, NULL, 'approved'),
(4, 'Dr. Martens 1460 maat 41', 'Geolied leer, comfort zool. Gebroken in.', 95.00, 'Casablanca', 'Layla S.', 'layla.demo@example.com', NULL, NULL, 'approved'),
(4, 'Designer handtas (demo replica)', 'Let op: niet-authentiek merk, wel net afgewerkt. Prijs eerlijk.', 40.00, 'Marrakech', 'Souad M.', 'souad.demo@example.com', NULL, NULL, 'approved'),
(4, 'Kinder ski-jas 128', 'Waterdicht, capuchon afneembaar. Eén seizoen gebruikt.', 28.00, 'Groningen', 'Erik N.', 'erik.demo@example.com', NULL, NULL, 'approved'),

(5, 'Giant mountainbike 29 inch L', 'Hydraulische remmen, 1x12 aandrijving. Kleine kras op frame.', 650.00, 'Utrecht', 'Ruben O.', 'ruben.demo@example.com', NULL, NULL, 'approved'),
(5, 'Tennisracket Babolat Pure Drive', 'Besnaring recent vernieuwd. Grip 3.', 95.00, 'Den Haag', 'Iris T.', 'iris.demo@example.com', NULL, NULL, 'approved'),
(5, 'Haltère set 2x20kg verstelbaar', 'Kunststof platen, stangen chroom. Ophalen zwaar pakket.', 85.00, 'Rotterdam', 'Mohamed A.', 'mohamed.demo@example.com', NULL, NULL, 'approved'),
(5, 'Camping tent 4 personen', 'Waterkolom 3000mm, alle haringen aanwezig. Eén scheur gerepareerd.', 110.00, 'Agadir', 'Rachid F.', 'rachid.demo@example.com', NULL, NULL, 'approved'),
(5, 'Elektrische step Xiaomi Pro 2', 'Accu ~80% van origineel, firmware NL. Helm niet inbegrepen.', 320.00, 'Amsterdam', 'Jesse L.', 'jesse.demo@example.com', NULL, NULL, 'approved'),
(5, 'Golfset heren rechtshandig', '7 ijzers + woods + putter + tas. Geschikt voor beginner.', 225.00, 'Eindhoven', 'Willem D.', 'willem.demo@example.com', NULL, NULL, 'approved'),
(5, 'Yoga mat Manduka 5mm', 'Antislip, lichte gebruikssporen. Opgerold met band.', 42.00, 'Rabat', 'Chaimae B.', 'chaimae.demo@example.com', NULL, NULL, 'approved'),
(5, 'DJI Mini 2 drone Fly More combo', '3 accu''s, propellers reserve, koffer. Registratie verwijderd.', 385.00, 'Tanger', 'Samir H.', 'samir.demo@example.com', NULL, NULL, 'approved'),

(6, 'Herman Miller Aeron maat B', 'Lumbale support, netweave. Kantoorupgrade, verkoop overschot.', 575.00, 'Amsterdam', 'Studio NL', 'studio.demo@example.com', NULL, NULL, 'approved'),
(6, 'HP LaserJet Pro M404dn', 'Toner halfvol, duplex, netwerk. Factuur beschikbaar.', 185.00, 'Rotterdam', 'KantoorX', 'kantoorx.demo@example.com', NULL, NULL, 'approved'),
(6, 'Whiteboard magnetisch 120x90', 'Wielen en standaard, markers en wisser mee.', 95.00, 'Utrecht', 'FlexWerk', 'flexwerk.demo@example.com', NULL, NULL, 'approved'),
(6, 'Kassalade + POS tablet houder', 'Gebruikt horeca, werkt op USB. Software niet inbegrepen.', 120.00, 'Casablanca', 'CafeDemo', 'cafe.demo@example.com', NULL, NULL, 'approved'),
(6, 'Pallet rekken metaal 5 stuks', 'Industrieel, demontabel. Alleen transport zelf regelen.', 200.00, 'Marrakech', 'LogiDemo', 'logi.demo@example.com', NULL, NULL, 'approved'),
(6, 'Beamer Epson Full HD 3500 ANSI', 'Lamp 1200 uur gebruikt, HDMI + VGA. Geschikt vergaderruimte.', 275.00, 'Den Haag', 'ITVerkoop', 'itverkoop.demo@example.com', NULL, NULL, 'approved'),
(6, 'Kantoorkasten grijs 80cm breed', '2 stuks identiek, slot met sleutel. Demontage service +€50.', 140.00, 'Eindhoven', 'ClearOffice', 'clear.demo@example.com', NULL, NULL, 'approved'),
(5, 'Skateboard setup compleet', 'Deck 8.25 inch, Independent trucks, 54 mm wielen. Licht gebruikt.', 45.00, 'Utrecht', 'Finn V.', 'finn.demo@example.com', NULL, NULL, 'approved');
