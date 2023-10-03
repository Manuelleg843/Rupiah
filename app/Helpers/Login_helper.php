<?php

namespace App\Helpers;

function is_logged_in($session)
{
    $email = $session->get('email');
    if (!$email) {
        session()->setFlashdata('pesan', 'Anda harus login terlebih dahulu.');
        return redirect()->to('/login');
    }
}
