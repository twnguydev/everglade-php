<?php

namespace App\Model;

use Core\ORM;
use App\Entity\History;

class HistoryModel extends ORM
{
    public function createHistoryInstance(): History
    {
        return new History();
    }

    public function checkRegistration(array $data): bool
    {
        $history = $this->createHistoryInstance();
    
        $userId = $data['id_user'] ?? null;
        $movieId = $data['id_movie'] ?? null;
        $date = $data['date'] ?? null;

        if (!$userId || !$movieId || !$date) {
            return false;
        }

        $history->setIdUser($userId);
        $history->setIdMovie($movieId);
        $history->setDate($date);


        return $this->save('history', $history);
    }
}