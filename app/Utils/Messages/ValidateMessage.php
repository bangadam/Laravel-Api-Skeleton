<?php

namespace App\Utils\Messages;

class ValidateMessage
{
    const DEFAULTREQUIRED = 'Wajib Diisi';
    const UNIQUE = 'Harus Unik';
    const MINIMUMCHAR = 'Kata Terlalu Pendek';
    const MAXIMUMCHAR = 'Kata Terlalu Panjang';

    const STATUSREQUIRED = 'Status Wajib Ada';
    const STATUSINVALID = 'Status Tidak Valid';

    const MUSTINTEGER = 'Wajib Angka';
    const MINIMUMINTEGER = 'Angka Kurang Dari Batas Minimal';
    const MAXIMUMINTEGER = 'Angka Melebihi Batas Maksimal';

    const DEFAULTBETWEEN = 'Nilai Diluar Batas Yang Telah Ditentukan';
}
