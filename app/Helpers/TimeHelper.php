<?php

if (! function_exists('jam_sewa')) {
    function jam_sewa(int $mulai = 7, int $akhir = 22): array
    {
        return range($mulai, $akhir);
    }
}

if (! function_exists('format_jam')) {
    function format_jam(int $jam): string
    {
        return str_pad($jam, 2, '0', STR_PAD_LEFT) . '.00';
    }
}
