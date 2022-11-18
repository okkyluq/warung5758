<?php

namespace App\Repositories;

interface HistoryItemRepositoryInterface 
{
    public function insertHistoryItem(array $attributes, object $transaksi);
} 
