<?php
session_start();

$flag = "DDDhalal{0oOoOhnUuUuUuuUu_Br4nk4";
$stages = [
    8 => ["InfokanSahur", "Total Basis Ku Ada 96 :)", base64_encode(base32_encode("InfokanSahur"))],
    2 => ["Ramadhan", "Keliling lapangan terooooos sampe capek", str_rot13("Ramadhan")],
    7 => ["JanganLupaSholatBang", "Beeep Boop Hehehehehehe", bin2hex("JanganLupaSholatBang")],
    9 => ["Es tehmu seh okeh ra", "Lanjutkan Solvenya Mas Okta", implode(' ', array_map(fn($char) => decoct(ord($char)), str_split("Es tehmu seh okeh ra")))
            ],
    5 => ["Ya sana dijual lah", "Kata Boboiboy mah, teeerbaliiik", strrev("Ya dijual lah")],
    6 => [
        "Di Bikini Bottom Ada Spongebob Squarepant",
        "Selamat Datang di perkumpulan Basecamp",
        base64_encode(base32_encode(base64_encode(base32_encode("Di Bikini Bottom Ada Spongebob Squarepant"))))
    ],
    3 => ["Mudik Yok Mudik Pulang Kampung", "Satu kereta melewati pagar menggunakan 3 Rel besi", railFenceEncrypt("Mudik Yok Mudik Pulang Kampung", 3)],
    1 => ["Malam Lailatul Qadar", "Terbalik Teksnya, tapi ko bukan reverse ya???", atbashCipher("Malam Lailatul Qadar")],
    4 => ["Sahuuuur", "Beeep Boop Beeep", bin2hex("Sahuuuur")],
    10 => ["INFOKAN FLAGNYA MIN", "Tersembunyi dari mata, dirasakan oleh mouse, disentuh oleh CTRL + C", bin2hex("INFOKAN FLAGNYA MIN")]
];

$current_stage = $_SESSION['stage'] ?? 1;

$counter = $_SESSION['counter'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['input'] ?? '';

    if ($input === "Bismillah Kebuka Ini Brankas") {
        $counter = 1000;
        $_SESSION['counter'] = $counter;
        $current_stage = 10; // Optional: Set stage to the final stage
        $_SESSION['stage'] = $current_stage;
    } elseif ($input === $stages[$current_stage][0]) {
        $current_stage++;
        $_SESSION['stage'] = $current_stage;
        $counter++;
        $_SESSION['counter'] = $counter;
    }
}

if ($current_stage <= 10 && $counter <= 1000) {
    $next_encoded_string = $stages[$current_stage][2];
    $hint = $stages[$current_stage][1];
} else {
    $current_stage = 1;
    $_SESSION['stage'] = 1;
    if ($counter == 998) {
        $counter = 0;
        $hint = "Nooo Counternya Ke Reseeet";
    }
    if ($counter >= 1000) {
        $next_encoded_string = "Flag : " . htmlspecialchars($flag);
        $hint = "Lessgoooooo Solveee Troooos";
    } else {
        $next_encoded_string = "You need to complete 1000 challenges to get the flag!";
        $hint = "Current progress: $counter/1000, Reset Page To Continue";
    }
}

function base32_encode($data)
{
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $binaryString = '';

    foreach (str_split($data) as $character) {
        $binaryString .= str_pad(decbin(ord($character)), 8, '0', STR_PAD_LEFT);
    }

    $paddingLength = (5 - (strlen($binaryString) % 5)) % 5;
    $binaryString = str_pad($binaryString, strlen($binaryString) + $paddingLength, '0', STR_PAD_RIGHT);

    $encoded = '';
    foreach (str_split($binaryString, 5) as $chunk) {
        $encoded .= $alphabet[bindec($chunk)];
    }

    $encoded = str_pad($encoded, ceil(strlen($encoded) / 8) * 8, '=');

    return $encoded;
}

