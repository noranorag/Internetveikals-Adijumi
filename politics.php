<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privātuma un sīkdatņu politika</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="announcement" id="announcement"></div>
    <?php include 'files/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <h2 class="text-center mb-4">Privātuma un sīkdatņu politika</h2>
        <p><strong>Pēdējās izmaiņas:</strong> 16/06/2025</p>
        <p>Šī privātuma un sīkdatņu politika paskaidro, kā mēs – gggadijumi.lv – apstrādājam jūsu personas datus un izmantojam sīkdatnes.</p>
        <p>Mēs apņemamies aizsargāt jūsu privātumu un nodrošināt, ka jūsu dati tiek apstrādāti saskaņā ar Vispārīgo datu aizsardzības regulu (GDPR) un Latvijas likumiem.</p>

        <h3>1. Kādi personas dati tiek vākti?</h3>
        <p>Mēs vācam tikai tos datus, ko apmeklētāji iesniedz mūsu mājaslapā:</p>
        <ul>
            <li>Vārds un uzvārds</li>
            <li>E-pasta adrese</li>
            <li>Parole (šifrēta formā)</li>
            <li>Tālruņa numurs</li>
            <li>Adrese vai piegādes informācija (ja nepieciešams)</li>
        </ul>
        <p>Dati tiek vākti brīvprātīgi – piemēram, reģistrējoties, aizpildot kontaktformu vai veicot pirkumu.</p>

        <h3>2. Datu izmantošanas mērķi</h3>
        <p>Jūsu dati tiek izmantoti, lai:</p>
        <ul>
            <li>Nodrošinātu piekļuvi jūsu kontam</li>
            <li>Komunicētu ar jums par pasūtījumiem vai jautājumiem</li>
            <li>Nosūtītu paziņojumus vai e-pastus (tikai, ja tam esat piekritis)</li>
            <li>Uzlabotu mūsu pakalpojumus</li>
        </ul>

        <h3>3. Datu glabāšana</h3>
        <p>Personas dati tiek glabāti tikai tik ilgi, cik tas nepieciešams iepriekš minētajiem nolūkiem, vai saskaņā ar normatīvo aktu prasībām.</p>

        <h3>4. Datu nodošana trešajām pusēm</h3>
        <p>Mēs nenododam jūsu personas datus trešajām personām, izņemot:</p>
        <ul>
            <li>Ja tas nepieciešams pakalpojumu nodrošināšanai (piemēram, piegādātāji)</li>
            <li>Ja to prasa likums</li>
        </ul>

        <h3>5. Jūsu tiesības</h3>
        <p>Jums ir tiesības:</p>
        <ul>
            <li>Pieprasīt piekļuvi saviem datiem</li>
            <li>Labot vai dzēst savus datus</li>
            <li>Ierobežot apstrādi vai iebilst tai</li>
            <li>Atsaukt piekrišanu (piemēram, e-pastu saņemšanai)</li>
            <li>Iesniegt sūdzību Datu valsts inspekcijā (<a href="https://www.dvi.gov.lv" target="_blank">www.dvi.gov.lv</a>)</li>
        </ul>

        <h3>6. Sīkdatņu izmantošana</h3>
        <p>Mūsu mājaslapā tiek izmantotas sīkdatnes (cookies), lai:</p>
        <ul>
            <li>Nodrošinātu lapas darbību (valodas izvēle, sesijas)</li>
            <li>Analizētu lapas apmeklējumu (piemēram, Google Analytics – ja tiek izmantots)</li>
        </ul>
        <p>Pirmreizējā apmeklējuma laikā Jums tiek lūgta piekrišana sīkdatņu izmantošanai. Jūs varat atteikties vai mainīt iestatījumus savā pārlūkprogrammā.</p>

        <h3>7. Drošība</h3>
        <p>Mēs izmantojam atbilstošus tehniskus un organizatoriskus pasākumus, lai aizsargātu Jūsu datus pret nesankcionētu piekļuvi, noplūdi vai izmainīšanu.</p>

        <h3>8. Kontaktinformācija</h3>
        <p>Ja Jums ir jautājumi par datu apstrādi, lūdzu, sazinieties ar mums:</p>
        <ul>
            <li>E-pasts: <a href="mailto:gunita.sjbuve@inbox.lv">gunita.sjbuve@inbox.lv</a></li>
            <li>Mājaslapa: <a href="https://www.gggadijumi.lv" target="_blank">https://www.gggadijumi.lv</a></li>
        </ul>
    </div>

    <?php include 'files/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="scripts.js"></script>
</body>
</html>