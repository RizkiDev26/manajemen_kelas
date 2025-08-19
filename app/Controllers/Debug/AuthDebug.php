<?php

namespace App\Controllers\Debug;

use App\Controllers\BaseController;

class AuthDebug extends BaseController
{
    public function checkUser()
    {
        $u = $this->request->getGet('u');
        if (!$u) return $this->response->setStatusCode(400)->setJSON(['error'=>'missing u']);
        $db = db_connect();
        $user = $db->table('users')->where('username', $u)->get()->getFirstRow('array');
        $siswa = $db->table('siswa')->select('id, nama, nisn')->where('nisn', $u)->get()->getFirstRow('array');
        return $this->response->setJSON([
            'input' => $u,
            'user' => $user ? [
                'id' => (int)$user['id'],
                'role' => $user['role'],
                'is_active' => (int)$user['is_active'],
            ] : null,
            'siswa' => $siswa,
        ]);
    }

    // TEMP: password verification for local debugging only
    public function checkPassword()
    {
        $u = $this->request->getGet('u');
        $p = $this->request->getGet('p');
        if (!$u || $p === null) return $this->response->setStatusCode(400)->setJSON(['error'=>'missing u or p']);
        $db = db_connect();
        $user = $db->table('users')->where('username', $u)->get()->getFirstRow('array');
        if (!$user) return $this->response->setStatusCode(404)->setJSON(['ok'=>false,'reason'=>'user_not_found']);
        $ok = password_verify($p, $user['password']);
        return $this->response->setJSON([
            'ok' => $ok,
            'role' => $user['role'] ?? null,
            'is_active' => (int)($user['is_active'] ?? 0),
        ]);
    }
}
