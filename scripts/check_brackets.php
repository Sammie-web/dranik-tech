<?php
$path = __DIR__ . '/../resources/views/bookings/create.blade.php';
$s = file_get_contents($path);
$pairs = ['('=>')','['=>']','{'=>'}'];
$openers = array_keys($pairs);
$closers = array_values($pairs);
$stack = [];
$len = strlen($s);
for ($i=0; $i < $len; $i++) {
    $c = $s[$i];
    if (isset($pairs[$c])) {
        array_push($stack, [$c, $i]);
    } else {
        $idx = array_search($c, $closers);
        if ($idx !== false) {
            if (empty($stack)) {
                echo "Unmatched closing $c at pos $i\n";
                exit(1);
            }
            $top = array_pop($stack);
            $expected = $pairs[$top[0]];
            if ($expected !== $c) {
                echo "Mismatch: expected $expected but found $c at pos $i (opened $top[0] at pos $top[1])\n";
                exit(1);
            }
        }
    }
}
if (!empty($stack)) {
    $top = end($stack);
    echo "Unclosed {$top[0]} at pos {$top[1]}\n";
    exit(1);
}

echo "All balanced\n";
return 0;