function base32_decode($data)
{
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $binaryString = '';

    $data = rtrim($data, '=');

    foreach (str_split($data) as $character) {
        $binaryString .= str_pad(decbin(strpos($alphabet, $character)), 5, '0', STR_PAD_LEFT);
    }

    $decoded = '';
    foreach (str_split($binaryString, 8) as $chunk) {
        if (strlen($chunk) === 8) {
            $decoded .= chr(bindec($chunk));
        }
    }

    return $decoded;
}

function railFenceEncrypt($text, $rails)
{
    $rail = array_fill(0, $rails, []);
    $down = false;
    $row = 0;
    for ($i = 0, $len = strlen($text); $i < $len; $i++) {
        $rail[$row][] = $text[$i];
        $down = ($row == 0 || $row == $rails - 1) ? !$down : $down;
        $row += $down ? 1 : -1;
    }
    return implode('', array_merge(...$rail));
}

function atbashCipher($text)
{
    $result = '';
    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            $base = ctype_upper($char) ? 'A' : 'a';
            $result .= chr(ord($base) + (25 - (ord($char) - ord($base))));
        } else {
            $result .= $char;
        }
    }
    return $result;
}

function vigenereCipher($text, $key)
{
    $result = '';
    $keyIndex = 0;
    $keyLength = strlen($key);
    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            $base = ctype_upper($char) ? 'A' : 'a';
            $shift = ord($key[$keyIndex % $keyLength]) - ord('a');
            $result .= chr((ord($char) - ord($base) + $shift) % 26 + ord($base));
            $keyIndex++;
        } else {
            $result .= $char;
        }
    }
    return $result;
}
function stringToOctal($input) {
    $octalArray = array_map(fn($char) => decoct(ord($char)), str_split($input));
    return implode(' ', $octalArray);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brankas Gus Miftah</title>
    <style>
        body {
            background-color: #e6e9ed;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            color: #2c3e50;
        }
        #container {
            background: linear-gradient(145deg, #d1d5db, #f0f3f8);
            width: 80%;
            max-width: 500px;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 1rem;
            color: #34495e;
        }
        input[type="text"] {
            width: 100%;
            padding: 0.7rem;
            border-radius: 5px;
            border: 1px solid #bdc3c7;
            margin-bottom: 1.5rem;
        }
        button {
            padding: 0.8rem 1.5rem;
            font-size: 1em;
            font-weight: bold;
            background: #2980b9;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #3498db;
        }
        .encoded-output {
            margin-top: 1.5rem;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.9em;
            word-wrap: break-word;
        }
        
           /* Vault image styling */
        .vault-theme {
            position: relative;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            background: #bdc3c7;
            margin: 2rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 5px 5px 15px rgba(0, 0, 0, 0.4);
        }
        .vault-theme:before {
            content: '';
            position: absolute;
            border: 3px solid #34495e;
            width: 120px;
            height: 120px;
            border-radius: 50%;
        }
        .vault-theme:after {
            content: '';
            position: absolute;
            width: 15px;
            height: 15px;
            background: #2c3e50;
            border-radius: 50%;
        }
        .vault-theme .dial {
            position: absolute;
            width: 5px;
            height: 60px;
            background: #34495e;
            border-radius: 2px;
            animation: spin 4s infinite linear;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<div id="container">
    <h1>Brankas Gus Miftah</h1><h2><?= htmlspecialchars($counter) ?> / 1000</h2>
     <div class="vault-theme">
            <div class="dial"></div>
        </div>
    <form method="POST">
        <input type="text" name="input" placeholder="Enter your answer" required>
        <button type="submit">Submit</button>
    </form>
    <div class="encoded-output">
        <strong>Encoded Text:</strong>
        <p>
        <?php
            if ($current_stage == 10) {
                echo '<span style="color: transparent;">' . htmlspecialchars($next_encoded_string) . '</span>';
            } else {
                echo htmlspecialchars($next_encoded_string);
            }
        ?>
        </p>

        <strong>Hint:</strong>
        <p><?= htmlspecialchars($hint) ?></p>
    </div>
</div>
</body>
</html>